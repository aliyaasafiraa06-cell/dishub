<div class="sidebar">
    <div class="logo-area">
        <i class="bi bi-bus-front-fill"></i> Dishub Surakarta
    </div>

    <nav class="nav flex-column">
        {{-- Menu Dashboard --}}
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="px-4 mt-3 mb-1">
            <small class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 1px;">Layanan</small>
        </div>

        {{-- Menu Angkutan Listrik --}}
        <a class="nav-link {{ request()->is('layanan/angkutan-listrik*') ? 'active' : '' }}" href="{{ route('layanan.show', 'angkutan-listrik') }}">
            <i class="bi bi-ev-front"></i> Angkutan Listrik
        </a>

        {{-- Menu Bus Tingkat --}}
        <a class="nav-link {{ request()->is('layanan/bus-tingkat*') ? 'active' : '' }}" href="{{ route('layanan.show', 'bus-tingkat') }}">
            <i class="bi bi-bus-front"></i> Bus Tingkat
        </a>

        {{-- Menu Bus Edukasi --}}
        <a class="nav-link {{ request()->is('layanan/bus-edukasi*') ? 'active' : '' }}" href="{{ route('layanan.show', 'bus-edukasi') }}">
            <i class="bi bi-mortarboard"></i> Bus Edukasi
        </a>

        {{-- Menu Kereta Jaladara --}}
        <a class="nav-link {{ request()->is('layanan/kereta-jaladara*') ? 'active' : '' }}" href="{{ route('layanan.show', 'kereta-jaladara') }}">
            <i class="bi bi-train-front"></i> Kereta Jaladara
        </a>
    </nav>

    <div class="mt-auto pt-4 border-top mx-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 border-0 bg-transparent text-danger px-0 text-start">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>
