<div class="topbar">
    <div class="d-flex align-items-center">
        <h5 class="mb-0 text-dark fw-bold">@yield('page_title', 'Dashboard')</h5>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="text-end">
            <div class="fw-bold text-dark">{{ Auth::user()->name ?? 'Admin' }}</div>
            <small class="text-muted">{{ Auth::user()->role ?? 'Administrator' }}</small>
        </div>
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
        </div>
    </div>
</div>
