<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudyProgram;
use App\Models\JobOpportunityApplication;
use App\Exports\BolsaLaboralReportExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Build base query for reports
     */
    private function buildQuery(Request $request)
    {
        $type = $request->get('type', 'all');
        $search = trim($request->get('search', ''));
        $programId = $request->get('program_id');
        $status = $request->get('status');

        $query = User::with([
            'person.studyProgram',
            'applications.offer.company'
        ]);

        if ($type === 'graduate') {
            $query->where('rol_id', 5);
        } elseif ($type === 'student') {
            $query->where('rol_id', 3);
        } else {
            // 'all': Ambos roles institucionales (Estudiantes y Egresados)
            $query->whereIn('rol_id', [3, 5]);
        }

        // Search filter
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereHas('person', function ($pq) use ($search) {
                      $pq->where('names', 'like', "%{$search}%")
                         ->orWhere('document_number', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('career', 'like', "%{$search}%");
                  });
            });
        }

        // Study Program filter
        if (!empty($programId)) {
            $query->whereHas('person', function ($pq) use ($programId) {
                $pq->where('study_program_id', $programId);
            });
        }

        // Application status filter
        if (!empty($status) && $status !== 'all') {
            if ($status === 'has_applications') {
                $query->has('applications');
            } elseif ($status === 'no_applications') {
                $query->doesntHave('applications');
            } elseif (in_array($status, ['accepted', 'under_review', 'postulated', 'rejected'])) {
                $query->whereHas('applications', function ($aq) use ($status) {
                    $aq->where('status', $status);
                });
            }
        }

        return $query;
    }

    /**
     * Transform a user collection/item into formatted report payload
     */
    private function formatUserData($users)
    {
        return $users->map(function ($user) {
            $person = $user->person;
            $studyProgram = $person?->studyProgram?->name ?? ($person?->career ?: 'Sin asignar');

            $apps = $user->applications->map(function ($app) {
                return [
                    'id' => $app->id,
                    'offer_id' => $app->offer_id,
                    'offer_title' => $app->offer?->title ?? 'Oferta no disponible',
                    'company_name' => $app->offer?->company?->name ?? 'Empresa no disponible',
                    'status' => $app->status,
                    'status_label' => match($app->status) {
                        'accepted' => 'Aceptado',
                        'under_review' => 'En revisión',
                        'rejected' => 'Rechazado',
                        default => 'Postulado',
                    },
                    'created_at' => $app->created_at ? $app->created_at->format('d/m/Y H:i') : '-',
                    'feedback' => $app->feedback,
                    'cv_url' => $app->cv ? route('applications.cv.download', $app->id) : null,
                ];
            });

            return [
                'id' => $user->id,
                'rol_id' => (int) $user->rol_id,
                'role_name' => (int) $user->rol_id === 5 ? 'Egresado' : 'Estudiante',
                'email' => $user->email,
                'is_active' => (bool) $user->is_active,
                'created_at' => $user->created_at ? $user->created_at->format('d/m/Y') : '-',
                'person' => [
                    'id' => $person?->id,
                    'document_number' => $person?->document_number ?? 'S/DNI',
                    'names' => $person?->names ?? 'Sin nombre',
                    'phone' => $person?->phone ?? 'S/Telf',
                    'study_program' => $studyProgram,
                    'study_program_id' => $person?->study_program_id,
                ],
                'applications_count' => $apps->count(),
                'applications' => $apps,
            ];
        });
    }

    /**
     * Get JSON data for Reports panel (AJAX)
     */
    public function getReportsData(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $perPage = (int) $request->get('per_page', 15);

            $query = $this->buildQuery($request);
            $paginated = $query->latest('id')->paginate($perPage);

            $items = $this->formatUserData(collect($paginated->items()));

            // Overall Summary KPI Statistics
            $studentUserIds = User::where('rol_id', 3)->pluck('id');
            $graduateUserIds = User::where('rol_id', 5)->pluck('id');
            $allUserIds = $studentUserIds->concat($graduateUserIds);

            $summary = [
                'total_all' => $allUserIds->count(),
                'all_with_apps' => JobOpportunityApplication::whereIn('user_id', $allUserIds)->distinct('user_id')->count('user_id'),
                'total_students' => $studentUserIds->count(),
                'students_with_apps' => JobOpportunityApplication::whereIn('user_id', $studentUserIds)->distinct('user_id')->count('user_id'),
                'total_graduates' => $graduateUserIds->count(),
                'graduates_with_apps' => JobOpportunityApplication::whereIn('user_id', $graduateUserIds)->distinct('user_id')->count('user_id'),
                'total_applications' => JobOpportunityApplication::count(),
                'accepted_applications' => JobOpportunityApplication::where('status', 'accepted')->count(),
                'under_review_applications' => JobOpportunityApplication::where('status', 'under_review')->count(),
                'rejected_applications' => JobOpportunityApplication::where('status', 'rejected')->count(),
            ];

            $studyPrograms = StudyProgram::where('is_active', true)->select('id', 'name')->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'summary' => $summary,
                'items' => $items,
                'study_programs' => $studyPrograms,
                'pagination' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                    'total' => $paginated->total(),
                    'per_page' => $paginated->perPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos de reportes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Reports to Excel (.xlsx)
     */
    public function exportExcel(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $query = $this->buildQuery($request);
            
            $users = $query->latest('id')->get();
            $formattedUsers = $this->formatUserData($users)->toArray();

            $dateStr = date('Y-m-d_His');
            $prefix = match($type) {
                'graduate' => 'reporte_egresados_bolsa_laboral',
                'student'  => 'reporte_estudiantes_bolsa_laboral',
                default    => 'reporte_estudiantes_egresados_bolsa_laboral',
            };
            $fileName = "{$prefix}_{$dateStr}.xlsx";

            return Excel::download(new BolsaLaboralReportExport($formattedUsers, $type), $fileName);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar reporte Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Reports to PDF (.pdf)
     */
    public function exportPdf(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $query = $this->buildQuery($request);

            $users = $query->latest('id')->get();
            $formattedUsers = $this->formatUserData($users)->toArray();

            $typeLabel = match($type) {
                'graduate' => 'Egresados',
                'student'  => 'Estudiantes',
                default    => 'Estudiantes y Egresados',
            };

            $config = DB::table('system_configuration')->pluck('value', 'key')->all();
            $institutionName = $config['institution_name'] ?? 'Bolsa Laboral Institucional';

            $programName = 'Todos los programas';
            if ($request->filled('program_id')) {
                $p = StudyProgram::find($request->get('program_id'));
                if ($p) $programName = $p->name;
            }

            $statusLabel = match($request->get('status', 'all')) {
                'has_applications' => 'Con postulaciones activas',
                'no_applications'  => 'Sin postulaciones',
                'accepted'         => 'Aceptados en convocatoria',
                'under_review'     => 'En revisión',
                'rejected'         => 'Rechazados',
                default            => 'Todos los estados',
            };

            $data = [
                'users' => $formattedUsers,
                'type' => $type,
                'typeLabel' => $typeLabel,
                'institutionName' => $institutionName,
                'programName' => $programName,
                'statusLabel' => $statusLabel,
                'search' => $request->get('search', ''),
                'date' => date('d/m/Y H:i:s'),
                'totalEvaluated' => count($formattedUsers),
                'totalWithApps' => collect($formattedUsers)->where('applications_count', '>', 0)->count(),
                'totalAccepted' => collect($formattedUsers)->sum(function($u) {
                    return collect($u['applications'])->where('status', 'accepted')->count();
                }),
            ];

            $dateStr = date('Y-m-d_His');
            $prefix = match($type) {
                'graduate' => 'reporte_egresados_bolsa_laboral',
                'student'  => 'reporte_estudiantes_bolsa_laboral',
                default    => 'reporte_estudiantes_egresados_bolsa_laboral',
            };
            $fileName = "{$prefix}_{$dateStr}.pdf";

            $pdf = Pdf::loadView('reports.pdf', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', true);

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar reporte PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
