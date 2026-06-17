@extends('layouts.app')

@section('title', 'Acceso al Panel')

@push('styles')
<style>
    /* Estilos específicos para la tarjeta de Login */
    .login-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }
    
    /* Reutilizamos el diseño de círculo centrado para el icono del candado */
    .login-circle-icon {
        width: 70px;
        height: 70px;
        background-color: #eef2ff;
        color: #4f46e5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
    }
    
    .login-circle-icon i {
        font-size: 1.75rem;
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-lg-5 col-xl-4">
            
            <div class="card login-card p-4">
                <div class="card-body">
                    
                    <!-- Icono Decorativo Superior -->
                    <div class="login-circle-icon">
                        <i class="bi bi-lock-fill"></i>
                    </div>

                    <h3 class="text-center fw-bold mb-1">Panel de Control</h3>
                    <p class="text-center text-muted small mb-4">Ingresa tus credenciales de administrador</p>

                    <!-- Formulario Seguro -->
                    <form action="{{ route('admin.login.submit') }}" method="POST" id="loginForm">
                        @csrf

                        <!-- Campo: Correo Electrónico -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control bg-light border-start-0 @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       required
                                       placeholder="admin@cocinaencasa.com"
                                       autocomplete="email"
                                       autofocus>
                            </div>
                        </div>

                        <!-- Campo: Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold small">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-key"></i></span>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control bg-light border-start-0"
                                       required
                                       placeholder="••••••••"
                                       autocomplete="current-password">
                            </div>
                        </div>

                        <!-- Opción: Recordarme -->
                        <div class="form-check mb-4 mt-2">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-input-label small text-muted user-select-none" for="remember">
                                Recordar sesión en este equipo
                            </label>
                        </div>

                        <!-- Botón de Envío con Efecto de Carga -->
                        <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                            Iniciar Sesión <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Conectamos el formulario con tu función global window.showLoadingModal de app.js
    document.getElementById('loginForm').addEventListener('submit', function() {
        showLoadingModal('Verificando acceso', 'Por favor, espere un momento...');
    });
</script>
@endpush
