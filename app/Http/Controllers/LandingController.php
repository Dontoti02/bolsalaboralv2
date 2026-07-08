<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobOpportunityOffer;
use App\Models\JobOpportunityOfferCategory;
use App\Models\JobOpportunityLocation;
use App\Models\JobOpportunityWorkSchedule;
use App\Models\JobOpportunityContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    /**
     * Show the public landing page.
     */
    public function index()
    {
        try {
            $config = DB::table('system_configuration')->pluck('value', 'key')->all();
        } catch (\Exception $e) {
            $config = [];
        }

        try {
            // Stats para el hero
            $totalActiveOffers = JobOpportunityOffer::whereHas('state', fn($q) => $q->where('key', 'active'))->count();
            $totalCompanies    = Company::where('is_verified', true)->where(function($q){ $q->whereNull('deleted_at'); })->count();

            // Ofertas destacadas (últimas activas)
            $featuredOffers = JobOpportunityOffer::with(['company:id,name,logo', 'state', 'category', 'location', 'workSchedule', 'contractType'])
                ->whereHas('state', fn($q) => $q->where('key', 'active'))
                ->orderBy('publication_date', 'desc')
                ->take(9)
                ->get();

            // Buscar oferta compartida por URL
            $sharedOffer = null;
            if (request()->has('offer')) {
                $sharedOffer = JobOpportunityOffer::with(['company:id,name,logo', 'state', 'category', 'location', 'workSchedule', 'contractType'])
                    ->where('id', request()->offer)
                    ->first();
            }

            // Empresas verificadas con al menos 1 oferta activa
            $companies = Company::where('is_verified', true)
                ->where(function($q) {
                    $q->whereNull('deleted_at');
                })
                ->withCount(['offers as active_offers_count' => function ($q) {
                    $q->whereHas('state', fn($s) => $s->where('key', 'active'));
                }])
                ->having('active_offers_count', '>', 0)
                ->orderByDesc('active_offers_count')
                ->take(12)
                ->get();

            // Metadata para filtros
            $categories     = JobOpportunityOfferCategory::all();
            $locations      = JobOpportunityLocation::all();
            $workSchedules  = JobOpportunityWorkSchedule::all();
            $contractTypes  = JobOpportunityContractType::all();

            // Departamentos disponibles (donde hay ofertas activas)
            $availablePlaces = DB::table('job_opportunity_offer')
                ->whereNull('deleted_at')
                ->where('state_id', function($sq) {
                    $sq->select('id')->from('job_opportunity_offer_state')
                       ->where('key', 'active')->limit(1);
                })
                ->whereNotNull('department')
                ->where('department', '!=', '')
                ->pluck('department')
                ->unique()
                ->sort()
                ->values();

            // Cargos disponibles (títulos de ofertas activas para sugerencias)
            $availableTitles = JobOpportunityOffer::whereHas('state', fn($q) => $q->where('key', 'active'))
                ->pluck('title')
                ->unique()
                ->values();

            // Empresas disponibles (con ofertas activas para sugerencias)
            $availableCompanies = Company::where('is_verified', true)
                ->where(function($q) { $q->whereNull('deleted_at'); })
                ->whereHas('offers', fn($o) => $o->whereHas('state', fn($s) => $s->where('key', 'active')))
                ->pluck('name')
                ->unique()
                ->sort()
                ->values();

        // Datos del estudiante autenticado
            $authUser = null;
            $studentCvs = collect();
            $studentApplicationIds = [];
            $studentApplications = collect();

            if (Auth::check() && (Auth::user()->rol_id == 3 || Auth::user()->rol_id == 2)) {
                $authUser = Auth::user()->load('person');
                
                if ($authUser->rol_id == 3) {
                    $studentCvs = DB::table('job_opportunity_user_cv')
                        ->where('user_id', $authUser->id)
                        ->whereNull('deleted_at')
                        ->orderBy('version', 'desc')
                        ->get()
                        ->map(function ($cv) {
                            $cv->filename = basename($cv->url);
                            $cv->uploaded_at = $cv->created_at
                                ? \Carbon\Carbon::parse($cv->created_at)->format('d M Y') : '-';
                            return $cv;
                        });
                    $studentApplicationIds = DB::table('job_opportunity_applications')
                        ->where('user_id', $authUser->id)
                        ->pluck('offer_id')
                        ->toArray();

                    $studentApplications = DB::table('job_opportunity_applications')
                        ->join('job_opportunity_offer', 'job_opportunity_applications.offer_id', '=', 'job_opportunity_offer.id')
                        ->join('job_opportunity_company', 'job_opportunity_offer.company_id', '=', 'job_opportunity_company.id')
                        ->where('job_opportunity_applications.user_id', $authUser->id)
                        ->whereNull('job_opportunity_applications.deleted_at')
                        ->select(
                            'job_opportunity_applications.id as app_id',
                            'job_opportunity_applications.status as app_status',
                            'job_opportunity_applications.created_at as app_date',
                            'job_opportunity_applications.feedback as app_feedback',
                            'job_opportunity_offer.title as offer_title',
                            'job_opportunity_company.name as company_name',
                            'job_opportunity_company.logo as company_logo'
                        )
                        ->orderBy('job_opportunity_applications.created_at', 'desc')
                        ->get()
                        ->map(function ($app) {
                            $app->formatted_date = $app->app_date 
                                ? \Carbon\Carbon::parse($app->app_date)->format('d M Y') : '-';
                            return $app;
                        });
                }
            }

        } catch (\Exception $e) {
            $totalActiveOffers = 0;
            $totalCompanies    = 0;
            $featuredOffers    = collect();
            $companies         = collect();
            $categories        = collect();
            $locations         = collect();
            $workSchedules     = collect();
            $contractTypes     = collect();
            $availablePlaces   = collect();
            $authUser          = null;
            $studentCvs        = collect();
            $studentApplicationIds = [];
            $studentApplications = collect();
            $availableTitles   = collect();
            $availableCompanies = collect();
            $sharedOffer       = null;
        }

        return view('landing', compact(
            'config',
            'totalActiveOffers',
            'totalCompanies',
            'featuredOffers',
            'companies',
            'categories',
            'locations',
            'workSchedules',
            'contractTypes',
            'availablePlaces',
            'availableTitles',
            'availableCompanies',
            'sharedOffer',
            'authUser',
            'studentCvs',
            'studentApplicationIds',
            'studentApplications'
        ));
    }

    /**
     * Public API: search job offers (JSON).
     */
    public function searchOffers(Request $request)
    {
        try {
            $query = JobOpportunityOffer::with(['company:id,name,logo', 'state', 'category', 'location', 'workSchedule', 'contractType'])
                ->whereHas('state', fn($q) => $q->where('key', 'active'));

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('company', fn($c) => $c->where('name', 'like', "%{$search}%"));
                });
            }

            // Filtro por lugar (departamento o provincia)
            if ($request->filled('province')) {
                $place = $request->province;
                $query->where(function ($q) use ($place) {
                    $q->where('province',   'like', "%{$place}%")
                      ->orWhere('department', 'like', "%{$place}%");
                });
            }

            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            if ($request->filled('location_id')) {
                $query->where('location_id', $request->location_id);
            }
            if ($request->filled('work_schedule_id')) {
                $query->where('work_schedule_id', $request->work_schedule_id);
            }
            if ($request->filled('contract_type_id')) {
                $query->where('contract_type_id', $request->contract_type_id);
            }
            if ($request->filled('salary_filter')) {
                switch ($request->salary_filter) {
                    case 'under_1000':   $query->where('salary', '<', 1000);  break;
                    case '1000_to_2000': $query->whereBetween('salary', [1000, 2000]); break;
                    case '2000_to_4000': $query->whereBetween('salary', [2000, 4000]); break;
                    case 'above_4000':   $query->where('salary', '>', 4000);  break;
                }
            }

            $sortBy = $request->input('sort_by', 'recent');
            match ($sortBy) {
                'title_asc'    => $query->orderBy('title', 'asc'),
                'title_desc'   => $query->orderBy('title', 'desc'),
                'salary_desc'  => $query->orderBy('salary', 'desc'),
                'salary_asc'   => $query->orderBy('salary', 'asc'),
                default        => $query->orderBy('publication_date', 'desc'),
            };

            $perPage = (int) $request->input('per_page', 9);
            $offers  = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'offers'  => $offers->items(),
                'total'   => $offers->total(),
                'has_more'=> $offers->hasMorePages(),
                'current_page' => $offers->currentPage(),
                'last_page'    => $offers->lastPage(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Clear password warning from session.
     */
    public function clearPasswordWarning()
    {
        session()->forget('show_password_warning');
        return response()->json(['success' => true]);
    }
}

