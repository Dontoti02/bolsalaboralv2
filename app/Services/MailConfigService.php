<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class MailConfigService
{
    /**
     * Apply outgoing mail settings from system_configuration table to Laravel mail config.
     */
    public static function apply(): void
    {
        try {
            if (!Schema::hasTable('system_configuration')) {
                return;
            }

            $keys = [
                'mail_enabled',
                'mail_host',
                'mail_port',
                'mail_encryption',
                'mail_username',
                'mail_password',
                'mail_from_address',
                'mail_from_name',
                'application_name',
            ];

            $configs = DB::table('system_configuration')
                ->whereIn('key', $keys)
                ->pluck('value', 'key');

            if ($configs->isEmpty()) {
                return;
            }

            $isEnabled = (string) ($configs['mail_enabled'] ?? '0') === '1';

            if (!$isEnabled) {
                Config::set('mail.default', 'log');
                Mail::purge();
                return;
            }

            $host = !empty($configs['mail_host']) ? $configs['mail_host'] : env('MAIL_HOST', 'smtp.gmail.com');
            $port = !empty($configs['mail_port']) ? (int) $configs['mail_port'] : (int) env('MAIL_PORT', 587);
            $encryption = $configs['mail_encryption'] ?? 'tls';
            $username = !empty($configs['mail_username']) ? $configs['mail_username'] : env('MAIL_USERNAME');

            $rawPassword = $configs['mail_password'] ?? null;
            $password = null;
            if (!empty($rawPassword)) {
                try {
                    $password = Crypt::decryptString($rawPassword);
                } catch (\Exception $e) {
                    $password = $rawPassword;
                }
            } else {
                $password = env('MAIL_PASSWORD');
            }

            $fromAddress = !empty($configs['mail_from_address'])
                ? $configs['mail_from_address']
                : env('MAIL_FROM_ADDRESS', $username ?: 'no-reply@instituto.edu.pe');

            $fromName = !empty($configs['mail_from_name'])
                ? $configs['mail_from_name']
                : ($configs['application_name'] ?? env('MAIL_FROM_NAME', 'Bolsa Laboral'));

            $scheme = null;
            if (strtolower((string) $encryption) === 'ssl' || $port === 465) {
                $scheme = 'smtps';
            }

            $encValue = ($encryption === 'none' || empty($encryption)) ? null : $encryption;

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.scheme', $scheme);
            Config::set('mail.mailers.smtp.encryption', $encValue);
            Config::set('mail.mailers.smtp.username', $username);
            Config::set('mail.mailers.smtp.password', $password);

            // Permit local development SSL compatibility
            Config::set('mail.mailers.smtp.stream', [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName);

            Mail::purge('smtp');
        } catch (\Throwable $e) {
            // Silently ignore if database is unavailable during install or migrations
        }
    }
}
