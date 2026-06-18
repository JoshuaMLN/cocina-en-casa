<nav class="navbar navbar-light bg-white shadow-sm fixed-top"
    data-smart-navbar>
    <div class="container d-flex justify-content-between flex-nowrap gap-3">
        <a class="navbar-brand fw-bold fs-4 d-flex align-items-center mb-0"
            href="/">
            @if($logo)
                <img
                    src="{{ asset('storage/' . $logo) }}"
                    alt="Logo de Cocina en Casa"
                    height="40"
                    class="me-3 object-fit-contain">
            @else
                <i class="fa-solid fa-utensils text-color me-3 fs-1"></i>
            @endif

            <span>
                Cocina <span class="text-color">en Casa</span>
                <small class="d-none d-sm-inline text-muted fw-normal ms-2">
                    Administración
                </small>
            </span>
        </a>

        <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit"
                class="btn btn-outline-danger btn-sm fw-semibold py-2 px-3 text-nowrap">
                <span class="d-none d-sm-inline">Cerrar sesión</span>
                <i class="bi bi-box-arrow-right ms-sm-1"></i>
            </button>
        </form>
    </div>
</nav>
