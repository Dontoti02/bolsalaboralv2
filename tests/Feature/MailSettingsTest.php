<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Services\MailConfigService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MailSettingsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('system_configuration');
        Schema::create('system_configuration', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name')->unique();
            $table->string('type');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function test_can_save_mail_settings_with_encrypted_password(): void
    {
        $request = Request::create('/admin/settings/mail', 'POST', [
            'mail_enabled' => '1',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => 587,
            'mail_encryption' => 'tls',
            'mail_username' => 'notificaciones@instituto.edu.pe',
            'mail_password' => 'supersecretapppassword',
            'mail_from_address' => 'notificaciones@instituto.edu.pe',
            'mail_from_name' => 'IESTP Sangarará',
        ]);

        $controller = new UserController();
        $response = $controller->saveMailSettings($request);
        $data = $response->getData(true);

        $this->assertTrue($data['success']);

        // Verify database entry for mail_host
        $this->assertDatabaseHas('system_configuration', [
            'key' => 'mail_host',
            'value' => 'smtp.gmail.com',
        ]);

        // Verify password was encrypted and not saved in plaintext
        $dbPassword = DB::table('system_configuration')->where('key', 'mail_password')->value('value');
        $this->assertNotEquals('supersecretapppassword', $dbPassword);
        $this->assertEquals('supersecretapppassword', Crypt::decryptString($dbPassword));

        // Verify MailConfigService applied settings
        MailConfigService::apply();
        $this->assertEquals('smtp', config('mail.default'));
        $this->assertEquals('smtp.gmail.com', config('mail.mailers.smtp.host'));
        $this->assertEquals(587, config('mail.mailers.smtp.port'));
        $this->assertEquals('notificaciones@instituto.edu.pe', config('mail.mailers.smtp.username'));
        $this->assertEquals('supersecretapppassword', config('mail.mailers.smtp.password'));
        $this->assertEquals('notificaciones@instituto.edu.pe', config('mail.from.address'));
        $this->assertEquals('IESTP Sangarará', config('mail.from.name'));
    }

    public function test_empty_password_retains_previously_saved_encrypted_password(): void
    {
        // First save with password
        $controller = new UserController();
        $controller->saveMailSettings(Request::create('/admin/settings/mail', 'POST', [
            'mail_enabled' => '1',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => 587,
            'mail_encryption' => 'tls',
            'mail_username' => 'admin@instituto.edu.pe',
            'mail_password' => 'my-initial-password',
        ]));

        $originalEncrypted = DB::table('system_configuration')->where('key', 'mail_password')->value('value');

        // Second save without password
        $controller->saveMailSettings(Request::create('/admin/settings/mail', 'POST', [
            'mail_enabled' => '1',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => 465,
            'mail_encryption' => 'ssl',
            'mail_username' => 'admin@instituto.edu.pe',
            'mail_password' => '', // blank to retain
        ]));

        $retainedEncrypted = DB::table('system_configuration')->where('key', 'mail_password')->value('value');
        $this->assertEquals($originalEncrypted, $retainedEncrypted);
        $this->assertEquals('my-initial-password', Crypt::decryptString($retainedEncrypted));
        $this->assertEquals('465', DB::table('system_configuration')->where('key', 'mail_port')->value('value'));
    }

    public function test_disabling_mail_switches_default_mailer_to_log(): void
    {
        $controller = new UserController();
        $controller->saveMailSettings(Request::create('/admin/settings/mail', 'POST', [
            'mail_enabled' => '0',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => 587,
            'mail_encryption' => 'tls',
            'mail_username' => 'notificaciones@instituto.edu.pe',
        ]));

        MailConfigService::apply();
        $this->assertEquals('log', config('mail.default'));
    }

    public function test_validation_rejects_invalid_port(): void
    {
        $controller = new UserController();
        $response = $controller->saveMailSettings(Request::create('/admin/settings/mail', 'POST', [
            'mail_port' => 999999, // out of range
        ]));

        $data = $response->getData(true);
        $this->assertFalse($data['success']);
        $this->assertEquals(422, $response->getStatusCode());
    }
}
