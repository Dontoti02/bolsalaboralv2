<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SettingsConfigurationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('system_configuration');
        Schema::create('system_configuration', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('type');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function test_settings_save_creates_new_configuration_keys_with_required_metadata(): void
    {
        $request = Request::create('/admin/settings', 'POST', [
            'application_name' => 'IESTP Colonia del Caco',
            'maximum_file_size_to_upload' => 5,
            'primary_color' => '#0284c7',
            'secondary_color' => '#0d9488',
            'accent_color' => '#fb923c',
            'theme_mode' => 'light',
            'interface_density' => 'compact',
            'sidebar_style' => 'compact',
            'extensions' => ['pdf', 'docx', 'jpg'],
        ]);

        $response = (new UserController())->saveSettings($request);
        $payload = $response->getData(true);

        $this->assertTrue($payload['success'], $payload['message'] ?? 'Settings save failed.');
        $this->assertDatabaseHas('system_configuration', [
            'key' => 'primary_container_color',
            'name' => 'Color principal suavizado',
            'type' => 'color',
        ]);
        $this->assertDatabaseHas('system_configuration', [
            'key' => 'sidebar_style',
            'name' => 'Estilo del sidebar',
            'type' => 'select',
            'value' => 'compact',
        ]);
    }
}
