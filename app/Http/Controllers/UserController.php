<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use App\Models\Company;
use App\Models\JobOpportunityApplication;
use App\Mail\ApplicationApprovedMail;
use App\Mail\NewApplicationMail;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'names' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|integer|in:1,2,3',
            'doc_type' => 'required|string|in:DNI,RUC,CE',
            'doc_number' => 'required|string|max:20',
        ], [
            'email.unique' => 'El correo electrónico ya está registrado.',
            'role_id.in' => 'El rol seleccionado no es válido.',
            'doc_type.in' => 'El tipo de documento seleccionado no es válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        if (in_array((int) $request->role_id, [2, 3], true) && ($request->doc_type !== 'DNI' || !preg_match('/^\d{8}$/', $request->doc_number))) {
            return response()->json([
                'success' => false,
                'message' => 'Docentes y estudiantes deben registrarse con un DNI válido de 8 dígitos.'
            ], 422);
        }

        if ((int) $request->role_id === 4 && ($request->doc_type !== 'RUC' || !preg_match('/^\d{11}$/', $request->doc_number))) {
            return response()->json([
                'success' => false,
                'message' => 'Las empresas deben registrarse con un RUC válido de 11 dígitos.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $phone = substr($request->phone, 0, 9); // truncate to fit database column limit if needed
            
            // Set password based on role: DNI for students/teachers/admins, RUC for companies
            if ($request->role_id == 4) {
                $password = Hash::make($request->doc_number); // RUC as password for companies
            } else {
                $password = Hash::make($request->doc_number); // DNI/CE as password for students/teachers/admins
            }

            $user = new User();
            $user->email = $request->email;
            $user->password = $password;
            $user->rol_id = $request->role_id;
            $user->is_active = true;
            $user->attempts = 0;

            if ($request->role_id == 4) {
                // For Company role (rol_id = 4)
                $company = Company::create([
                    'name' => $request->names,
                    'ruc' => $request->doc_number,
                    'email' => $request->email,
                    'phone' => $phone ?? '',
                    'mailbox' => $request->email,
                    'is_verified' => true,
                ]);
                $user->company_id = $company->id;
            } else {
                // For Admin, Teacher, Student roles
                $person = Person::create([
                    'document_type' => $request->doc_type,
                    'document_number' => $request->doc_number,
                    'names' => $request->names,
                    'phone' => $phone ?? '',
                    'email' => $request->email,
                ]);
                $user->person_id = $person->id;
            }

            $user->save();

            // Insert into legacy/related role_user table
            DB::table('rol_user')->insert([
                'rol_id' => $request->role_id,
                'user_id' => $user->id
            ]);

            DB::commit();

            $user->load(['person', 'company']);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente.',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'names' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|integer|in:1,2,3',
            'doc_type' => 'required|string|in:DNI,RUC,CE',
            'doc_number' => 'required|string|max:20',
        ], [
            'email.unique' => 'El correo electrónico ya está registrado por otro usuario.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        if (in_array((int) $request->role_id, [2, 3], true) && ($request->doc_type !== 'DNI' || !preg_match('/^\d{8}$/', $request->doc_number))) {
            return response()->json([
                'success' => false,
                'message' => 'Docentes y estudiantes deben registrarse con un DNI válido de 8 dígitos.'
            ], 422);
        }

        if ((int) $request->role_id === 4 && ($request->doc_type !== 'RUC' || !preg_match('/^\d{11}$/', $request->doc_number))) {
            return response()->json([
                'success' => false,
                'message' => 'Las empresas deben registrarse con un RUC válido de 11 dígitos.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $phone = substr($request->phone, 0, 9);
            $user->email = $request->email;
            
            // Check if role changed. (We don't necessarily delete the old person/company record, just migrate relation if needed)
            $oldRoleId = $user->rol_id;
            $user->rol_id = $request->role_id;

            if ($request->role_id == 4) {
                // If it was already a company, update it. If not, create a new one.
                if ($user->company_id) {
                    $company = Company::find($user->company_id);
                    if ($company) {
                        $company->update([
                            'name' => $request->names,
                            'ruc' => $request->doc_number,
                            'email' => $request->email,
                            'phone' => $phone ?? '',
                        ]);
                    }
                } else {
                    $company = Company::create([
                        'name' => $request->names,
                        'ruc' => $request->doc_number,
                        'email' => $request->email,
                        'phone' => $phone ?? '',
                        'mailbox' => $request->email,
                        'is_verified' => true,
                    ]);
                    $user->company_id = $company->id;
                    $user->person_id = null; // detach person
                }
            } else {
                // If it was already a person, update it. If not, create a new one.
                if ($user->person_id) {
                    $person = Person::find($user->person_id);
                    if ($person) {
                        $person->update([
                            'document_type' => $request->doc_type,
                            'document_number' => $request->doc_number,
                            'names' => $request->names,
                            'phone' => $phone ?? '',
                            'email' => $request->email,
                        ]);
                    }
                } else {
                    $person = Person::create([
                        'document_type' => $request->doc_type,
                        'document_number' => $request->doc_number,
                        'names' => $request->names,
                        'phone' => $phone ?? '',
                        'email' => $request->email,
                    ]);
                    $user->person_id = $person->id;
                    $user->company_id = null; // detach company
                }
            }

            $user->save();

            // Update rol_user table
            DB::table('rol_user')
                ->where('user_id', $user->id)
                ->update(['rol_id' => $request->role_id]);

            // If it doesn't exist, insert it
            $hasRolUser = DB::table('rol_user')->where('user_id', $user->id)->exists();
            if (!$hasRolUser) {
                DB::table('rol_user')->insert([
                    'rol_id' => $request->role_id,
                    'user_id' => $user->id
                ]);
            }

            DB::commit();

            $user->load(['person', 'company']);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente.',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active/inactive user status.
     */
    public function toggleStatus($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        try {
            $user->is_active = !$user->is_active;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'El estado del usuario ha sido modificado.',
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar contraseña: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        // Prevent deleting the currently authenticated user
        if ((int)$id === (int)auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propio usuario mientras está iniciada la sesión.'
            ], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            // We do a hard delete or soft delete depending on whether the user has soft deletes enabled.
            // Since `user` has deleted_at column in schema, calling ->delete() will soft delete it.
            $user->delete();

            // Clean rol_user relation
            DB::table('rol_user')->where('user_id', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete users by IDs.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:user,id',
        ], [
            'ids.required' => 'Debe seleccionar al menos un usuario.',
            'ids.array' => 'Formato de datos inválido.',
            'ids.*.exists' => 'Uno o más usuarios no existen.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $ids = $request->input('ids');
            $currentUserId = (int) auth()->id();

            // Remove the currently authenticated user from the deletion list
            $filteredIds = array_values(array_filter($ids, fn($id) => (int)$id !== $currentUserId));
            $skipped = count($ids) - count($filteredIds);

            if (empty($filteredIds)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar tu propio usuario mientras tienes la sesión activa.'
                ], 403);
            }

            $count = count($filteredIds);

            // Delete rol_user relations
            DB::table('rol_user')->whereIn('user_id', $filteredIds)->delete();

            // Soft delete users
            User::whereIn('id', $filteredIds)->delete();

            DB::commit();

            $message = "{$count} usuario(s) eliminado(s) exitosamente.";
            if ($skipped > 0) {
                $message .= ' Tu propio usuario fue omitido y no se eliminó.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'deleted_ids' => $filteredIds,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import users from Excel.
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls',
        ], [
            'file.required' => 'El archivo Excel es obligatorio.',
            'file.mimes' => 'El archivo debe ser un formato válido de Excel (.xlsx, .xls).',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $file = $request->file('file');

        try {
            $import = new UsersImport();
            Excel::import($import, $file);

            $errors = $import->errors;
            $importedUserIds = $import->importedUserIds;

            if (count($errors) > 0 && count($importedUserIds) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => implode("\n", $errors)
                ], 422);
            }

            $importedUsers = [];
            if (!empty($importedUserIds)) {
                $importedUsers = User::with(['person', 'company'])
                    ->whereIn('id', $importedUserIds)
                    ->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Importación completada. ' . count($importedUsers) . ' usuarios registrados.',
                'users' => $importedUsers,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo Excel: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Save settings to system_configuration table.
     */
    public function saveSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_name' => 'required|string|max:255',
            'maximum_file_size_to_upload' => 'required|integer|min:1|max:100',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_mode' => 'nullable|string|in:light,system',
            'interface_density' => 'nullable|string|in:comfortable,compact',
            'sidebar_style' => 'nullable|string|in:expanded,compact',
            'extensions_allowed_to_upload' => 'nullable|string',
            'extensions' => 'nullable|array',
            'logo' => 'nullable|image|max:5120',
            'favicon' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $configColumns = \Illuminate\Support\Facades\Schema::getColumnListing('system_configuration');
            $hasCreatedAt = in_array('created_at', $configColumns, true);
            $hasUpdatedAt = in_array('updated_at', $configColumns, true);
            $hasName = in_array('name', $configColumns, true);
            $hasType = in_array('type', $configColumns, true);
            $configMetadata = [
                'application_name' => ['name' => 'Nombre de la institucion', 'type' => 'text'],
                'maximum_file_size_to_upload' => ['name' => 'Tamano maximo de archivos', 'type' => 'number'],
                'primary_color' => ['name' => 'Color principal', 'type' => 'color'],
                'primary_container_color' => ['name' => 'Color principal suavizado', 'type' => 'color'],
                'secondary_color' => ['name' => 'Color secundario', 'type' => 'color'],
                'secondary_container_color' => ['name' => 'Color secundario suavizado', 'type' => 'color'],
                'accent_color' => ['name' => 'Color de acento', 'type' => 'color'],
                'theme_mode' => ['name' => 'Modo visual', 'type' => 'select'],
                'interface_density' => ['name' => 'Densidad de interfaz', 'type' => 'select'],
                'sidebar_style' => ['name' => 'Estilo del sidebar', 'type' => 'select'],
                'extensions_allowed_to_upload' => ['name' => 'Extensiones permitidas', 'type' => 'json'],
                'logo' => ['name' => 'Logo', 'type' => 'image'],
                'favicon' => ['name' => 'Favicon', 'type' => 'image'],
                'banner' => ['name' => 'Banner de login', 'type' => 'image'],
            ];

            $updateConfig = function($key, $value) use ($hasCreatedAt, $hasUpdatedAt, $hasName, $hasType, $configMetadata) {
                $payload = ['value' => $value];
                $exists = DB::table('system_configuration')->where('key', $key)->exists();

                if ($hasUpdatedAt) {
                    $payload['updated_at'] = now();
                }

                if ($exists) {
                    DB::table('system_configuration')
                        ->where('key', $key)
                        ->update($payload);
                    return;
                }

                $metadata = $configMetadata[$key] ?? [
                    'name' => ucfirst(str_replace('_', ' ', $key)),
                    'type' => 'text',
                ];

                $payload['key'] = $key;
                if ($hasName) {
                    $payload['name'] = $metadata['name'];
                }
                if ($hasType) {
                    $payload['type'] = $metadata['type'];
                }
                if ($hasCreatedAt) {
                    $payload['created_at'] = now();
                }

                DB::table('system_configuration')->insert($payload);
            };

            $updateConfig('application_name', $request->application_name);
            $updateConfig('maximum_file_size_to_upload', $request->maximum_file_size_to_upload);
            $updateConfig('primary_color', $request->primary_color);
            $updateConfig('primary_container_color', $this->softenHexColor($request->primary_color));
            $updateConfig('secondary_color', $request->secondary_color ?: '#006b60');
            $updateConfig('secondary_container_color', $this->softenHexColor($request->secondary_color ?: '#006b60'));
            $updateConfig('accent_color', $request->accent_color ?: '#ff9f43');
            $updateConfig('theme_mode', $request->theme_mode ?: 'light');
            $updateConfig('interface_density', $request->interface_density ?: 'comfortable');
            $updateConfig('sidebar_style', $request->sidebar_style ?: 'expanded');

            // Handle allowed extensions checkbox status
            if ($request->has('application_name')) {
                $checkedExts = $request->input('extensions', []);
                if ($request->has('extensions_allowed_to_upload')) {
                    $checkedExts = array_filter(array_map('trim', explode(',', $request->extensions_allowed_to_upload)));
                }

                $defaultExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'jpg', 'jpeg', 'png', 'gif', 'mp3', 'mp4', 'avi', 'mkv'];
                $mappedExts = [];
                foreach ($defaultExtensions as $ext) {
                    $mappedExts[] = [
                        'extension' => $ext,
                        'permitted' => in_array($ext, $checkedExts)
                    ];
                }
                $updateConfig('extensions_allowed_to_upload', json_encode($mappedExts));
            }

            $uploadPath = public_path('uploads');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $logoUrl = null;
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $logoUrl = '/uploads/' . $filename;
                $updateConfig('logo', $logoUrl);
            }

            if ($request->hasFile('favicon')) {
                $file = $request->file('favicon');
                $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $updateConfig('favicon', '/uploads/' . $filename);
            } elseif ($logoUrl) {
                $updateConfig('favicon', $logoUrl);
            }

            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $filename = 'banner_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $updateConfig('banner', '/uploads/' . $filename);
            }

            DB::commit();

            $config = DB::table('system_configuration')->pluck('value', 'key')->all();

            return response()->json([
                'success' => true,
                'message' => 'Configuraciones guardadas exitosamente.',
                'config' => $config
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar configuraciones: ' . $e->getMessage()
            ], 500);
        }
    }

    private function softenHexColor(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (!preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
            return '#7df7e4';
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $mix = function (int $channel): int {
            return (int) round($channel + ((255 - $channel) * 0.72));
        };

        return sprintf('#%02x%02x%02x', $mix($r), $mix($g), $mix($b));
    }

    /**
     * Delete a specific settings image (logo, favicon or banner).
     */
    public function deleteSettingsImage(Request $request)
    {
        $type = $request->input('type');
        if (!in_array($type, ['logo', 'favicon', 'banner'], true)) {
            return response()->json(['success' => false, 'message' => 'Tipo de imagen no válido.'], 422);
        }

        try {
            $currentPath = DB::table('system_configuration')->where('key', $type)->value('value');

            // Delete the physical file if it exists in uploads/
            if ($currentPath && str_starts_with($currentPath, '/uploads/')) {
                $fullPath = public_path(ltrim($currentPath, '/'));
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }

            // Reset to empty (blank) in the config
            DB::table('system_configuration')
                ->where('key', $type)
                ->update(['value' => '', 'updated_at' => now()]);

            $placeholders = [
                'logo'    => '/assets/logo.png',
                'favicon' => '/assets/favicon.png',
                'banner'  => '/assets/banner.png',
            ];

            return response()->json([
                'success'     => true,
                'message'     => "Imagen '{$type}' eliminada correctamente.",
                'placeholder' => $placeholders[$type],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar imagen: ' . $e->getMessage()], 500);
        }
    }

    /**
     * List all companies with pagination (15 per page) and optional search.
     */
    public function listCompanies()
    {
        try {
            $query = Company::query();

            // Server-side search filter
            if (request()->filled('search')) {
                $search = request()->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('ruc', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $companies = $query->orderBy('created_at', 'desc')
                               ->paginate(15)
                               ->withQueryString();

            // Add extra attributes to each company in the current page
            $companies->getCollection()->transform(function ($company) {
                $company->offers_count = \App\Models\JobOpportunityOffer::where('company_id', $company->id)->count();
                $user = User::where('company_id', $company->id)->first();
                $company->user_id = $user ? $user->id : null;
                return $company;
            });

            return response()->json([
                'success' => true,
                'companies' => $companies->items(),
                'pagination' => [
                    'current_page' => $companies->currentPage(),
                    'last_page'    => $companies->lastPage(),
                    'per_page'     => $companies->perPage(),
                    'total'        => $companies->total(),
                    'from'         => $companies->firstItem(),
                    'to'           => $companies->lastItem(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar empresas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete companies (admin only).
     */
    public function bulkDeleteCompanies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:job_opportunity_company,id',
        ], [
            'ids.required' => 'Debe seleccionar al menos una empresa.',
            'ids.array'    => 'Formato de datos inválido.',
            'ids.*.exists' => 'Una o más empresas no existen.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $ids = $request->input('ids');
        $deletedCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $company = Company::find($id);
                if (!$company) {
                    $errors[] = "Empresa ID {$id} no encontrada.";
                    continue;
                }

                // 1. Get offer IDs of the company
                $offerIds = DB::table('job_opportunity_offer')->where('company_id', $id)->pluck('id');
                if ($offerIds->isNotEmpty()) {
                    // 2. Delete applications for these offers
                    DB::table('job_opportunity_applications')->whereIn('offer_id', $offerIds)->delete();
                    // 3. Delete offer state details
                    DB::table('job_opportunity_offer_state_detail')->whereIn('offer_id', $offerIds)->delete();
                    // 4. Delete the offers
                    DB::table('job_opportunity_offer')->whereIn('id', $offerIds)->delete();
                }

                // 5. Get user IDs of the company
                $userIds = User::where('company_id', $id)->pluck('id');
                if ($userIds->isNotEmpty()) {
                    // 6. Delete rol_user associations
                    DB::table('rol_user')->whereIn('user_id', $userIds)->delete();
                    // 7. Delete the users
                    User::whereIn('id', $userIds)->delete();
                }

                // 8. Delete the company (hard delete)
                $company->delete();
                $deletedCount++;
            }

            DB::commit();

            $message = $deletedCount > 0
                ? "{$deletedCount} empresa(s) eliminada(s) permanentemente."
                : 'No se eliminaron empresas.';

            if (!empty($errors)) {
                $message .= ' ' . implode(' ', $errors);
            }

            return response()->json([
                'success' => $deletedCount > 0,
                'message' => $message,
                'deleted_count' => $deletedCount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar empresas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly registered company from admin.
     */
    public function storeCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'ruc' => 'required|string|size:11',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'mailbox' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.size' => 'El RUC debe tener exactamente 11 dígitos.',
            'phone.required' => 'El teléfono es obligatorio.',
            'address.required' => 'La dirección es obligatoria.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Find user if user_id is provided, or by email
            $user = null;
            if ($request->filled('user_id')) {
                $user = User::find($request->user_id);
            }
            if (!$user) {
                $user = User::where('email', $request->email)->first();
            }

            // Find if a company with this RUC already exists
            $company = Company::where('ruc', $request->ruc)->first();

            // Validate that we are not trying to hijack another user's email/RUC
            if ($user && $user->rol_id != 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este correo electrónico ya está registrado por un usuario con otro rol.'
                ], 422);
            }

            if ($company) {
                // If company exists, make sure it is linked to the correct user (if any)
                $linkedUser = User::where('company_id', $company->id)->first();
                if ($linkedUser && $linkedUser->email !== $request->email) {
                    if (!$user || $linkedUser->id !== $user->id) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Este RUC ya está registrado para otra empresa.'
                        ], 422);
                    }
                }
            }

            if (!$company) {
                // If company doesn't exist by RUC, check if user exists and has a company linked
                if ($user && $user->company_id) {
                    $company = Company::find($user->company_id);
                }
            }

            // If we still don't have a company, create a new one
            if (!$company) {
                $company = new Company();
                $company->ruc = $request->ruc;
            }

            // Update company fields
            $company->name = $request->name;
            $company->email = $request->email;
            $company->phone = $request->phone;
            $company->address = $request->address;
            $company->mailbox = $request->mailbox ?? $request->email;
            $company->website = $request->website;
            $company->description = $request->description;
            $company->is_verified = true;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $uploadPath = public_path('uploads/logos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $filename);
                $company->logo = '/uploads/logos/' . $filename;
            }

            $company->save();

            // Handle user account linking
            if (!$user) {
                // If company is already linked to some user, update that user's email
                $linkedUser = User::where('company_id', $company->id)->first();
                if ($linkedUser) {
                    $user = $linkedUser;
                    $user->email = $request->email;
                    $user->save();
                } else {
                    // Create associated User account with RUC as password
                    $user = new User();
                    $user->company_id = $company->id;
                    $user->rol_id = 4; // Empresa
                    $user->email = $request->email;
                    $user->password = Hash::make($request->ruc); // RUC as password
                    $user->is_active = true;
                    $user->attempts = 0;
                    $user->save();

                    // Insert into legacy/related role_user table
                    DB::table('rol_user')->insert([
                        'rol_id' => 4,
                        'user_id' => $user->id
                    ]);
                }
            } else {
                // Link the user to the company
                $user->company_id = $company->id;
                $user->save();
                
                // If this user was not mapped to role 4 in rol_user table, map it
                $hasRolUser = DB::table('rol_user')->where('user_id', $user->id)->where('rol_id', 4)->exists();
                if (!$hasRolUser) {
                    DB::table('rol_user')->insert([
                        'rol_id' => 4,
                        'user_id' => $user->id
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Empresa registrada y vinculada exitosamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar empresa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing company from admin.
     */
    public function updateCompany(Request $request, $id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada.'
            ], 404);
        }

        $user = User::where('company_id', $id)->first();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . ($user ? $user->id : 'NULL'),
            'ruc' => 'required|string|size:11|unique:job_opportunity_company,ruc,' . $id,
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'mailbox' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.size' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.unique' => 'Este RUC ya está registrado.',
            'phone.required' => 'El teléfono es obligatorio.',
            'address.required' => 'La dirección es obligatoria.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $company->name = $request->name;
            $company->ruc = $request->ruc;
            $company->email = $request->email;
            $company->phone = $request->phone;
            $company->address = $request->address;
            $company->mailbox = $request->mailbox ?? $request->email;
            $company->website = $request->website;
            $company->description = $request->description;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $uploadPath = public_path('uploads/logos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $filename);
                $company->logo = '/uploads/logos/' . $filename;
            }

            $company->save();

            // Update associated User account if it exists
            if ($user) {
                $user->email = $request->email;
                $user->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Empresa actualizada exitosamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar empresa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle verification state of a company.
     */
    public function toggleVerifyCompany(Request $request, $id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada.'
            ], 404);
        }

        try {
            $company->is_verified = !$company->is_verified;
            $company->save();

            $statusText = $company->is_verified ? 'verificada' : 'no verificada';

            return response()->json([
                'success' => true,
                'message' => "La empresa ha sido {$statusText} exitosamente.",
                'is_verified' => $company->is_verified
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar verificación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a company and its user.
     */
    public function deleteCompany($id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            // 1. Get offer IDs of the company
            $offerIds = DB::table('job_opportunity_offer')->where('company_id', $id)->pluck('id');
            if ($offerIds->isNotEmpty()) {
                // 2. Delete applications for these offers
                DB::table('job_opportunity_applications')->whereIn('offer_id', $offerIds)->delete();
                // 3. Delete offer state details
                DB::table('job_opportunity_offer_state_detail')->whereIn('offer_id', $offerIds)->delete();
                // 4. Delete the offers
                DB::table('job_opportunity_offer')->whereIn('id', $offerIds)->delete();
            }

            // 5. Get user IDs of the company
            $userIds = User::where('company_id', $id)->pluck('id');
            if ($userIds->isNotEmpty()) {
                // 6. Delete rol_user associations
                DB::table('rol_user')->whereIn('user_id', $userIds)->delete();
                // 7. Delete the users
                User::whereIn('id', $userIds)->delete();
            }

            // 8. Delete company
            $company->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Empresa eliminada permanentemente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar empresa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all applications (admin only).
     */
    public function listApplications(Request $request)
    {
        try {
            $query = JobOpportunityApplication::with(['user.person', 'offer.company'])
                ->latest();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('fullname', 'like', "%{$search}%")
                      ->orWhereHas('offer', function ($oq) use ($search) {
                          $oq->where('title', 'like', "%{$search}%");
                      });
                });
            }

            $applications = $query->get();

            return response()->json([
                'success' => true,
                'applications' => $applications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar postulaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export dashboard summary and recent companies to a real Excel sheet (admin only).
     */
    public function exportExcel()
    {
        if (!\Illuminate\Support\Facades\Auth::check() || \Illuminate\Support\Facades\Auth::user()->rol_id !== 1) {
            abort(403);
        }

        try {
            $totalUsers = User::count();
            $pendingCompanies = Company::where('status', 'pending')->count();
            $activeOffers = \App\Models\JobOpportunityOffer::where('state_id', 2)->count();
            $totalApplications = JobOpportunityApplication::count();
            
            $recentCompanies = Company::latest()
                ->take(5)
                ->get()
                ->map(function ($c) {
                    $c->formatted_date = $c->created_at ? $c->created_at->format('d M Y') : '-';
                    $c->status_label = match ($c->status) {
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado',
                        default => 'Pendiente',
                    };
                    return $c;
                });

            $data = [
                'totalUsers' => $totalUsers,
                'pendingCompanies' => $pendingCompanies,
                'activeOffers' => $activeOffers,
                'totalApplications' => $totalApplications,
                'recentCompanies' => $recentCompanies
            ];

            return Excel::download(new \App\Exports\AdminDashboardExport($data), 'resumen-admin-' . date('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar Excel: ' . $e->getMessage());
        }
    }

    /**
     * Update application status and feedback (admin only).
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:postulated,under_review,accepted,rejected',
            'feedback' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $app = JobOpportunityApplication::with(['offer.company', 'user.person'])->findOrFail($id);
            $previousStatus = $app->status;
            $app->status = $request->status;
            $app->feedback = $request->feedback ?? $app->feedback;
            $app->feedback_date = now();
            $app->save();

            // Notify student via in-app notification
            if ($app->user_id) {
                try {
                    $statusText = match($request->status) {
                        'accepted' => 'aceptada',
                        'rejected' => 'rechazada',
                        'under_review' => 'en revisión',
                        default => 'actualizada',
                    };
                    UserNotification::create([
                        'user_id' => $app->user_id,
                        'title' => 'Estado de postulación actualizado',
                        'message' => "Tu postulación ha sido {$statusText} por el administrador.",
                        'link' => '/student/dashboard?tab=applications',
                    ]);
                } catch (\Exception $e) {
                    logger()->error('Error creating admin notification for application status: ' . $e->getMessage());
                }
            }

            // Send email notification when application is approved (accepted)
            if ($request->status === 'accepted' && $previousStatus !== 'accepted') {
                $studentEmail = $app->user?->email;
                if ($studentEmail) {
                    try {
                        Mail::to($studentEmail)
                            ->send(new ApplicationApprovedMail($app));
                    } catch (\Exception $mailEx) {
                        // Log mail error but don't break the request
                        logger()->error('Error sending approval email: ' . $mailEx->getMessage());
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado de postulación actualizado exitosamente.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Postulación no encontrada.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an application (admin only).
     */
    public function deleteApplication($id)
    {
        try {
            $application = JobOpportunityApplication::findOrFail($id);
            $application->delete();

            return response()->json([
                'success' => true,
                'message' => 'Postulación eliminada exitosamente.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Postulación no encontrada.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar postulación: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOwnProfile(Request $request)
    {
        $user = $request->user();

        if (!$user || (int) $user->rol_id !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permisos para actualizar este perfil.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'names' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user,email,' . $user->id,
            'phone' => 'nullable|string|max:9',
            'document_type' => 'required|string|in:DNI,CE,PASAPORTE',
            'document_number' => 'required|string|max:20',
            'sex' => 'nullable|string|in:M,F,O',
            'birth_date' => 'nullable|date|before:today',
            'native_language' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
        ], [
            'email.unique' => "El correo electr\u{00F3}nico ya est\u{00E1} registrado.",
            'avatar.image' => "La foto seleccionada no es una imagen v\u{00E1}lida.",
            'avatar.mimes' => 'La foto debe ser JPG, PNG o WEBP.',
            'avatar.max' => 'La foto no puede superar los 3 MB.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $person = $user->person ?: new Person();
            $person->fill([
                'names' => trim($request->names),
                'email' => $request->email,
                'phone' => $request->phone ?: '',
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'sex' => $request->sex ?: null,
                'birth_date' => $request->birth_date ?: null,
                'native_language' => $request->native_language ?: null,
            ]);
            $person->save();

            $user->person_id = $person->id;
            $user->email = $request->email;

            if ($request->hasFile('avatar')) {
                $oldAvatar = $user->avatar;
                $user->avatar = $request->file('avatar')->store('profile-photos', 'public');

                if ($oldAvatar && !str_starts_with($oldAvatar, 'http')) {
                    Storage::disk('public')->delete($oldAvatar);
                }
            }

            $user->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado correctamente.',
                'profile' => [
                    'names' => $person->names,
                    'email' => $user->email,
                    'phone' => $person->phone,
                    'document_type' => $person->document_type,
                    'document_number' => $person->document_number,
                    'sex' => $person->sex,
                    'birth_date' => $person->birth_date,
                    'native_language' => $person->native_language,
                    'avatar_url' => $user->avatar ? Storage::url($user->avatar) : null,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOwnPassword(Request $request)
    {
        $user = $request->user();

        if (!$user || (int) $user->rol_id !== 1) {
            return response()->json([
                'success' => false,
                'message' => "No tiene permisos para realizar esta acci\u{00F3}n."
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'current_password.required' => "Ingrese su contrase\u{00F1}a actual.",
            'password.required' => "Ingrese la nueva contrase\u{00F1}a.",
            'password.min' => "La nueva contrase\u{00F1}a debe tener al menos 8 caracteres.",
            'password.confirmed' => "La confirmaci\u{00F3}n no coincide con la nueva contrase\u{00F1}a.",
            'password.different' => "La nueva contrase\u{00F1}a debe ser distinta a la actual.",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => "La contrase\u{00F1}a actual no es correcta."
            ], 422);
        }

        $user->password = $request->password;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Contrase\u{00F1}a actualizada correctamente."
        ]);
    }

    /**
     * Get applicants for a specific offer (admin only).
     */
    public function getOfferApplicants($id)
    {
        try {
            $offer = \App\Models\JobOpportunityOffer::select('id', 'title', 'company_id')->with('company:id,name')->findOrFail($id);
            
            $applications = \App\Models\JobOpportunityApplication::where('offer_id', $id)
                ->with([
                    'user' => function ($q) {
                        $q->select('id', 'email', 'person_id')->with('person:id,names,phone');
                    }
                ])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'offer' => $offer,
                'applications' => $applications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener postulantes: ' . $e->getMessage()
            ], 500);
        }
    }


}
