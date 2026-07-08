<?php

namespace App\Http\Controllers;

use App\Models\JobOpportunityOffer;
use App\Models\JobOpportunityContractType;
use App\Models\JobOpportunityWorkSchedule;
use App\Models\JobOpportunityLocation;
use App\Models\JobOpportunityOfferCategory;
use App\Models\JobOpportunityOfferState;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JobOpportunityController extends Controller
{
    /**
     * Display a listing of job offers with filters.
     */
    public function index(Request $request)
    {
        try {
            $query = JobOpportunityOffer::with(['company', 'location', 'state', 'category', 'workSchedule', 'contractType']);

            // Search query (title, description, requirements)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('requirements', 'like', "%{$search}%");
                });
            }

            // Category filter
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Work schedule / Workday filter
            if ($request->filled('work_schedule_id')) {
                $query->where('work_schedule_id', $request->work_schedule_id);
            }

            // Location / Work modality filter
            if ($request->filled('location_id')) {
                $query->where('location_id', $request->location_id);
            }

            // Contract type filter
            if ($request->filled('contract_type_id')) {
                $query->where('contract_type_id', $request->contract_type_id);
            }

            // Company filter
            if ($request->filled('company_id')) {
                $query->where('company_id', $request->company_id);
            }

            // Salary Filter
            if ($request->filled('salary_filter')) {
                switch ($request->salary_filter) {
                    case 'under_1000':
                        $query->where('salary', '<', 1000);
                        break;
                    case '1000_to_2000':
                        $query->whereBetween('salary', [1000, 2000]);
                        break;
                    case '2000_to_4000':
                        $query->whereBetween('salary', [2000, 4000]);
                        break;
                    case 'above_4000':
                        $query->where('salary', '>', 4000);
                        break;
                }
            }

            // Date Filter
            if ($request->filled('date_filter')) {
                switch ($request->date_filter) {
                    case 'today':
                        $query->whereDate('publication_date', today());
                        break;
                    case 'last_3_days':
                        $query->where('publication_date', '>=', now()->subDays(3));
                        break;
                    case 'last_week':
                        $query->where('publication_date', '>=', now()->subWeek());
                        break;
                    case 'last_month':
                        $query->where('publication_date', '>=', now()->subMonth());
                        break;
                }
            }

            // Sorting
            $sortBy = $request->input('sort_by', 'recent');
            switch ($sortBy) {
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'salary_desc':
                    $query->orderBy('salary', 'desc');
                    break;
                case 'salary_asc':
                    $query->orderBy('salary', 'asc');
                    break;
                case 'recent':
                default:
                    $query->orderBy('publication_date', 'desc');
                    break;
            }

            $offers = $query->get();

            return response()->json([
                'success' => true,
                'offers' => $offers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar ofertas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created job offer.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'publication_date' => 'required|date',
            'deadline' => 'nullable|date',
            'salary' => 'required|numeric|min:0',
            'salary_currency' => 'required|string|in:SOLES,DOLARES',
            'address' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'company_id' => 'required|integer',
            'location_id' => 'required|integer',
            'category_id' => 'required|integer',
            'work_schedule_id' => 'required|integer',
            'contract_type_id' => 'required|integer',
            'state_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $slug = Str::slug($request->title);
            $slugCount = JobOpportunityOffer::where('slug', 'like', $slug . '%')->count();
            if ($slugCount > 0) {
                $slug .= '-' . time();
            }

            $offer = new JobOpportunityOffer($request->all());
            $offer->slug = $slug;
            $offer->country = 'Perú';
            // Default to vigent/active (2) if not specified
            $offer->state_id = $request->input('state_id', 2);
            $offer->save();

            // Record initial state in state detail table
            DB::table('job_opportunity_offer_state_detail')->insert([
                'offer_id' => $offer->id,
                'state_id' => $offer->state_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Oferta laboral creada exitosamente.',
                'offer' => $offer->load(['company', 'location', 'state', 'category', 'workSchedule', 'contractType'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la oferta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show detailed information of a specific job offer.
     */
    public function show($id)
    {
        $offer = JobOpportunityOffer::with(['company', 'location', 'state', 'category', 'workSchedule', 'contractType'])->find($id);

        if (!$offer) {
            return response()->json([
                'success' => false,
                'message' => 'Oferta laboral no encontrada.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'offer' => $offer
        ]);
    }

    /**
     * Update the specified job offer.
     */
    public function update(Request $request, $id)
    {
        $offer = JobOpportunityOffer::find($id);

        if (!$offer) {
            return response()->json([
                'success' => false,
                'message' => 'Oferta laboral no encontrada.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'publication_date' => 'required|date',
            'deadline' => 'nullable|date',
            'salary' => 'required|numeric|min:0',
            'salary_currency' => 'required|string|in:SOLES,DOLARES',
            'address' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'company_id' => 'required|integer',
            'location_id' => 'required|integer',
            'category_id' => 'required|integer',
            'work_schedule_id' => 'required|integer',
            'contract_type_id' => 'required|integer',
            'state_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update slug if title changed
            if ($offer->title !== $request->title) {
                $slug = Str::slug($request->title);
                $slugCount = JobOpportunityOffer::where('slug', 'like', $slug . '%')
                    ->where('id', '!=', $offer->id)
                    ->count();
                if ($slugCount > 0) {
                    $slug .= '-' . time();
                }
                $offer->slug = $slug;
            }

            $offer->fill($request->all());
            $offer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Oferta laboral actualizada exitosamente.',
                'offer' => $offer->load(['company', 'location', 'state', 'category', 'workSchedule', 'contractType'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la oferta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle or update status (state_id) of the job offer.
     */
    public function toggleState(Request $request, $id)
    {
        $offer = JobOpportunityOffer::find($id);

        if (!$offer) {
            return response()->json([
                'success' => false,
                'message' => 'Oferta laboral no encontrada.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'state_id' => 'required|integer|in:1,2,3,4,5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Estado seleccionado no es válido.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $offer->state_id = $request->state_id;
            $offer->save();

            // Record state update history
            DB::table('job_opportunity_offer_state_detail')->insert([
                'offer_id' => $offer->id,
                'state_id' => $request->state_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado de la oferta actualizado exitosamente.',
                'offer' => $offer->load(['company', 'location', 'state', 'category', 'workSchedule', 'contractType'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified job offer (soft delete).
     */
    public function destroy($id)
    {
        $offer = JobOpportunityOffer::find($id);

        if (!$offer) {
            return response()->json([
                'success' => false,
                'message' => 'Oferta laboral no encontrada.'
            ], 404);
        }

        try {
            $offer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Oferta laboral eliminada exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la oferta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove multiple job offers in bulk (soft delete).
     */
    public function bulkDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'IDs de ofertas no válidos.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            JobOpportunityOffer::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ofertas seleccionadas eliminadas exitosamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar ofertas en masa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lookup/metadata for forms and filters.
     */
    public function getMetadata()
    {
        try {
            return response()->json([
                'success' => true,
                'companies' => Company::all(),
                'categories' => JobOpportunityOfferCategory::all(),
                'locations' => JobOpportunityLocation::all(),
                'work_schedules' => JobOpportunityWorkSchedule::all(),
                'contract_types' => JobOpportunityContractType::all(),
                'states' => JobOpportunityOfferState::all(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar metadatos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add lookup item dynamically from form (+ button).
     */
    public function addMetadataItem(Request $request)
    {
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
                    $item = JobOpportunityContractType::create(['name' => $name]);
                    break;
                case 'work_schedule':
                    $item = JobOpportunityWorkSchedule::create(['name' => $name]);
                    break;
                case 'location':
                    $item = JobOpportunityLocation::create(['name' => $name]);
                    break;
                case 'category':
                    $item = JobOpportunityOfferCategory::create(['name' => $name]);
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

    public function updateMetadataItem(Request $request, string $type, int $id)
    {
        $validator = Validator::make(
            ['type' => $type, 'name' => $request->input('name')],
            [
                'type' => 'required|string|in:contract_type,work_schedule,location,category',
                'name' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $modelClass = $this->metadataModelClass($type);
            $item = $modelClass::findOrFail($id);
            $name = trim($request->input('name'));

            $duplicateExists = $modelClass::whereRaw('LOWER(name) = ?', [Str::lower($name)])
                ->where('id', '!=', $id)
                ->exists();

            if ($duplicateExists) {
                return response()->json([
                    'success' => false,
                    'message' => "Ya existe una opci\u{00F3}n con ese nombre."
                ], 422);
            }

            $item->update(['name' => $name]);

            return response()->json([
                'success' => true,
                'message' => "Opci\u{00F3}n actualizada exitosamente.",
                'item' => $item
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "La opci\u{00F3}n seleccionada no existe."
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error al actualizar la opci\u{00F3}n: " . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMetadataItem(string $type, int $id)
    {
        if (!in_array($type, ['contract_type', 'work_schedule', 'location', 'category'], true)) {
            return response()->json([
                'success' => false,
                'message' => "El tipo de mantenedor no es v\u{00E1}lido."
            ], 422);
        }

        try {
            $modelClass = $this->metadataModelClass($type);
            $item = $modelClass::findOrFail($id);

            if ($item->offers()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "No se puede eliminar porque esta opci\u{00F3}n est\u{00E1} asociada a una oferta laboral."
                ], 422);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => "Opci\u{00F3}n eliminada exitosamente."
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "La opci\u{00F3}n seleccionada no existe."
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error al eliminar la opci\u{00F3}n: " . $e->getMessage()
            ], 500);
        }
    }

    private function metadataModelClass(string $type): string
    {
        return match ($type) {
            'contract_type' => JobOpportunityContractType::class,
            'work_schedule' => JobOpportunityWorkSchedule::class,
            'location' => JobOpportunityLocation::class,
            'category' => JobOpportunityOfferCategory::class,
        };
    }
}
