<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicLandingTest extends TestCase
{
    public function test_guest_can_access_landing_page_without_login_redirect(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_guest_can_search_offers_via_ajax(): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('job_opportunity_offer')) {
            $this->markTestSkipped('La tabla "job_opportunity_offer" no existe en este entorno de pruebas sqlite.');
        }

        $response = $this->getJson('/buscar-ofertas');
        $response->assertStatus(200);
        $response->assertJsonStructure(['offers', 'total']);
    }

    public function test_guest_cannot_view_nonexistent_offer(): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('job_opportunity_offer')) {
            $this->markTestSkipped('La tabla "job_opportunity_offer" no existe en este entorno de pruebas sqlite.');
        }

        $response = $this->get('/ofertas/999999');
        $response->assertStatus(404);
    }
}
