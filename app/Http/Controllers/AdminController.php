<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use App\Models\Company;
use App\Models\StudyProgram;
use App\Models\JobOpportunityOffer;
use App\Models\JobOpportunityApplication;
use Illuminate\Http\Request;
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

            // Monthly stats for charts (last 6 months)
            $monthlyStats = collect();
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthlyStats->push([
                    'label' => $date->locale('es')->isoFormat('MMM'),
                    'users' => User::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)->count(),
                    'offers' => JobOpportunityOffer::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)->count(),
                    'applications' => JobOpportunityApplication::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)->count(),
                ]);
            }

            // Role distribution for doughnut chart
            $roleDistribution = User::select('rol_id', DB::raw('count(*) as total'))
                ->groupBy('rol_id')
                ->get()
                ->map(function ($item) {
                    $labels = [1 => 'Administradores', 2 => 'Docentes', 3 => 'Estudiantes', 4 => 'Empresas'];
                    $colors = [1 => '#002741', 2 => '#006b60', 3 => '#ff9f43', 4 => '#18A999'];
                    return [
                        'label' => $labels[$item->rol_id] ?? 'Otro',
                        'total' => $item->total,
                        'color' => $colors[$item->rol_id] ?? '#94a3b8',
                    ];
                });

            // Top 5 companies by offer count
            $topCompanies = JobOpportunityOffer::with('company')
                ->select('company_id', DB::raw('count(*) as total'))
                ->groupBy('company_id')
                ->orderByDesc('total')
                ->take(5)
                ->get()
                ->filter(fn($item) => $item->company)
                ->values()
                ->map(function ($item) {
                    return [
                        'name' => $item->company->name,
                        'total' => $item->total,
                    ];
                });

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
                        'time' => $user->created_at ? $user->created_at->locale('es')->diffForHumans() : '-',
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
                        'time' => $company->created_at ? $company->created_at->locale('es')->diffForHumans() : '-',
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
                        'time' => $offer->created_at ? $offer->created_at->locale('es')->diffForHumans() : '-',
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

            // Cargar programas de estudio para el select de asignación
            $studyPrograms = StudyProgram::orderBy('name')->get();

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
                'currentStatus',
                'studyPrograms',
                'monthlyStats',
                'roleDistribution',
                'topCompanies'
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
            $studyPrograms = collect();
            $userGrowth = 0;
            $appGrowth = 0;
            $currentSearch = '';
            $currentRolId = '';
            $currentStatus = '';
            $monthlyStats = collect();
            $roleDistribution = collect();
            $topCompanies = collect();

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
                'currentStatus',
                'studyPrograms',
                'monthlyStats',
                'roleDistribution',
                'topCompanies'
            ));
        }
    }

    /**
     * AJAX endpoint: filter users and return JSON with paginated results.
     */
    public function filterUsers()
    {
        $usersQuery = User::with(['person', 'company'])->latest();

        $search = request()->input('search', '');
        $rolId = request()->input('rol_id', '');
        $status = request()->input('status', '');
        $page = request()->input('page', 1);

        if ($search) {
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

        if ($rolId !== '') {
            $usersQuery->where('rol_id', $rolId);
        }

        if ($status !== '') {
            if ($status === 'active') {
                $usersQuery->where('is_active', true);
            } elseif ($status === 'inactive') {
                $usersQuery->where('is_active', false);
            }
        }

        $users = $usersQuery->paginate(10)->withQueryString();

        $usersData = $users->getCollection()->map(function ($user) {
            $name = '';
            if ($user->person) {
                $name = $user->person->names ?? '';
            } elseif ($user->company) {
                $name = $user->company->name ?? '';
            }
            return [
                'id' => $user->id,
                'name' => $name,
                'email' => $user->email ?? '',
                'phone' => $user->person->phone ?? '',
                'doc_type' => $user->person->document_type ?? 'Cédula',
                'doc_number' => $user->person->document_number ?? '',
                'rol_id' => $user->rol_id,
                'is_active' => (bool) $user->is_active,
            ];
        });

        return response()->json([
            'users' => $usersData,
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
            'filters' => [
                'search' => $search,
                'rol_id' => $rolId,
                'status' => $status,
            ],
        ]);
    }

    /**
     * Search users that have an associated person (students/teachers) for study program assignment.
     */
    public function searchPersonUsers(Request $request)
    {
        $query = $request->input('q', '');

        $users = User::with(['person.studyProgram'])
            ->whereNotNull('person_id')
            ->where('rol_id', 3)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('email', 'like', "%{$query}%")
                        ->orWhereHas('person', fn($p) => $p->where('names', 'like', "%{$query}%"));
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($user) {
                return [
                    'user_id'          => $user->id,
                    'person_id'        => $user->person_id,
                    'name'             => $user->person->names ?? '-',
                    'email'            => $user->email,
                    'rol_id'           => $user->rol_id,
                    'study_program_id' => $user->person->study_program_id,
                    'study_program'    => $user->person->studyProgram->name ?? null,
                ];
            });

        return response()->json(['success' => true, 'users' => $users]);
    }

    /**
     * Assign a study program to a person.
     */
    public function assignStudyProgram(Request $request)
    {
        $request->validate([
            'person_id'        => 'required|integer|exists:person,id',
            'study_program_id' => 'nullable|integer|exists:study_programs,id',
        ]);

        try {
            Person::where('id', $request->person_id)
                ->update(['study_program_id' => $request->study_program_id ?: null]);

            $program = $request->study_program_id
                ? StudyProgram::find($request->study_program_id)->name
                : 'Sin asignar';

            return response()->json([
                'success' => true,
                'message' => "Programa de estudio actualizado a: {$program}",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar programa: ' . $e->getMessage(),
            ], 500);
        }
    }
}
