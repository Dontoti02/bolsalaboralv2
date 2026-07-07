<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobOpportunityOffer;
use App\Models\JobOpportunityOfferCategory;
use App\Models\JobOpportunityLocation;
use App\Models\JobOpportunityWorkSchedule;
use App\Models\JobOpportunityContractType;
use Illuminate\Http\Request;
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

        } catch (\Exception $e) {
            $totalActiveOffers = 0;
            $totalCompanies    = 0;
            $featuredOffers    = collect();
            $companies         = collect();
            $categories        = collect();
            $locations         = collect();
            $workSchedules     = collect();
            $contractTypes     = collect();
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
            'contractTypes'
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
}
