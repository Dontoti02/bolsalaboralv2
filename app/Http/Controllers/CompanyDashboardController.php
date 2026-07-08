<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobOpportunityOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Mail\ApplicationApprovedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\UserNotification;
use App\Models\User;

use Illuminate\Support\Str;

class CompanyDashboardController extends Controller
{
    /**
     * Show the company dashboard overview.
     */
    public function showDashboard()
    {
        $user = Auth::user();
        
        try {
            $company = $user->company;

            if (!$company) {
                // Fallback or create one if none exists (should not happen with regular flow)
                $company = Company::create([
                    'name' => 'Empresa Nueva',
                    'ruc' => '00000000000',
                    'email' => $user->email,
                    'phone' => '',
                    'mailbox' => $user->email,
                    'is_verified' => true,
                ]);
                $user->company_id = $company->id;
                $user->save();
            }

            // Metrics
            $offersCount = JobOpportunityOffer::where('company_id', $company->id)->count();
            $activeOffersCount = JobOpportunityOffer::where('company_id', $company->id)
                ->where('state_id', 2) // Vigente / Activa
                ->count();

            $offerIds = JobOpportunityOffer::where('company_id', $company->id)->pluck('id')->toArray();

            $applicantsCount = 0;
            $pendingApplicantsCount = 0;
            $recentApplicants = collect();

            if (!empty($offerIds)) {
                $applicantsCount = DB::table('job_opportunity_applications')
                    ->whereIn('offer_id', $offerIds)
                    ->whereNull('deleted_at')
                    ->count();

                $pendingApplicantsCount = DB::table('job_opportunity_applications')
                    ->whereIn('offer_id', $offerIds)
                    ->whereNull('deleted_at')
                    ->where('status', 'postulated')
                    ->count();

                $applicants = DB::table('job_opportunity_applications')
                    ->whereIn('offer_id', $offerIds)
                    ->whereNull('job_opportunity_applications.deleted_at')
                    ->join('job_opportunity_offer', 'job_opportunity_applications.offer_id', '=', 'job_opportunity_offer.id')
                    ->leftJoin('user', 'job_opportunity_applications.user_id', '=', 'user.id')
                    ->leftJoin('person', 'user.person_id', '=', 'person.id')
                    ->select(
                        'job_opportunity_applications.*',
                        'job_opportunity_offer.title as offer_title',
                        'user.avatar as user_avatar',
                        'person.names as person_names',
                        'person.career as person_career',
                        'person.about_me as person_about_me',
                        'person.skills as person_skills'
                    )
                    ->orderBy('job_opportunity_applications.created_at', 'desc')
                    ->get();
                
                $recentApplicants = $applicants->take(5);
            } else {
                $applicants = collect();
            }

            // All Offers
            $offers = JobOpportunityOffer::where('company_id', $company->id)
                ->with(['state', 'location', 'workSchedule', 'contractType', 'category'])
                ->withCount(['applications as applicants_count' => function ($q) {
                    $q->whereNull('deleted_at');
                }])
                ->latest('publication_date')
                ->get();
            $recentOffers = $offers->take(5);

            // Load options for publishing modal
            $categories = DB::table('job_opportunity_offer_category')->get();
            $locations = DB::table('job_opportunity_location')->get();
            $schedules = DB::table('job_opportunity_work_schedules')->get();
            $contracts = DB::table('job_opportunity_contract_types')->get();

        } catch (\Exception $e) {
            $company = new Company([
                'name' => 'Empresa de Prueba',
                'ruc' => '00000000000',
                'email' => $user ? $user->email : 'company@test.com',
                'phone' => '',
                'mailbox' => $user ? $user->email : 'company@test.com',
                'is_verified' => true,
            ]);
            $offersCount = 0;
            $activeOffersCount = 0;
            $applicantsCount = 0;
            $pendingApplicantsCount = 0;
            $recentOffers = collect();
            $offers = collect();
            $recentApplicants = collect();
            $applicants = collect();
            $categories = collect();
            $locations = collect();
            $schedules = collect();
            $contracts = collect();
        }

        try {
            $config = DB::table('system_configuration')->pluck('value', 'key')->all();
        } catch (\Exception $e) {
            $config = [];
        }

        return view('company.dashboard', compact(
            'company',
            'offersCount',
            'activeOffersCount',
            'applicantsCount',
            'pendingApplicantsCount',
            'recentOffers',
            'offers',
            'recentApplicants',
            'applicants',
            'categories',
            'locations',
            'schedules',
            'contracts',
            'config'
        ));
    }

    /**
     * Update the company profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una empresa asociada a este usuario.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'mailbox' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'El nombre de la empresa es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'phone.required' => 'El teléfono de contacto es obligatorio.',
            'address.required' => 'La dirección física es obligatoria.',
            'mailbox.email' => 'El buzón de correo debe tener un formato de email válido.',
            'logo.image' => 'El archivo del logo debe ser una imagen.',
            'logo.mimes' => 'El logo debe estar en formato jpeg, png, jpg o gif.',
            'logo.max' => 'El logo no debe pesar más de 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $company->name = $request->name;
            $company->email = $request->email;
            $company->phone = $request->phone;
            $company->address = $request->address;
            $company->mailbox = $request->mailbox ?? $request->email;
            $company->website = $request->website;
            $company->description = $request->description;

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                
                // Ensure upload directory exists
                $uploadPath = public_path('uploads/logos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $file->move($uploadPath, $fileName);
                $company->logo = '/uploads/logos/' . $fileName;
            }

            $company->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Perfil de la empresa actualizado exitosamente.',
                'company' => $company
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ------------------------------------------------------------------ */
    /* OFFER CRUD – scoped to the authenticated company                     */
    /* ------------------------------------------------------------------ */

    /**
     * List offers for the authenticated company (AJAX).
     */
    public function listOffers(Request $request)
    {
        $user    = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Empresa no encontrada.'], 404);
        }

        try {
            $query = JobOpportunityOffer::with(['company', 'location', 'state', 'category', 'workSchedule', 'contractType'])
                ->where('company_id', $company->id);

            if ($request->filled('search')) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('title', 'like', "%$s%")
                      ->orWhere('description', 'like', "%$s%");
                });
            }

            $sortBy = $request->input('sort_by', 'recent');
            match ($sortBy) {
                'title_asc'   => $query->orderBy('title', 'asc'),
                'title_desc'  => $query->orderBy('title', 'desc'),
                'salary_desc' => $query->orderBy('salary', 'desc'),
                'salary_asc'  => $query->orderBy('salary', 'asc'),
                default       => $query->orderBy('publication_date', 'desc'),
            };

            return response()->json(['success' => true, 'offers' => $query->get()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get metadata (categories, locations, etc.) for the company offer form.
     * Mirrors admin getMetadata but WITHOUT the companies list.
     */
    public function getOfferMeta()
    {
        try {
            return response()->json([
                'success'        => true,
                'categories'     => DB::table('job_opportunity_offer_category')->get(),
                'locations'      => DB::table('job_opportunity_location')->get(),
                'work_schedules' => DB::table('job_opportunity_work_schedules')->get(),
                'contract_types' => DB::table('job_opportunity_contract_types')->get(),
                'states'         => DB::table('job_opportunity_offer_state')->get(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new offer scoped to the authenticated company.
     */
    public function storeOffer(Request $request)
    {
        $user    = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Empresa no encontrada.'], 404);
        }

        if (!$company->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Tu empresa aún no ha sido verificada por el administrador. No tienes permisos para realizar esta acción.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'required|string',
            'benefits'         => 'nullable|string',
            'publication_date' => 'required|date',
            'deadline'         => 'nullable|date',
            'salary'           => 'required|numeric|min:0',
            'salary_currency'  => 'required|string|in:SOLES,DOLARES',
            'address'          => 'required|string|max:255',
            'department'       => 'required|string|max:255',
            'province'         => 'required|string|max:255',
            'location_id'      => 'required|integer',
            'category_id'      => 'required|integer',
            'work_schedule_id' => 'required|integer',
            'contract_type_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            DB::beginTransaction();

            $slug      = Str::slug($request->title);
            $slugCount = JobOpportunityOffer::where('slug', 'like', $slug . '%')->count();
            if ($slugCount > 0) {
                $slug .= '-' . time();
            }

            $offer = new JobOpportunityOffer($request->except('company_id'));
            $offer->company_id = $company->id;   // always force company from auth
            $offer->slug       = $slug;
            $offer->country    = 'Perú';
            $offer->state_id   = 2;              // active by default
            $offer->save();

            DB::table('job_opportunity_offer_state_detail')->insert([
                'offer_id'   => $offer->id,
                'state_id'   => $offer->state_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            // Notify admins about new offer
            try {
                $admins = User::where('rol_id', 1)->get();
                foreach ($admins as $admin) {
                    UserNotification::create([
                        'user_id' => $admin->id,
                        'title' => 'Nueva oferta laboral',
                        'message' => "La empresa {$company->name} ha publicado la oferta: {$offer->title}",
                        'link' => '/admin/dashboard?tab=offers',
                    ]);
                }
            } catch (\Exception $e) {
                logger()->error('Error creating notification for new offer: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => '¡Oferta laboral creada exitosamente!',
                'offer'   => $offer->load(['company', 'location', 'state', 'category', 'workSchedule', 'contractType']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al crear la oferta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show a single offer (must belong to authenticated company).
     */
    public function showOffer($id)
    {
        $user    = Auth::user();
        $company = $user->company;

        $offer = JobOpportunityOffer::with(['company', 'location', 'state', 'category', 'workSchedule', 'contractType'])
            ->where('id', $id)
            ->where('company_id', $company->id)
            ->first();

        if (!$offer) {
            return response()->json(['success' => false, 'message' => 'Oferta no encontrada.'], 404);
        }

        return response()->json(['success' => true, 'offer' => $offer]);
    }

    /**
     * Update an offer (must belong to authenticated company).
     */
    public function updateOffer(Request $request, $id)
    {
        $user    = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Empresa no encontrada.'], 404);
        }

        if (!$company->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Tu empresa aún no ha sido verificada por el administrador. No tienes permisos para realizar esta acción.'
            ], 403);
        }

        $offer = JobOpportunityOffer::where('id', $id)->where('company_id', $company->id)->first();

        if (!$offer) {
            return response()->json(['success' => false, 'message' => 'Oferta no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'requirements'     => 'required|string',
            'benefits'         => 'nullable|string',
            'publication_date' => 'required|date',
            'deadline'         => 'nullable|date',
            'salary'           => 'required|numeric|min:0',
            'salary_currency'  => 'required|string|in:SOLES,DOLARES',
            'address'          => 'required|string|max:255',
            'department'       => 'required|string|max:255',
            'province'         => 'required|string|max:255',
            'location_id'      => 'required|integer',
            'category_id'      => 'required|integer',
            'work_schedule_id' => 'required|integer',
            'contract_type_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            DB::beginTransaction();

            if ($offer->title !== $request->title) {
                $slug      = Str::slug($request->title);
                $slugCount = JobOpportunityOffer::where('slug', 'like', $slug . '%')->where('id', '!=', $offer->id)->count();
                if ($slugCount > 0) {
                    $slug .= '-' . time();
                }
                $offer->slug = $slug;
            }

            $offer->fill($request->except(['company_id', 'state_id'])); // never allow company override
            $offer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '¡Oferta actualizada exitosamente!',
                'offer'   => $offer->load(['company', 'location', 'state', 'category', 'workSchedule', 'contractType']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al actualizar la oferta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Toggle offer state (active ↔ finished) for the authenticated company.
     */
    public function toggleOfferState(Request $request, $id)
    {
        $user    = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Empresa no encontrada.'], 404);
        }

        if (!$company->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Tu empresa aún no ha sido verificada por el administrador. No tienes permisos para realizar esta acción.'
            ], 403);
        }

        $offer = JobOpportunityOffer::where('id', $id)->where('company_id', $company->id)->first();

        if (!$offer) {
            return response()->json(['success' => false, 'message' => 'Oferta no encontrada.'], 404);
        }

        try {
            DB::beginTransaction();

            // Toggle: active(2) → finished(3), anything else → active(2)
            $newState       = ($offer->state_id === 2) ? 3 : 2;
            $offer->state_id = $newState;
            $offer->save();

            DB::table('job_opportunity_offer_state_detail')->insert([
                'offer_id'   => $offer->id,
                'state_id'   => $newState,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado de la oferta actualizado.',
                'offer'   => $offer->load(['state']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete an offer (must belong to authenticated company).
     */
    public function destroyOffer($id)
    {
        $user    = Auth::user();
        $company = $user->company;

        $offer = JobOpportunityOffer::where('id', $id)->where('company_id', $company->id)->first();

        if (!$offer) {
            return response()->json(['success' => false, 'message' => 'Oferta no encontrada.'], 404);
        }

        try {
            $offer->delete();
            return response()->json(['success' => true, 'message' => 'Oferta eliminada exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update application status (accepted / rejected).
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        $user    = Auth::user();
        $company = $user->company;

        // Verify the application belongs to an offer of this company
        $application = DB::table('job_opportunity_applications')
            ->join('job_opportunity_offer', 'job_opportunity_applications.offer_id', '=', 'job_opportunity_offer.id')
            ->where('job_opportunity_applications.id', $id)
            ->where('job_opportunity_offer.company_id', $company->id)
            ->whereNull('job_opportunity_applications.deleted_at')
            ->select('job_opportunity_applications.*')
            ->first();

        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Postulación no encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status'   => 'required|string|in:accepted,rejected,postulated',
            'feedback' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $previousStatus = $application->status;

            DB::table('job_opportunity_applications')
                ->where('id', $id)
                ->update([
                    'status'     => $request->status,
                    'feedback'   => $request->feedback,
                    'updated_at' => now(),
                ]);

            // Send email notification when application is approved (accepted)
            if ($request->status === 'accepted' && $previousStatus !== 'accepted') {
                $appModel = \App\Models\JobOpportunityApplication::with(['offer.company', 'user.person'])
                    ->find($id);
                if ($appModel && $appModel->user && $appModel->user->email) {
                    try {
                        Mail::to($appModel->user->email)
                            ->send(new ApplicationApprovedMail($appModel));
                    } catch (\Exception $mailEx) {
                        logger()->error('Error sending approval email from company: ' . $mailEx->getMessage());
                    }
                }
            }

            // Notify student about status change
            if ($application->user_id) {
                try {
                    $statusText = match($request->status) {
                        'accepted' => 'aceptada',
                        'rejected' => 'rechazada',
                        default => 'actualizada',
                    };
                    UserNotification::create([
                        'user_id' => $application->user_id,
                        'title' => 'Estado de postulación actualizado',
                        'message' => "Tu postulación ha sido {$statusText} por la empresa.",
                        'link' => '/student/dashboard?tab=applications',
                    ]);
                } catch (\Exception $e) {
                    logger()->error('Error creating notification for application status change: ' . $e->getMessage());
                }
            }

            return response()->json(['success' => true, 'message' => 'Estado del postulante actualizado.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Add custom metadata item.
     */
    public function addOfferMetaItem(Request $request)
    {
        $user    = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Empresa no encontrada.'], 404);
        }

        if (!$company->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Tu empresa aún no ha sido verificada por el administrador. No tienes permisos para realizar esta acción.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:contract_type,work_schedule,location,category',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $type = $request->type;
        $name = $request->name;
        $item = null;

        try {
            switch ($type) {
                case 'contract_type':
                    $item = \App\Models\JobOpportunityContractType::create(['name' => $name]);
                    break;
                case 'work_schedule':
                    $item = \App\Models\JobOpportunityWorkSchedule::create(['name' => $name]);
                    break;
                case 'location':
                    $item = \App\Models\JobOpportunityLocation::create(['name' => $name]);
                    break;
                case 'category':
                    $item = \App\Models\JobOpportunityOfferCategory::create(['name' => $name]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Opción agregada exitosamente.',
                'item' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar opción: ' . $e->getMessage()
            ], 500);
        }
    }
}
