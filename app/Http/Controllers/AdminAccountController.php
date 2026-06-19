<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminAccountController extends Controller
{
    private const SETTINGS_UNLOCK_MINUTES = 10;

    public function unlock(Request $request): JsonResponse
    {
        $data = $request->validate(
            ['current_password' => ['required', 'string']],
            ['current_password.required' => 'Ingresa tu contraseña actual.']
        );

        $admin = $request->user('admin');

        if (! Hash::check($data['current_password'], $admin->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'La contraseña actual no es correcta.',
            ]);
        }

        $request->session()->put(
            'admin.settings_verified_at',
            now()->timestamp
        );

        return response()->json([
            'success' => true,
            'expires_in' => self::SETTINGS_UNLOCK_MINUTES * 60,
        ]);
    }

    public function updateEmail(Request $request): RedirectResponse
    {
        $this->ensureSettingsUnlocked($request);

        $admin = $request->user('admin');

        $data = $request->validateWithBag('adminEmail', [
            'current_password' => ['required', 'string'],
            'email' => [
                'required',
                'email:rfc',
                'max:150',
                'confirmed',
                Rule::unique(Admin::class, 'email')->ignore($admin->id),
            ],
        ], [
            'current_password.required' => 'Ingresa tu contraseña actual.',
            'email.required' => 'Ingresa el nuevo correo.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.confirmed' => 'Los correos no coinciden.',
            'email.unique' => 'Este correo ya está registrado.',
        ]);

        $this->ensureCurrentPassword(
            $data['current_password'],
            $admin,
            'adminEmail'
        );

        $admin->update([
            'email' => mb_strtolower(trim($data['email'])),
        ]);

        return back()->with(
            'success',
            'Correo del administrador actualizado correctamente.'
        );
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $this->ensureSettingsUnlocked($request);

        $admin = $request->user('admin');

        $data = $request->validateWithBag('adminPassword', [
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                'string',
                'min:8',
                function (
                    string $attribute,
                    mixed $value,
                    \Closure $fail
                ): void {
                    if (! preg_match('/\pL/u', (string) $value)) {
                        $fail('La nueva contraseña debe contener al menos una letra.');
                    }

                    if (! preg_match('/\d/', (string) $value)) {
                        $fail('La nueva contraseña debe contener al menos un número.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Ingresa tu contraseña actual.',
            'password.required' => 'Ingresa una nueva contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.different' => 'La nueva contraseña debe ser diferente a la actual.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);

        $this->ensureCurrentPassword(
            $data['current_password'],
            $admin,
            'adminPassword'
        );

        $admin->update([
            'password' => $data['password'],
        ]);

        Auth::guard('admin')->logoutOtherDevices($data['password']);
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with(
                'success',
                'Contraseña actualizada. Inicia sesión nuevamente.'
            );
    }

    private function ensureSettingsUnlocked(Request $request): void
    {
        $verifiedAt = (int) $request->session()->get(
            'admin.settings_verified_at',
            0
        );

        if ($verifiedAt < now()->subMinutes(
            self::SETTINGS_UNLOCK_MINUTES
        )->timestamp) {
            $request->session()->forget('admin.settings_verified_at');

            $exception = ValidationException::withMessages([
                'settings' => 'Vuelve a confirmar tu contraseña para continuar.',
            ]);
            $exception->errorBag = 'adminSettings';

            throw $exception;
        }
    }

    private function ensureCurrentPassword(
        string $password,
        Admin $admin,
        string $errorBag
    ): void {
        if (Hash::check($password, $admin->password)) {
            return;
        }

        $exception = ValidationException::withMessages([
            'current_password' => 'La contraseña actual no es correcta.',
        ]);
        $exception->errorBag = $errorBag;

        throw $exception;
    }
}
