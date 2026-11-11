<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ConsultaDocumentoController extends Controller
{
    /**
     * Consulta datos de DNI ante RENIEC mediante proxy seguro backend.
     */
    public function consultarDni(string $dni)
    {
        $dni = trim($dni);

        if (!preg_match('/^[0-9]{8}$/', $dni)) {
            return response()->json([
                'success' => false,
                'message' => 'El DNI debe contener exactamente 8 dígitos numéricos.',
            ], 422);
        }

        $cacheKey = "consulta_reniec_{$dni}";
        $cachedData = Cache::get($cacheKey);
        if ($cachedData) {
            return response()->json([
                'success' => true,
                'data' => $cachedData,
                'source' => 'cache',
            ]);
        }

        $baseUrl = rtrim(config('services.consultas_peru.base_url', 'https://ww2.desarrollo21.pe'), '/');
        $token = config('services.consultas_peru.token');

        if (empty($token)) {
            Log::error('ConsultaDocumentoController: Token de Consultas Perú no configurado.');
            return response()->json([
                'success' => false,
                'message' => 'Servicio de consulta no configurado en el servidor.',
            ], 503);
        }

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->acceptJson()
                ->get("{$baseUrl}/api/reniec/{$dni}");

            if ($response->successful()) {
                $body = $response->json();
                if (!empty($body['success']) && !empty($body['data'])) {
                    $item = $body['data'];

                    $normalized = [
                        'dni' => $item['dni'] ?? $dni,
                        'nombre_completo' => $item['nombre_completo'] ?? trim(($item['nombres'] ?? '') . ' ' . ($item['apellidoPaterno'] ?? '') . ' ' . ($item['apellidoMaterno'] ?? '')),
                        'nombres' => $item['nombres'] ?? '',
                        'apellido_paterno' => $item['apellidoPaterno'] ?? '',
                        'apellido_materno' => $item['apellidoMaterno'] ?? '',
                        'codigo_verificacion' => $item['codigo_verificacion'] ?? null,
                    ];

                    Cache::put($cacheKey, $normalized, now()->addDays(30));

                    return response()->json([
                        'success' => true,
                        'data' => $normalized,
                        'source' => 'api',
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => $body['message'] ?? 'No se encontraron datos para el DNI ingresado.',
                ], 404);
            }

            Log::warning("Consulta RENIEC fallida para DNI {$dni}: Status {$response->status()}");
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener información para el DNI ingresado.',
            ], 404);

        } catch (\Throwable $e) {
            Log::error("Error conectando con API RENIEC: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al consultar el servicio de RENIEC.',
            ], 500);
        }
    }

    /**
     * Consulta datos de RUC ante SUNAT mediante proxy seguro backend.
     */
    public function consultarRuc(string $ruc)
    {
        $ruc = trim($ruc);

        if (!preg_match('/^[0-9]{11}$/', $ruc)) {
            return response()->json([
                'success' => false,
                'message' => 'El RUC debe contener exactamente 11 dígitos numéricos.',
            ], 422);
        }

        $cacheKey = "consulta_sunat_{$ruc}";
        $cachedData = Cache::get($cacheKey);
        if ($cachedData) {
            return response()->json([
                'success' => true,
                'data' => $cachedData,
                'source' => 'cache',
            ]);
        }

        $baseUrl = rtrim(config('services.consultas_peru.base_url', 'https://ww2.desarrollo21.pe'), '/');
        $token = config('services.consultas_peru.token');

        if (empty($token)) {
            Log::error('ConsultaDocumentoController: Token de Consultas Perú no configurado.');
            return response()->json([
                'success' => false,
                'message' => 'Servicio de consulta no configurado en el servidor.',
            ], 503);
        }

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->acceptJson()
                ->get("{$baseUrl}/api/sunat/{$ruc}");

            if ($response->successful()) {
                $body = $response->json();
                if (!empty($body['success']) && !empty($body['data'])) {
                    $item = $body['data'];

                    $normalized = [
                        'ruc' => $item['ruc'] ?? $ruc,
                        'razon_social' => $item['nombre'] ?? '',
                        'nombre' => $item['nombre'] ?? '',
                        'estado' => $item['estado'] ?? '',
                        'condicion' => $item['condicion'] ?? '',
                        'departamento' => $item['departamento'] ?? '',
                        'provincia' => $item['provincia'] ?? '',
                        'distrito' => $item['distrito'] ?? '',
                        'direccion' => $item['direccion'] ?? '',
                        'direccion_completa' => $item['direccion_completa'] ?? '',
                        'ubigeo_sunat' => $item['ubigeo_sunat'] ?? null,
                    ];

                    Cache::put($cacheKey, $normalized, now()->addHours(24));

                    return response()->json([
                        'success' => true,
                        'data' => $normalized,
                        'source' => 'api',
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => $body['message'] ?? 'No se encontraron datos para el RUC ingresado.',
                ], 404);
            }

            Log::warning("Consulta SUNAT fallida para RUC {$ruc}: Status {$response->status()}");
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener información para el RUC ingresado.',
            ], 404);

        } catch (\Throwable $e) {
            Log::error("Error conectando con API SUNAT: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al consultar el servicio de SUNAT.',
            ], 500);
        }
    }
}

