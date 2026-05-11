<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Mas Que Perros' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/dashboard-admin-v2.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blue: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body>
    @include('partials.page-loader')
    @php
        use Illuminate\Support\Str;
        $user = Auth::user();
    @endphp
    <div class="admin-layout">
        @include('partials.admin-sidebar')

        <main class="admin-main">
            @include('partials.mq-topbar', [
                'user' => $user,
                'roleLabel' => 'Administrador',
                'profileUrl' => route('admin.settings'),
                'settingsUrl' => route('admin.settings'),
                'helpUrl' => route('admin.dashboard'),
                'notificationsUrl' => route('admin.dashboard'),
                'notifCount' => 2,
            ])

            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
