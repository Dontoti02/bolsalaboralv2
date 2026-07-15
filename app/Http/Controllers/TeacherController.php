<?php

namespace App\Http\Controllers;

use App\Models\JobOpportunityOffer;
use App\Models\JobOpportunityOfferState;

class TeacherController extends Controller
{
    /**
     * Show the teacher dashboard with real offers data.
     */
    public function dashboard()
    {
        try {
            // Get all active offers for teachers to view/share with students
            $activeState = JobOpportunityOfferState::where('key', 'active')->first();
            $activeStateId = $activeState ? $activeState->id : 2;

            $activeOffers = JobOpportunityOffer::with([
                    'company:id,name,logo',
                    'state',
                    'category:id,name',
                    'workSchedule:id,name',
                    'modality:id,name',
                    'contractType:id,name'
                ])
                ->where('state_id', $activeStateId)
                ->orderBy('publication_date', 'desc')
                ->take(9)
                ->get()
                ->map(function ($offer) {
                    $offer->company_name = $offer->company->name ?? 'Empresa';
                    $offer->location_name = $offer->modality->name ?? 'No especificada';
                    $offer->schedule_name = $offer->workSchedule->name ?? 'Jornada completa';
                    $offer->category_name = $offer->category->name ?? 'General';
                    $offer->state_name = $offer->state->name ?? 'Activa';
                    $offer->initial = $offer->company ? strtoupper(substr($offer->company->name, 0, 1)) : 'E';
                    $offer->publication_formatted = $offer->publication_date
                        ? $offer->publication_date->format('d M Y')
                        : 'Fecha no disponible';
                    return $offer;
                });

            // Also get some finished/closed offers for display variety
            $closedState = JobOpportunityOfferState::where('key', 'finished')->first();
            $closedStateId = $closedState ? $closedState->id : 3;

            $closedOffers = JobOpportunityOffer::with([
                    'company:id,name,logo',
                    'state',
                    'category:id,name',
                    'workSchedule:id,name',
                    'modality:id,name',
                    'contractType:id,name'
                ])
                ->where('state_id', $closedStateId)
                ->orderBy('publication_date', 'desc')
                ->take(3)
                ->get()
                ->map(function ($offer) {
                    $offer->company_name = $offer->company->name ?? 'Empresa';
                    $offer->location_name = $offer->modality->name ?? 'No especificada';
                    $offer->schedule_name = $offer->workSchedule->name ?? 'Jornada completa';
                    $offer->category_name = $offer->category->name ?? 'General';
                    $offer->state_name = $offer->state->name ?? 'Cerrada';
                    $offer->initial = $offer->company ? strtoupper(substr($offer->company->name, 0, 1)) : 'E';
                    $offer->publication_formatted = $offer->publication_date
                        ? $offer->publication_date->format('d M Y')
                        : 'Fecha no disponible';
                    return $offer;
                });

        } catch (\Exception $e) {
            $activeOffers = collect();
            $closedOffers = collect();
        }

        try {
            $config = \Illuminate\Support\Facades\DB::table('system_configuration')->pluck('value', 'key')->all();
        } catch (\Exception $e) {
            $config = [];
        }

        return view('teacher.dashboard', compact('activeOffers', 'closedOffers', 'config'));
    }

    /**
     * Change teacher password.
     */
    public function changePassword(\Illuminate\Http\Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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
}