<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_root_returns_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin_dashboard_redirects_to_login_for_unauthenticated_user(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_admin_dashboard_is_accessible_to_authenticated_admin(): void
    {
        try {
            $user = User::where('email', 'alejandro.ruiz@talentum.edu.pe')->first();
        } catch (\Illuminate\Database\QueryException $e) {
            $user = null;
        }
        
        if ($user) {
            $response = $this->actingAs($user)->get('/admin/dashboard');
            $response->assertStatus(200);
        } else {
            $user = new User([
                'email' => 'admin@test.com',
                'rol_id' => 1,
                'is_active' => true,
            ]);
            $response = $this->actingAs($user)->get('/admin/dashboard');
            $response->assertStatus(200);
        }
    }

    public function test_student_dashboard_redirects_to_login_for_unauthenticated_user(): void
    {
        $response = $this->get('/student/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_student_dashboard_is_accessible_to_authenticated_student(): void
    {
        try {
            $user = User::where('email', 'maria.espinoza@talentum.edu.pe')->first();
        } catch (\Illuminate\Database\QueryException $e) {
            $user = null;
        }
        
        if ($user) {
            $response = $this->actingAs($user)->get('/student/dashboard');
            $response->assertStatus(200);
        } else {
            $user = new User([
                'email' => 'student@test.com',
                'rol_id' => 3,
                'is_active' => true,
            ]);
            $response = $this->actingAs($user)->get('/student/dashboard');
            $response->assertStatus(200);
        }
    }

    public function test_company_dashboard_redirects_to_login_for_unauthenticated_user(): void
    {
        $response = $this->get('/company/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_company_dashboard_is_accessible_to_authenticated_company(): void
    {
        try {
            $user = User::where('email', 'contacto@technova.pe')->first();
        } catch (\Illuminate\Database\QueryException $e) {
            $user = null;
        }
        
        if ($user) {
            $response = $this->actingAs($user)->get('/company/dashboard');
            $response->assertStatus(200);
        } else {
            $user = new User([
                'email' => 'company@test.com',
                'rol_id' => 4,
                'is_active' => true,
            ]);
            $response = $this->actingAs($user)->get('/company/dashboard');
            $response->assertStatus(200);
        }
    }

    public function test_teacher_dashboard_redirects_to_login_for_unauthenticated_user(): void
    {
        $response = $this->get('/teacher/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_teacher_dashboard_is_accessible_to_authenticated_teacher(): void
    {
        try {
            $user = User::where('email', 'carlos.valenzuela@talentum.edu.pe')->first();
        } catch (\Illuminate\Database\QueryException $e) {
            $user = null;
        }
        
        if ($user) {
            $response = $this->actingAs($user)->get('/teacher/dashboard');
            $response->assertStatus(200);
        } else {
            $user = new User([
                'email' => 'teacher@test.com',
                'rol_id' => 2,
                'is_active' => true,
            ]);
            $response = $this->actingAs($user)->get('/teacher/dashboard');
            $response->assertStatus(200);
        }
    }
}


