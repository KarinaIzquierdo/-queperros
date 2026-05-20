<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel Administrativo - Galería Pública</title>

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
        
        <style>
            /* Reset tailwind base to avoid conflicts with existing dashboard styles */
            .admin-main img { display: inline-block; }
            .admin-main button { background-image: none; }
        </style>
    </head>
    <body>
        @include('partials.page-loader')
        <div class="admin-layout">
            @include('partials.admin-sidebar')

            <main class="admin-main">
                @include('partials.mq-topbar', [
                    'user' => Auth::user(), 
                    'roleLabel' => 'Administrador',
                    'profileUrl' => route('admin.settings'),
                    'settingsUrl' => route('admin.settings'),
                    'helpUrl' => route('admin.dashboard'),
                    'notificationsUrl' => route('admin.dashboard'),
                    'notifCount' => 0,
                ])

                <div class="p-8">
                    <section class="ad2-hero mb-8" aria-label="Bienvenida" style="padding: 2rem; min-height: auto;">
                        <div class="ad2-hero-left">
                            <h1 class="ad2-hero-title">Gestión de Galería</h1>
                            <p class="ad2-hero-kicker"><span class="ad2-hero-kicker-icon">📸</span> Galería Pública</p>
                            <p class="ad2-hero-sub">Sube las fotos que todos los visitantes podrán ver en la web.</p>
                        </div>
                    </section>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Formulario de Subida -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-10">
                        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div class="border-3 border-dashed border-purple-200 rounded-2xl p-10 text-center hover:border-purple-400 transition-all bg-purple-50 group">
                                <input type="file" name="photos[]" id="photos" multiple class="hidden" accept="image/*" onchange="previewImages()">
                                <label for="photos" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                            <i class="bi bi-cloud-upload-fill text-3xl"></i>
                                        </div>
                                        <span class="text-purple-700 font-bold text-xl mb-1">Haz clic para seleccionar fotos</span>
                                        <span class="text-purple-400 text-sm font-medium uppercase tracking-wider">Formatos: PNG, JPG, WebP (Máx 5MB)</span>
                                    </div>
                                </label>
                            </div>
                            
                            <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6"></div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-10 py-4 rounded-xl font-bold transition-all shadow-lg hover:shadow-purple-200 flex items-center gap-3 active:scale-95">
                                    <i class="bi bi-send-fill"></i>
                                    Publicar en la Web
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Listado de Fotos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @forelse($photos as $photo)
                            <div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 transition-all hover:shadow-xl hover:-translate-y-1">
                                <div class="aspect-square overflow-hidden">
                                    <img src="{{ $photo['url'] }}" alt="{{ $photo['name'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center p-4">
                                    <form action="{{ route('admin.gallery.destroy', $photo['name']) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta foto de la galería pública?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white w-14 h-14 rounded-2xl shadow-2xl transition-all transform hover:scale-110 flex items-center justify-center">
                                            <i class="bi bi-trash3-fill text-2xl"></i>
                                        </button>
                                    </form>
                                    <span class="text-white text-xs mt-4 font-medium truncate w-full text-center">{{ $photo['name'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-images text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-xl font-bold text-gray-400">La galería está vacía</p>
                                <p class="text-gray-400 mt-1">Sube fotos para mostrar el amor por los peluditos.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>

        <script>
        function previewImages() {
            const preview = document.getElementById('preview-container');
            const files = document.getElementById('photos').files;
            preview.innerHTML = '';

            if (files) {
                [].forEach.call(files, readAndPreview);
            }

            function readAndPreview(file) {
                if (!/\.(jpe?g|png|gif|webp)$/i.test(file.name)) {
                    return;
                }
                
                const reader = new FileReader();
                reader.addEventListener("load", function() {
                    const div = document.createElement('div');
                    div.className = 'relative mt-4 rounded-xl overflow-hidden border-2 border-purple-200 shadow-md aspect-square animate-pulse';
                    div.innerHTML = `<img src="${this.result}" class="w-full h-full object-cover">`;
                    preview.appendChild(div);
                    setTimeout(() => div.classList.remove('animate-pulse'), 500);
                }, false);
                
                reader.readAsDataURL(file);
            }
        }
        </script>
    </body>
</html>
