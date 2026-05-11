@php
    use Illuminate\Support\Str;
    $user = $user ?? auth()->user();
@endphp

<aside class="mq-dashboard-sidebar et-sidebar">
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
            <div class="mq-side-avatar">{{ strtoupper(mb_substr($user->name ?? 'E', 0, 1)) }}</div>
            <div class="mq-side-user-name">{{ Str::upper(Str::before($user->name ?? 'Entrenador', ' ')) }}</div>
            <div class="mq-side-user-role">Entrenador</div>
        </div>
    </div>

    <div class="mq-side-section">MENU PRINCIPAL</div>
    <nav class="mq-side-menu" aria-label="Menú entrenador">
        <a href="{{ route('entrenador.dashboard') }}" class="mq-side-item {{ request()->routeIs('entrenador.dashboard') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-house-door" aria-hidden="true"></i>
                <span>Dashboard</span>
            </span>
            @if (request()->routeIs('entrenador.dashboard'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('entrenador.seguimiento') }}" class="mq-side-item {{ request()->routeIs('entrenador.seguimiento') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-graph-up" aria-hidden="true"></i>
                <span>Seguimiento</span>
            </span>
            @if (request()->routeIs('entrenador.seguimiento'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('entrenador.horario') }}" class="mq-side-item {{ request()->routeIs('entrenador.horario') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-calendar2-week" aria-hidden="true"></i>
                <span>Mi horario</span>
            </span>
            @if (request()->routeIs('entrenador.horario'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('entrenador.chat') }}" class="mq-side-item {{ request()->routeIs('entrenador.chat') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-chat-dots" aria-hidden="true"></i>
                <span>Chat</span>
            </span>
            @if (request()->routeIs('entrenador.chat'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('entrenador.notificaciones') }}" class="mq-side-item {{ request()->routeIs('entrenador.notificaciones') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-bell" aria-hidden="true"></i>
                <span>Notificaciones</span>
            </span>
            @if (request()->routeIs('entrenador.notificaciones'))
                <span class="mq-side-active-dot" aria-hidden="true"></span>
            @endif
        </a>
        <a href="{{ route('entrenador.perfil') }}" class="mq-side-item {{ request()->routeIs('entrenador.perfil') ? 'mq-side-item--active' : '' }}">
            <span class="mq-side-left">
                <i class="bi bi-person" aria-hidden="true"></i>
                <span>Mi Perfil</span>
            </span>
            @if (request()->routeIs('entrenador.perfil'))
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
