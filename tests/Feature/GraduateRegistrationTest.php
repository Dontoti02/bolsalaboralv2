<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Person;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GraduateRegistrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('user')) {
            $this->markTestSkipped('La tabla "user" no existe en este entorno.');
        }
    }

    public function test_graduate_registered_from_login_is_inactive_pending_verification()
    {
        $uniqueDni = '9' . substr(strval(time()), -7);
        $email = 'egresado.test.' . time() . '@test.com';

        $response = $this->postJson('/register/graduate', [
            'names' => 'Egresado de Prueba',
            'document_number' => $uniqueDni,
            'email' => $email,
            'phone' => '987654321',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'pending_approval' => true,
                 ]);

        // Verificamos que el usuario en la BD tenga is_active = false
        $this->assertDatabaseHas('user', [
            'email' => $email,
            'rol_id' => 5,
            'is_active' => false,
        ]);

        // Verificamos que no haya iniciado sesión automáticamente
        $this->assertGuest();
    }
}
