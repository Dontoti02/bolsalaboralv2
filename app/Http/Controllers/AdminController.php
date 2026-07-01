<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\JobOpportunityOffer;
use App\Models\JobOpportunityApplication;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with real data from the database.
     */
    public function dashboard()
    {
        try {
            // Total users count
            $totalUsers = User::count();

            // Pending companies (not verified)
            $pendingCompanies = Company::where('is_verified', false)->count();

            // Active offers count
            $activeOffers = JobOpportunityOffer::count();

            // Total applications count
            $totalApplications = JobOpportunityApplication::count();

            // Recent companies (last 5)
            $recentCompanies = Company::orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($company) {
                    $company->formatted_date = $company->created_at ? $company->created_at->format('d M Y') : '-';
                    $company->status_label = $company->is_verified ? 'Aprobado' : 'Pendiente';
                    $company->status_class = $company->is_verified
                        ? 'bg-secondary-fixed/20 text-on-secondary-container'
                        : 'bg-tertiary-fixed text-on-tertiary-fixed-variant';
                    $company->initial = strtoupper(substr($company->name, 0, 1));
                    return $company;
                });

            // Recent activity log
            $recentActivity = collect();

            // New users registered (last 5)
            $recentUsers = User::with('person')
                ->whereNotNull('person_id')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($user) {
                    $name = e($user->person->names ?? 'Usuario');
                    return [
                        'icon' => 'person_add',
                        'icon_bg' => 'bg-primary-fixed',
                        'icon_color' => 'text-primary',
                        'text' => "Nuevo usuario <span class=\"font-semibold\">{$name}</span> registrado en el sistema.",
                        'time' => $user->created_at ? $user->created_at->diffForHumans() : '-',
                    ];
                });

            // Recent companies created (last 3)
            $recentCompaniesActivity = Company::orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($company) {
                    $companyName = e($company->name);
                    $action = $company->is_verified ? 'registrada y aprobada' : 'registrada';
                    return [
                        'icon' => 'domain_verification',
                        'icon_bg' => 'bg-secondary-fixed',
                        'icon_color' => 'text-on-secondary-fixed',
                        'text' => "Empresa <span class=\"font-semibold\">{$companyName}</span> {$action} en el sistema.",
                        'time' => $company->created_at ? $company->created_at->diffForHumans() : '-',
                    ];
                });

            // Recent offers created (last 3)
            $recentOffersActivity = JobOpportunityOffer::with('company')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get()
                ->map(function ($offer) {
                    $companyName = e($offer->company->name ?? 'Desconocida');
                    $offerTitle = e($offer->title);
                    return [
                        'icon' => 'work',
                        'icon_bg' => 'bg-tertiary-fixed',
                        'icon_color' => 'text-on-tertiary-fixed-variant',
                        'text' => "Nueva oferta <span class=\"font-semibold\">{$offerTitle}</span> publicada por {$companyName}.",
                        'time' => $offer->created_at ? $offer->created_at->diffForHumans() : '-',
                    ];
                });

            // Merge and sort all activities by time
            $recentActivity = $recentUsers
                ->concat($recentCompaniesActivity)
                ->concat($recentOffersActivity)
                ->sortByDesc(function ($item) {
                    // Simple sort - recent items first
                    return $item['time'];
                })
                ->take(5)
                ->values();

            // Get system configuration
            $config = DB::table('system_configuration')->pluck('value', 'key')->all();

            // Get all users for the users table with filters and pagination
            $usersQuery = User::with(['person', 'company'])->latest();

            // Current filter values for preserving state in the view
            $currentSearch = request()->input('search', '');
            $currentRolId = request()->input('rol_id', '');
            $currentStatus = request()->input('status', '');

            // Filtro de búsqueda por nombre, email o documento
            if (request()->filled('search')) {
                $search = request()->input('search');
                $usersQuery->where(function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                        ->orWhereHas('person', function ($pq) use ($search) {
                            $pq->where('names', 'like', "%{$search}%")
                                ->orWhere('document_number', 'like', "%{$search}%");
                        })
                        ->orWhereHas('company', function ($cq) use ($search) {
                            $cq->where('name', 'like', "%{$search}%")
                                ->orWhere('ruc', 'like', "%{$search}%");
                        });
                });
            }

            // Filtro por rol
            if (request()->filled('rol_id')) {
                $usersQuery->where('rol_id', request()->input('rol_id'));
            }

            // Filtro por estado (is_active)
            if (request()->filled('status')) {
                $statusVal = request()->input('status');
                if ($statusVal === 'active') {
                    $usersQuery->where('is_active', true);
                } elseif ($statusVal === 'inactive') {
                    $usersQuery->where('is_active', false);
                }
            }

            $users = $usersQuery->paginate(10)->withQueryString();

            // Calculate percentage changes (comparing with last month data)
            $lastMonthUsers = User::where('created_at', '<', now()->subMonth())->count();
            $userGrowth = $lastMonthUsers > 0 ? round((($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1) : 0;

            $lastMonthApplications = JobOpportunityApplication::where('created_at', '<', now()->subMonth())->count();
            $appGrowth = $lastMonthApplications > 0 ? round((($totalApplications - $lastMonthApplications) / $lastMonthApplications) * 100, 1) : 0;

            return view('admin.dashboard', compact(
                'totalUsers',
                'pendingCompanies',
                'activeOffers',
                'totalApplications',
                'recentCompanies',
                'recentActivity',
                'config',
                'users',
                'userGrowth',
                'appGrowth',
                'currentSearch',
                'currentRolId',
                'currentStatus'
            ));

        } catch (\Exception $e) {
            // Fallback with empty data
            $totalUsers = 0;
            $pendingCompanies = 0;
            $activeOffers = 0;
            $totalApplications = 0;
            $recentCompanies = collect();
            $recentActivity = collect();
            $config = [];
            $users = collect();
            $userGrowth = 0;
            $appGrowth = 0;
            $currentSearch = '';
            $currentRolId = '';
            $currentStatus = '';

            return view('admin.dashboard', compact(
                'totalUsers',
                'pendingCompanies',
                'activeOffers',
                'totalApplications',
                'recentCompanies',
                'recentActivity',
                'config',
                'users',
                'userGrowth',
                'appGrowth',
                'currentSearch',
                'currentRolId',
                'currentStatus'
            ));
        }
    }
}
