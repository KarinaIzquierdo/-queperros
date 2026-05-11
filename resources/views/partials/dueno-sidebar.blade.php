@php
    use Illuminate\Support\Str;
    $user = auth()->user();
@endphp

<aside class="mq-dashboard-sidebar" id="mqSidebar">
    <div class="mq-side-top">
        <div class="mq-side-brand">
            <div class="mq-side-badge">
                <img src="{{ asset('img/logo_qperros.png') }}" alt="Logo" />
            </div>
            <div class="mq-side-brand-text">
                <div class="mq-side-brand-title">MAS QUE</div>
                <div class="mq-side-brand-title">PERROS</div>
                <div class="mq-side-brand-sub">Panel</div>
            </div>
        </div>

        <div class="mq-side-user">
            <div class="mq-side-avatar">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</div>
            <div class="mq-side-user-name">{{ Str::upper(Str::before($user->name, ' ')) }}</div>
            <div class="mq-side-user-role">Propietario</div>
        </div>
    </div>

    <div class="mq-side-section">MENU PRINCIPAL</div>
    <nav class="mq-side-menu">
        <a href="{{ route('dashboard') }}" class="mq-side-item {{ request()->routeIs('dashboard') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-house-door" aria-hidden="true"></i>
                <span>Dashboard</span>
            </span>
            @if (request()->routeIs('dashboard'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('owner.pets') }}" class="mq-side-item {{ request()->routeIs('owner.pets') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="fa-solid fa-bone" aria-hidden="true"></i>
                <span>Mis Perros</span>
            </span>
            @if (request()->routeIs('owner.pets'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('owner.services') }}" class="mq-side-item {{ request()->routeIs('owner.services') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-bag" aria-hidden="true"></i>
                <span>Servicios</span>
            </span>
            @if (request()->routeIs('owner.services'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('owner.reservas') }}" class="mq-side-item {{ request()->routeIs('owner.reservas') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-calendar-check" aria-hidden="true"></i>
                <span>Reservas</span>
            </span>
            @if (request()->routeIs('owner.reservas'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('owner.seguimiento') }}" class="mq-side-item {{ request()->routeIs('owner.seguimiento') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-graph-up" aria-hidden="true"></i>
                <span>Seguimiento</span>
            </span>
            @if (request()->routeIs('owner.seguimiento'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('owner.pagos') }}" class="mq-side-item {{ request()->routeIs('owner.pagos') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-credit-card" aria-hidden="true"></i>
                <span>Pagos</span>
            </span>
            @if (request()->routeIs('owner.pagos'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
    </nav>

    <div class="mq-side-section">OTROS</div>
    <nav class="mq-side-menu">
        <a href="{{ route('owner.planpadrino') }}" class="mq-side-item {{ request()->routeIs('owner.planpadrino') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-heart" aria-hidden="true"></i>
                <span>Plan Padrino</span>
            </span>
            @if (request()->routeIs('owner.planpadrino'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('owner.perfil') }}" class="mq-side-item {{ request()->routeIs('owner.perfil') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-person" aria-hidden="true"></i>
                <span>Perfil</span>
            </span>
            @if (request()->routeIs('owner.perfil'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('owner.chat') }}" class="mq-side-item {{ request()->routeIs('owner.chat') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-chat-dots" aria-hidden="true"></i>
                <span>Chat</span>
            </span>
            @if (request()->routeIs('owner.chat'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('owner.notificaciones') }}" class="mq-side-item {{ request()->routeIs('owner.notificaciones') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-bell" aria-hidden="true"></i>
                <span>Notificaciones</span>
            </span>
            @if (request()->routeIs('owner.notificaciones'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('owner.galeria') }}" class="mq-side-item {{ request()->routeIs('owner.galeria') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-images" aria-hidden="true"></i>
                <span>Galería</span>
            </span>
            @if (request()->routeIs('owner.galeria'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
    </nav>

    <div class="mq-side-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mq-side-item mq-side-logout" style="width: 100%; border: none; background: none; cursor: pointer;">
                <span class="mq-side-left">
                    <i class="bi bi-box-arrow-left" aria-hidden="true"></i>
                    <span>Cerrar Sesión</span>
                </span>
            </button>
        </form>
    </div>
</aside>
