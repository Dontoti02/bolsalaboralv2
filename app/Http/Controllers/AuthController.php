<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->rol_id == 1) {
                return redirect()->intended('/admin/dashboard');
            } elseif (Auth::user()->rol_id == 2) {
                return redirect()->intended('/teacher/dashboard');
            } elseif (Auth::user()->rol_id == 3) {
                return redirect()->intended('/student/dashboard');
            } elseif (Auth::user()->rol_id == 4) {
                return redirect()->intended('/company/dashboard');
            }
        }
        try {
            $config = \Illuminate\Support\Facades\DB::table('system_configuration')->pluck('value', 'key')->all();
            $companies = \Illuminate\Support\Facades\DB::table('job_opportunity_company')
                ->select('name', 'logo', 'website')
                ->whereNull('deleted_at')
                ->get();
        } catch (\Exception $e) {
            $config = [];
            $companies = collect();
        }
        return view('login', compact('config', 'companies'));
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $identifier = trim((string) $request->input('login', $request->input('email', '')));
        $request->merge(['login' => $identifier]);

        $validator = Validator::make($request->all(), [
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'login.required' => 'Ingrese su DNI, RUC o correo electrónico.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->onlyInput('login');
        }

        $throttleKey = Str::lower($identifier) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'login' => "Demasiados intentos fallidos. Intente nuevamente en {$seconds} segundos.",
            ])->onlyInput('login');
        }

        $remember = $request->has('remember');
        $user = $this->findLoginUser($identifier);

        $isPasswordCorrect = false;
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $isPasswordCorrect = true;
            }
        }

        if ($user && $user->is_active && $isPasswordCorrect) {
            RateLimiter::clear($throttleKey);
            Auth::login($user, $remember);
            $request->session()->regenerate();

            // Redirect based on user role
            if ($user->rol_id == 1) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->rol_id == 2) {
                return redirect()->intended('/teacher/dashboard');
            } elseif ($user->rol_id == 3) {
                return redirect()->intended('/student/dashboard');
            } elseif ($user->rol_id == 4) {
                return redirect()->intended('/company/dashboard');
            }

            // If user has some other role that is not permitted
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'login' => 'Acceso denegado. Rol de usuario no autorizado para este portal.',
            ])->onlyInput('login');
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'login' => 'Las credenciales ingresadas no son correctas.',
        ])->onlyInput('login');
    }

    /**
     * Resolve a user by the allowed login identifier.
     */
    private function findLoginUser(string $identifier): ?User
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $identifier)->first();
        }

        if (preg_match('/^\d{8}$/', $identifier)) {
            return User::whereIn('rol_id', [2, 3])
                ->whereHas('person', function ($query) use ($identifier) {
                    $query->where('document_type', 'DNI')
                        ->where('document_number', $identifier);
                })
                ->first();
        }

        if (preg_match('/^\d{11}$/', $identifier)) {
            return User::where('rol_id', 4)
                ->whereHas('company', function ($query) use ($identifier) {
                    $query->where('ruc', $identifier);
                })
                ->first();
        }

        return null;
    }

    /**
     * Handle company registration.
     */
    public function registerCompany(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ruc' => 'required|string|size:11|unique:job_opportunity_company,ruc',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
        ], [
            'name.required' => 'El nombre de la empresa es obligatorio.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.size' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.unique' => 'Este RUC ya está registrado.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe incluir al menos una letra.',
            'password.mixed' => 'La contraseña debe incluir mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Create company with basic data
            $company = \App\Models\Company::create([
                'name' => $request->name,
                'ruc' => $request->ruc,
                'email' => $request->email,
                'phone' => '',
                'mailbox' => $request->email,
                'is_verified' => false,
            ]);

            // Create user for the company with the password registered by the company.
            $user = new \App\Models\User();
            $user->company_id = $company->id;
            $user->rol_id = 4; // Empresa
            $user->email = $request->email;
            $user->password = $request->password;
            $user->is_active = true;
            $user->attempts = 0;
            $user->save();

            // Insert into legacy/related role_user table
            \Illuminate\Support\Facades\DB::table('rol_user')->insert([
                'rol_id' => 4,
                'user_id' => $user->id
            ]);

            \Illuminate\Support\Facades\DB::commit();

            // Log the user in directly
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Empresa registrada y autenticada exitosamente.'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error durante el registro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Send password reset link to user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:user,email'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'Ingrese un correo electrónico válido.',
            'email.exists'   => 'No encontramos ningún usuario con ese correo electrónico.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $token = Str::random(64);
            
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            $link = route('password.reset', ['token' => $token]) . '?email=' . urlencode($request->email);

            \Illuminate\Support\Facades\Mail::send('emails.password_reset', ['link' => $link], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Restablecer Contraseña - Bolsa Laboral');
            });

            return response()->json([
                'success' => true,
                'message' => 'Hemos enviado un enlace de recuperación a su correo electrónico.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al enviar el correo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show password reset form.
     */
    public function showResetForm(Request $request, $token)
    {
        try {
            $config = \Illuminate\Support\Facades\DB::table('system_configuration')->pluck('value', 'key')->all();
        } catch (\Exception $e) {
            $config = [];
        }
        return view('reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
            'config' => $config
        ]);
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'    => ['required'],
            'email'    => ['required', 'email', 'exists:user,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'token.required'    => 'El token es obligatorio.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Ingrese un correo electrónico válido.',
            'email.exists'      => 'No encontramos ningún usuario con ese correo electrónico.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'=> 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'El enlace de recuperación es inválido o ha expirado.'
                ], 400);
            }

            // Verify token match
            if (!Hash::check($request->token, $record->token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El token de recuperación es inválido.'
                ], 400);
            }

            // Check if token is older than 60 minutes
            if (now()->subMinutes(60)->gt($record->created_at)) {
                \Illuminate\Support\Facades\DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'El enlace de recuperación ha expirado.'
                ], 400);
            }

            // Update user password
            \Illuminate\Support\Facades\DB::table('user')
                ->where('email', $request->email)
                ->update([
                    'password' => Hash::make($request->password)
                ]);

            // Delete token
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Su contraseña ha sido restablecida con éxito.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al restablecer la contraseña: ' . $e->getMessage()
            ], 500);
        }
    }
}
