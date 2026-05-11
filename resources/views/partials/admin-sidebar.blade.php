<aside class="admin-sidebar">
    <div class="admin-brand">
        <div class="admin-logo">
            <img src="{{ asset('img/logo_qperros.png') }}?v={{ time() }}" alt="Logo" style="width: 80%;height: 100%;object-fit: contain;background: white;border-radius: 30%;padding: -1px;">
        </div>
        <div class="admin-brand-text">
            <span class="admin-brand-title">MAS QUE PERROS</span>
            <span class="admin-brand-subtitle">Panel Administrativo</span>
        </div>
    </div>

    <p class="admin-sidebar-section">ADMINISTRACION</p>

    <nav class="admin-menu">
        <a href="{{ route('admin.dashboard') }}" class="admin-menu-item {{ request()->routeIs('admin.dashboard') ? 'admin-menu-item--active' : '' }}">
            <span class="admin-menu-left">
                <div class="admin-menu-icon-wrapper">
                    <i class="admin-menu-icon bi bi-grid-1x2-fill" aria-hidden="true"></i>
                </div>
                <span>Dashboard</span>
            </span>
            <span class="admin-menu-right">
                <span class="admin-menu-chevron">›</span>
            </span>
        </a>
        <a href="{{ route('admin.users') }}" class="admin-menu-item {{ request()->routeIs('admin.users') ? 'admin-menu-item--active' : '' }}">
            <span class="admin-menu-left">
                <div class="admin-menu-icon-wrapper">
                    <i class="admin-menu-icon bi bi-people-fill" aria-hidden="true"></i>
                </div>
                <span>Gestión de usuarios</span>
            </span>
            <span class="admin-menu-right">
                <span class="admin-menu-badge">5</span>
            </span>
        </a>
        <a href="{{ route('admin.services') }}" class="admin-menu-item {{ request()->routeIs('admin.services') ? 'admin-menu-item--active' : '' }}">
            <span class="admin-menu-left">
                <div class="admin-menu-icon-wrapper">
                    <i class="admin-menu-icon bi bi-heart-pulse-fill" aria-hidden="true"></i>
                </div>
                <span>Gestión de servicios</span>
            </span>
            <span class="admin-menu-right">
                <span class="admin-menu-badge">6</span>
            </span>
        </a>
        <a href="{{ route('admin.pets') }}" class="admin-menu-item {{ request()->routeIs('admin.pets') ? 'admin-menu-item--active' : '' }}">
            <span class="admin-menu-left">
                <div class="admin-menu-icon-wrapper">
                    <i class="admin-menu-icon fa-sharp fa-solid fa-shield-dog"  aria-hidden="true"></i>
                </div>
                <span>Gestión de mascotas</span>
            </span>
            <span class="admin-menu-right"></span>
        </a>
        <a href="{{ route('admin.settings') }}" class="admin-menu-item {{ request()->routeIs('admin.settings') ? 'admin-menu-item--active' : '' }}">
            <span class="admin-menu-left">
                <div class="admin-menu-icon-wrapper">
                    <i class="admin-menu-icon bi bi-gear-fill" aria-hidden="true"></i>
                </div>
                <span>Configuracion</span>
            </span>
            <span class="admin-menu-right"></span>
        </a>
    </nav>

    <div class="admin-sidebar-spacer"></div>

    <div class="admin-sidebar-divider"></div>

    <form method="POST" action="{{ route('logout') }}" class="admin-logout">
        @csrf
        <button type="submit">
            <i class="admin-logout-icon bi bi-box-arrow-left" aria-hidden="true"></i>
            <span>Cerrar sesion</span>
        </button>
    </form>
</aside>
