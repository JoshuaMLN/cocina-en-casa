@extends('layouts.app')

@section('title', 'Panel de Control')

@push('styles')
    @vite('resources/css/admin/index.css')
@endpush

@section('content')

<div class="container mt-5 mb-5">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="mb-0">Panel de Administración</h2>
        
        <!-- Formulario Seguro de Cierre de Sesión -->
        <form action="{{ route('admin.logout') }}" method="POST" id="logoutForm">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold py-2 px-3">
                Cerrar Sesión <i class="bi bi-box-arrow-right ms-1"></i>
            </button>
        </form>
    </div>
    <hr>

    <div id="tabs-spinner" class="d-flex justify-content-center align-items-center my-3">
        <output class="spinner-border text-primary">
            <span class="visually-hidden">Cargando...</span>
        </output>
    </div>

    <div id="tabs-wrapper" class="d-none">
        <div class="nav nav-tabs" id="adminTabs" role="tablist">
            <button type="button" class="nav-link active"
                data-bs-toggle="tab"
                data-bs-target="#general"
                role="tab"
                aria-controls="general"
                aria-selected="true">
                General
            </button>

            <button type="button" class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#platos"
                role="tab"
                aria-controls="platos"
                aria-selected="false">
                Platos
            </button>
        </div>

        <div class="tab-content pt-4">
            <div class="tab-pane fade show active" id="general">
                @include('admin.tabs.general')
            </div>
            <div class="tab-pane fade" id="platos">
                @include('admin.tabs.platos')
            </div>
        </div>

    </div>

</div>

{{-- MODAL DE CROPPER --}}
@include('admin.partials.crop-modal')

@endsection

@push('scripts')
    @vite('resources/js/admin/index.js')
@endpush
