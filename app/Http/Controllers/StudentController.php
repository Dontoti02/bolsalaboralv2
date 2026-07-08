<?php

namespace App\Http\Controllers;

use App\Models\JobOpportunityApplication;
use App\Models\JobOpportunityOffer;
use App\Mail\ApplicationSubmittedMail;
use App\Mail\NewApplicationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\UserNotification;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Show the student dashboard with real data.
     */
    public function dashboard()
    {
        $user = Auth::user();

        try {
            // Student applications metrics
            $totalApplications = JobOpportunityApplication::where('user_id', $user->id)->count();
            $pendingApplications = JobOpportunityApplication::where('user_id', $user->id)
                ->whereIn('status', ['postulated', 'under_review'])
                ->count();
            $acceptedApplications = JobOpportunityApplication::where('user_id', $user->id)
                ->where('status', 'accepted')
                ->count();

            // Recent applications for this student
            $recentApplications = JobOpportunityApplication::where('user_id', $user->id)
                ->with(['offer' => function ($q) {
                    $q->with('company:id,name');
                }])
                ->orderBy('created_at', 'desc')
                ->get();

            // All active offers (recommended jobs)
            $activeOffers = JobOpportunityOffer::with(['company:id,name,logo', 'state', 'category', 'workSchedule', 'location', 'contractType'])
                ->whereHas('state', function ($q) {
                    $q->where('key', 'active');
                })
                ->orderBy('publication_date', 'desc')
                ->take(6)
                ->get()
                ->map(function ($offer) {
                    $offer->location_name = $offer->location->name ?? 'No especificada';
                    $offer->company_name = $offer->company->name ?? 'Empresa';
                    return $offer;
                });

            // Retrieve student's CVs
            $cvs = DB::table('job_opportunity_user_cv')
                ->where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->orderBy('version', 'desc')
                ->get()
                ->map(function ($cv) {
                    $cv->filename = basename($cv->url);
                    $cv->uploaded_at = $cv->created_at ? \Carbon\Carbon::parse($cv->created_at)->format('d M Y') : '-';
                    return $cv;
                });
            $cvsCount = $cvs->count();

        } catch (\Exception $e) {
            $totalApplications = 0;
            $pendingApplications = 0;
            $acceptedApplications = 0;
            $recentApplications = collect();
            $activeOffers = collect();
            $cvsCount = 0;
            $cvs = collect();
        }

        try {
            $config = \Illuminate\Support\Facades\DB::table('system_configuration')->pluck('value', 'key')->all();
        } catch (\Exception $e) {
            $config = [];
        }

        return view('student.dashboard', compact(
            'totalApplications',
            'pendingApplications',
            'acceptedApplications',
            'recentApplications',
            'activeOffers',
            'cvsCount',
            'cvs',
            'config'
        ));
    }

    /**
     * Upload a new CV.
     */
    public function uploadCv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cv' => 'required|file|mimes:pdf|max:5120',
        ], [
            'cv.required' => 'El archivo del currículum es obligatorio.',
            'cv.mimes' => 'El currículum debe ser un archivo PDF.',
            'cv.max' => 'El tamaño del currículum no debe exceder los 5MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('cv');
            $filename = 'cv_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            $uploadPath = public_path('uploads/cvs');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $url = '/uploads/cvs/' . $filename;
            
            // Calculate next version
            $latestVersion = DB::table('job_opportunity_user_cv')
                ->where('user_id', auth()->id())
                ->max('version') ?? 0;
            
            $id = DB::table('job_opportunity_user_cv')->insertGetId([
                'user_id' => auth()->id(),
                'url' => $url,
                'version' => $latestVersion + 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Currículum subido exitosamente.',
                'cv' => [
                    'id' => $id,
                    'url' => $url,
                    'version' => $latestVersion + 1,
                    'filename' => $file->getClientOriginalName(),
                    'uploaded_at' => now()->format('d M Y')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir currículum: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a CV (soft-delete).
     */
    public function deleteCv($id)
    {
        try {
            $cv = DB::table('job_opportunity_user_cv')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$cv) {
                return response()->json([
                    'success' => false,
                    'message' => 'Currículum no encontrado o no pertenece a su usuario.'
                ], 404);
            }

            DB::table('job_opportunity_user_cv')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Currículum eliminado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar currículum: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a CV.
     */
    public function downloadCv($id)
    {
        $cv = DB::table('job_opportunity_user_cv')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->first();

        if (!$cv) {
            abort(404, 'Currículum no encontrado.');
        }

        $filePath = public_path($cv->url);
        if (!file_exists($filePath)) {
            abort(404, 'El archivo físico del currículum no existe.');
        }

        return response()->download($filePath);
    }

    /**
     * Apply to a job offer.
     */
    public function applyToOffer(Request $request, $offer_id)
    {
        $validator = Validator::make($request->all(), [
            'cv_id' => 'required|integer',
            'message' => 'nullable|string|max:1000',
        ], [
            'cv_id.required' => 'Debe seleccionar un currículum para postular.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe completar sus datos personales primero.'
                ], 400);
            }

            // Validate CV ownership
            $cv = DB::table('job_opportunity_user_cv')
                ->where('id', $request->cv_id)
                ->where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->first();

            if (!$cv) {
                return response()->json([
                    'success' => false,
                    'message' => 'El currículum seleccionado no es válido.'
                ], 400);
            }

            // Check if already applied
            $alreadyApplied = JobOpportunityApplication::where('user_id', $user->id)
                ->where('offer_id', $offer_id)
                ->exists();

            if ($alreadyApplied) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya ha postulado a esta oferta laboral.'
                ], 400);
            }

            $application = JobOpportunityApplication::create([
                'fullname' => $person->names,
                'program_study' => 'Computación y Sistemas', // default study program
                'message' => $request->message ?? '',
                'status' => 'postulated', // Initial status
                'cv' => $cv->url,
                'offer_id' => $offer_id,
                'user_id' => $user->id,
            ]);

            $application->load(['offer.company', 'user.person']);

            // Create in-app notifications
            try {
                $offer = $application->offer;
                if ($offer && $offer->company_id) {
                    $companyUser = \App\Models\User::where('company_id', $offer->company_id)->first();
                    if ($companyUser) {
                        \App\Models\UserNotification::create([
                            'user_id' => $companyUser->id,
                            'title' => 'Nueva postulación',
                            'message' => "El estudiante {$person->names} ha postulado a tu oferta: {$offer->title}",
                            'link' => '/company/dashboard?tab=applicants',
                        ]);
                    }
                }
                // Notify admins
                $admins = \App\Models\User::where('rol_id', 1)->get();
                foreach ($admins as $admin) {
                    \App\Models\UserNotification::create([
                        'user_id' => $admin->id,
                        'title' => 'Nueva postulación',
                        'message' => "El estudiante {$person->names} ha postulado a la oferta: " . ($offer ? $offer->title : ''),
                        'link' => '/admin/dashboard?tab=applications',
                    ]);
                }
            } catch (\Exception $e) {
                logger()->error('Error creating notification for new application: ' . $e->getMessage());
            }

            // Send email notifications using the mail settings configured in .env.
            try {
                $offer = $application->offer;
                if ($offer && $offer->company && $offer->company->email) {
                    Mail::to($offer->company->email)
                        ->send(new NewApplicationMail($application));
                }

                if ($user->email) {
                    Mail::to($user->email)
                        ->send(new ApplicationSubmittedMail($application));
                }
            } catch (\Exception $mailEx) {
                // Log mail error but don't break the application flow
                logger()->error('Error sending application notification email: ' . $mailEx->getMessage());
            }


            return response()->json([
                'success' => true,
                'message' => '¡Postulación enviada exitosamente!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar postulación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update student professional profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'names' => 'required|string|max:255',
            'phone' => 'nullable|string|max:9',
            'document_type' => 'required|string|in:DNI,CE,PASAPORTE',
            'document_number' => 'required|string|max:20',
            'sex' => 'nullable|string|in:M,F,O',
            'birth_date' => 'nullable|date|before:today',
            'native_language' => 'nullable|string|max:100',
            'about_me' => 'nullable|string|max:1000',
            'skills' => 'nullable|array',
            'hobbies' => 'nullable|array',
            'education' => 'nullable|array',
            'experience' => 'nullable|array',
        ], [
            'names.required' => 'El nombre completo es obligatorio.',
            'document_type.required' => 'El tipo de documento es obligatorio.',
            'document_number.required' => 'El número de documento es obligatorio.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $person = $user->person ?: new \App\Models\Person();
            $person->fill([
                'names' => trim($request->names),
                'phone' => $request->phone ?: '',
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'sex' => $request->sex ?: null,
                'birth_date' => $request->birth_date ?: null,
                'native_language' => $request->native_language ?: null,
                'about_me' => $request->about_me ?: null,
                'skills' => $request->skills ?: [],
                'hobbies' => $request->hobbies ?: [],
                'education' => $request->education ?: [],
                'experience' => $request->experience ?: [],
            ]);
            $person->save();

            // Associate person with user if not already set
            if (!$user->person_id) {
                $user->person_id = $person->id;
                $user->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado exitosamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Change student password.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Ingrese su contraseña actual.',
            'new_password.required'     => 'Ingrese la nueva contraseña.',
            'new_password.min'          => 'La contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed'    => 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'La contraseña actual no es correcta.'], 400);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'Contraseña actualizada exitosamente.']);
    }

    /**
     * Upload and update student profile photo (avatar).
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,jpg,png,webp|max:3072',
        ], [
            'avatar.required' => 'Debe seleccionar una imagen.',
            'avatar.image'    => 'El archivo seleccionado debe ser una imagen.',
            'avatar.mimes'    => 'La foto debe estar en formato JPG, PNG o WEBP.',
            'avatar.max'      => 'La foto no debe superar los 3MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            $uploadPath = public_path('uploads/avatars');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Borrar avatar anterior si existe
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                $oldPath = public_path($user->avatar);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            
            $file->move($uploadPath, $filename);
            $url = '/uploads/avatars/' . $filename;
            
            $user->avatar = $url;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil actualizada exitosamente.',
                'avatar_url' => $url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir la foto de perfil: ' . $e->getMessage()
            ], 500);
        }
    }
}

