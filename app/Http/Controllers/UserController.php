<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'role_id' => 'required|integer|in:1,2,3,4',
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
            'role_id' => 'required|integer|in:1,2,3,4',
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

            $updateConfig = function($key, $value) {
                DB::table('system_configuration')
                    ->where('key', $key)
                    ->update([
                        'value' => $value,
                        'updated_at' => now()
                    ]);
            };

            $updateConfig('application_name', $request->application_name);
            $updateConfig('maximum_file_size_to_upload', $request->maximum_file_size_to_upload);
            $updateConfig('primary_color', $request->primary_color);

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

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $updateConfig('logo', '/uploads/' . $filename);
            }

            if ($request->hasFile('favicon')) {
                $file = $request->file('favicon');
                $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $updateConfig('favicon', '/uploads/' . $filename);
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
     * List all companies.
     */
    public function listCompanies()
    {
        try {
            $companies = Company::all();
            foreach ($companies as $company) {
                $company->offers_count = \App\Models\JobOpportunityOffer::where('company_id', $company->id)->count();
                $user = User::where('company_id', $company->id)->first();
                $company->user_id = $user ? $user->id : null;
            }
            return response()->json([
                'success' => true,
                'companies' => $companies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar empresas: ' . $e->getMessage()
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

            // Delete associated user
            User::where('company_id', $id)->delete();

            // Delete company
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
     * List all job opportunity applications.
     */
    public function listApplications()
    {
        try {
            $applications = \App\Models\JobOpportunityApplication::with([
                'offer' => function ($q) {
                    $q->select('id', 'title', 'company_id')->with('company:id,name,logo');
                },
                'user' => function ($q) {
                    $q->select('id', 'email', 'person_id')->with('person:id,names,phone');
                }
            ])->latest()->get();

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
     * Update application status and feedback.
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:postulated,under_review,accepted,rejected',
            'feedback' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $app = \App\Models\JobOpportunityApplication::findOrFail($id);
            $app->status = $request->status;
            $app->feedback = $request->feedback;
            $app->feedback_date = now();
            $app->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado de postulación actualizado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an application (soft delete).
     */
    public function deleteApplication($id)
    {
        try {
            $app = \App\Models\JobOpportunityApplication::findOrFail($id);
            $app->delete();

            return response()->json([
                'success' => true,
                'message' => 'Postulación eliminada exitosamente.'
            ]);
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
}
