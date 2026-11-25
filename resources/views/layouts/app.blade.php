<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dishub Surakarta')</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #F5F7FB; font-family: 'Segoe UI', system-ui, sans-serif; }
        .sidebar { width: 260px; height: 100vh; background-color: #FFFFFF; border-right: 1px solid #E7ECF3; position: fixed; top: 0; left: 0; z-index: 1000; padding: 20px 0; display: flex; flex-direction: column; }
        .main-content { margin-left: 260px; min-height: 100vh; }
        .topbar { height: 70px; background-color: #FFFFFF; border-bottom: 1px solid #E7ECF3; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; position: sticky; top: 0; z-index: 900; }
        .nav-link { color: #182230; padding: 12px 30px; font-weight: 500; display: flex; align-items: center; gap: 12px; transition: all 0.2s; }
        .nav-link:hover, .nav-link.active { background-color: #EAF3FF; color: #2878E8; border-right: 3px solid #2878E8; }
        .nav-link i { font-size: 1.2rem; }
        .logo-area { padding: 0 30px 20px; border-bottom: 1px solid #E7ECF3; margin-bottom: 20px; font-weight: 700; color: #174A8B; font-size: 1.2rem; display: flex; align-items: center; gap: 10px; }
    </style>
    @stack('styles')
</head>
<body>
    @include('components.sidebar')
    <div class="main-content">
        @include('components.topbar')
        <div class="p-4">
                {{-- Notifikasi Sukses / Error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #198754;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border-left: 5px solid #dc3545;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
