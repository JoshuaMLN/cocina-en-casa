<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    // El nombre del comando que escribirás en la terminal
    protected $signature = 'make:admin';

    // La descripción que sale cuando ejecutas php artisan list
    protected $description = 'Crea un nuevo usuario administrador para el panel';

    public function handle()
    {
        $this->info('--- CREADOR DE ADMINISTRADORES: COCINA EN CASA ---');

        // 1. Solicitar el nombre
        $name = $this->ask('Introduce el nombre del administrador');
        if (empty($name)) {
            $this->error('El nombre no puede estar vacío.');
            return Command::FAILURE;
        }

        // 2. Solicitar el correo electrónico
        $email = $this->ask('Introduce el correo electrónico');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('El formato del correo electrónico no es válido.');
            return Command::FAILURE;
        }

        // Verificar si el correo ya existe en la tabla admins
        if (Admin::where('email', $email)->exists()) {
            $this->error('Este correo electrónico ya está registrado.');
            return Command::FAILURE;
        }

        // 3. Solicitar la contraseña de forma oculta (no se verá mientras escribes)
        $password = $this->secret('Introduce la contraseña para el acceso');
        if (strlen($password) < 6) {
            $this->error('La contraseña debe tener al menos 6 caracteres.');
            return Command::FAILURE;
        }

        // 4. Crear el registro en la base de datos
        Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("¡Éxito! El administrador '{$name}' ha sido creado correctamente.");
        return Command::SUCCESS;
    }
}
