@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 flex items-center gap-3">
            <i class="bi bi-gear-fill text-blue-600"></i>
            Ajustes del Negocio
        </h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información General -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Información General</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre del Negocio</label>
                        <input type="text" name="nombre_negocio" value="{{ old('nombre_negocio', $settings->nombre_negocio) }}" 
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion', $settings->direccion) }}" 
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $settings->telefono) }}" 
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $settings->email) }}" 
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Horarios -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Horarios</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hora Apertura</label>
                            <input type="time" name="hora_apertura" value="{{ old('hora_apertura', $settings->hora_apertura) }}" 
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hora Cierre</label>
                            <input type="time" name="hora_cierre" value="{{ old('hora_cierre', $settings->hora_cierre) }}" 
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <input type="checkbox" name="atiende_fines_semana" value="1" id="atiende_fines_semana" 
                            {{ old('atiende_fines_semana', $settings->atiende_fines_semana) ? 'checked' : '' }}
                            class="rounded text-blue-600 focus:ring-blue-500 h-5 w-5">
                        <label for="atiende_fines_semana" class="text-sm font-medium text-gray-700">Atiende fines de semana</label>
                    </div>
                </div>
            </div>

            <!-- Preferencias y Sistema -->
            <div class="space-y-6 pt-6">
                <h2 class="text-xl font-semibold text-gray-800 border-b pb-2">Preferencias de Sistema</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <span class="text-sm font-medium text-gray-700">Notificaciones Email</span>
                            <input type="checkbox" name="notificaciones_email" value="1" 
                                {{ old('notificaciones_email', $settings->notificaciones_email) ? 'checked' : '' }}
                                class="rounded text-blue-600 focus:ring-blue-500 h-5 w-5">
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <span class="text-sm font-medium text-gray-700">Notificaciones SMS</span>
                            <input type="checkbox" name="notificaciones_sms" value="1" 
                                {{ old('notificaciones_sms', $settings->notificaciones_sms) ? 'checked' : '' }}
                                class="rounded text-blue-600 focus:ring-blue-500 h-5 w-5">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <span class="text-sm font-medium text-gray-700">Recordatorio Citas</span>
                            <input type="checkbox" name="recordatorio_citas" value="1" 
                                {{ old('recordatorio_citas', $settings->recordatorio_citas) ? 'checked' : '' }}
                                class="rounded text-blue-600 focus:ring-blue-500 h-5 w-5">
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <span class="text-sm font-medium text-gray-700">2FA (Autenticación 2 factores)</span>
                            <input type="checkbox" name="autenticacion_dos_factores" value="1" 
                                {{ old('autenticacion_dos_factores', $settings->autenticacion_dos_factores) ? 'checked' : '' }}
                                class="rounded text-blue-600 focus:ring-blue-500 h-5 w-5">
                        </div>
                    </div>
                </div>

                <div class="max-w-xs">
                    <label class="block text-sm font-medium text-gray-700">Cierre de sesión automático (minutos)</label>
                    <input type="number" name="cierre_sesion_minutos" value="{{ old('cierre_sesion_minutos', $settings->cierre_sesion_minutos) }}" 
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="pt-6 border-t flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg shadow-blue-200">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
