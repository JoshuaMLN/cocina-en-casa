@extends('layouts.app')

@section('title', 'Panel de Control')

@section('navbar')
    @include('admin.partials.navbar')
@endsection

@push('styles')
    @vite('resources/css/admin/index.css')
@endpush

@section('content')

<div class="container mt-5 mb-5">

    <h2 class="mb-0">Panel de Administración</h2>
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

            <button type="button" class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#solicitudes"
                role="tab"
                aria-controls="solicitudes"
                aria-selected="false">
                Solicitudes
                <span id="solicitudesUnreadBadge"
                    class="badge rounded-pill text-bg-danger ms-1 {{ $solicitudesNoLeidas === 0 ? 'd-none' : '' }}"
                    data-count="{{ $solicitudesNoLeidas }}">
                    {{ $solicitudesNoLeidas }}
                </span>
            </button>

            <button type="button" class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#configuracion"
                data-admin-settings-tab
                data-settings-unlocked="{{ $adminSettingsUnlocked ? 'true' : 'false' }}"
                role="tab"
                aria-controls="configuracion"
                aria-selected="false">
                <i class="fa-solid fa-gear me-1"></i>
                Configuración
                <i class="fa-solid fa-lock ms-1 settings-tab-lock {{ $adminSettingsUnlocked ? 'd-none' : '' }}"
                    data-settings-tab-lock
                    aria-hidden="true"></i>
            </button>
        </div>

        <div class="tab-content pt-4">
            <div class="tab-pane fade show active" id="general">
                @include('admin.tabs.general')
            </div>
            <div class="tab-pane fade" id="platos">
                @include('admin.tabs.platos')
            </div>
            <div class="tab-pane fade" id="solicitudes">
                <div data-solicitudes-inbox
                    data-snapshot-url="{{ route('admin.solicitudes.snapshot') }}"
                    data-latest-id="{{ $solicitudesLatestId }}"
                    data-total="{{ $solicitudes->total() }}"
                    data-unread="{{ $solicitudesNoLeidas }}"
                    data-poll-interval="30000">
                    @include('admin.tabs.solicitudes')
                </div>
            </div>
            <div class="tab-pane fade" id="configuracion">
                @include('admin.tabs.configuracion')
            </div>
        </div>

    </div>

</div>

{{-- MODAL DE CROPPER --}}
@include('admin.partials.crop-modal')
@include('admin.partials.settings-unlock-modal')

@endsection

@push('scripts')
    @vite('resources/js/admin/index.js')
@endpush
