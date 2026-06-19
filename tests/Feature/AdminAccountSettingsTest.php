<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_unlock_account_settings(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin, 'admin')
            ->postJson(route('admin.settings.unlock'), [
                'current_password' => 'Current123',
            ]);

        $response
            ->assertOk()
            ->assertSessionHas('admin.settings_verified_at');
    }

    public function test_unlock_rejects_an_invalid_password(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin, 'admin')
            ->postJson(route('admin.settings.unlock'), [
                'current_password' => 'Incorrect123',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('current_password');
    }

    public function test_admin_can_update_email_after_unlocking(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin, 'admin')
            ->withSession([
                'admin.settings_verified_at' => now()->timestamp,
            ])
            ->patch(route('admin.settings.email'), [
                'current_password' => 'Current123',
                'email' => 'nuevo@example.com',
                'email_confirmation' => 'nuevo@example.com',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertSame(
            'nuevo@example.com',
            $admin->fresh()->email
        );
    }

    public function test_password_change_logs_the_admin_out(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin, 'admin')
            ->withSession([
                'admin.settings_verified_at' => now()->timestamp,
            ])
            ->patch(route('admin.settings.password'), [
                'current_password' => 'Current123',
                'password' => 'NuevaClave456',
                'password_confirmation' => 'NuevaClave456',
            ]);

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
        $this->assertTrue(
            Hash::check('NuevaClave456', $admin->fresh()->password)
        );
    }

    private function createAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => 'Current123',
        ]);
    }
}
