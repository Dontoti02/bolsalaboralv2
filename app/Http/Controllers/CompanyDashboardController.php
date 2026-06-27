<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobOpportunityOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

                $recentApplicants = DB::table('job_opportunity_applications')
                    ->whereIn('offer_id', $offerIds)
                    ->whereNull('job_opportunity_applications.deleted_at')
                    ->join('job_opportunity_offer', 'job_opportunity_applications.offer_id', '=', 'job_opportunity_offer.id')
                    ->select('job_opportunity_applications.*', 'job_opportunity_offer.title as offer_title')
                    ->orderBy('job_opportunity_applications.created_at', 'desc')
                    ->take(5)
                    ->get();
            }

            // Recent Offers
            $recentOffers = JobOpportunityOffer::where('company_id', $company->id)
                ->with(['state'])
                ->latest('publication_date')
                ->take(5)
                ->get();

            foreach ($recentOffers as $offer) {
                $offer->applicants_count = DB::table('job_opportunity_applications')
                    ->where('offer_id', $offer->id)
                    ->whereNull('deleted_at')
                    ->count();
            }
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
            $recentApplicants = collect();
        }

        $config = DB::table('system_configuration')->pluck('value', 'key')->all();

        return view('company.dashboard', compact(
            'company',
            'offersCount',
            'activeOffersCount',
            'applicantsCount',
            'pendingApplicantsCount',
            'recentOffers',
            'recentApplicants',
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
}
