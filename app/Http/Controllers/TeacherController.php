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
                    'location:id,name',
                    'contractType:id,name'
                ])
                ->where('state_id', $activeStateId)
                ->orderBy('publication_date', 'desc')
                ->take(9)
                ->get()
                ->map(function ($offer) {
                    $offer->company_name = $offer->company->name ?? 'Empresa';
                    $offer->location_name = $offer->location->name ?? 'No especificada';
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
                    'location:id,name',
                    'contractType:id,name'
                ])
                ->where('state_id', $closedStateId)
                ->orderBy('publication_date', 'desc')
                ->take(3)
                ->get()
                ->map(function ($offer) {
                    $offer->company_name = $offer->company->name ?? 'Empresa';
                    $offer->location_name = $offer->location->name ?? 'No especificada';
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
}