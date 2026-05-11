<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerModulesController extends Controller
{
    public function misTareas()
    {
        $user = Auth::user();

        $tasks = [
            [
                'title' => 'Paseo matutino - Max',
                'time' => '08:00 AM',
                'status' => 'alta',
                'done' => true,
            ],
            [
                'title' => 'Entrenamiento obediencia - Luna',
                'time' => '10:00 AM',
                'status' => 'alta',
                'done' => true,
            ],
            [
                'title' => 'Alimentación - Rocky',
                'time' => '12:00 PM',
                'status' => 'media',
                'done' => false,
            ],
            [
                'title' => 'Sesión de socialización',
                'time' => '02:00 PM',
                'status' => 'media',
                'done' => false,
            ],
            [
                'title' => 'Paseo vespertino - Max',
                'time' => '04:00 PM',
                'status' => 'alta',
                'done' => false,
            ],
            [
                'title' => 'Revisión veterinaria - Bella',
                'time' => '05:30 PM',
                'status' => 'baja',
                'done' => false,
            ],
        ];

        return view('entrenador.mistareas', [
            'user' => $user,
            'tasks' => $tasks,
        ]);
    }

    public function mascotasAsignadas()
    {
        $user = Auth::user();

        $pets = [
            [
                'name' => 'Max',
                'breed' => 'Golden Retriever',
                'age' => '3 años',
                'owner' => 'Carlos Rodriguez',
                'phone' => '555-1234',
                'tags' => ['Paseo', 'Entrenamiento'],
            ],
            [
                'name' => 'Luna',
                'breed' => 'Border Collie',
                'age' => '2 años',
                'owner' => 'Maria Garcia',
                'phone' => '555-5678',
                'tags' => ['Entrenamiento', 'Socializacion'],
            ],
            [
                'name' => 'Rocky',
                'breed' => 'Bulldog Frances',
                'age' => '4 años',
                'owner' => 'Ana Martinez',
                'phone' => '555-9012',
                'tags' => ['Cuidado diario', 'Paseo'],
            ],
            [
                'name' => 'Bella',
                'breed' => 'Labrador',
                'age' => '1 año',
                'owner' => 'Pedro Sanchez',
                'phone' => '555-3456',
                'tags' => ['Entrenamiento', 'Paseo'],
            ],
        ];

        return view('entrenador.mascotas', [
            'user' => $user,
            'pets' => $pets,
        ]);
    }

    public function seguimiento()
    {
        $user = Auth::user();

        return view('entrenador.seguimiento', [
            'user' => $user,
        ]);
    }

    public function horario()
    {
        $user = Auth::user();

        // Get trainer availability from database
        $availability = DB::table('trainer_availability')
            ->where('trainer_id', $user->id)
            ->get()
            ->keyBy('day_of_week');

        // Build week schedule with availability and reservations
        $days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        $dayMap = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miercoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sabado', 0 => 'Domingo'];

        $week = [];

        // Get current week dates
        $startOfWeek = now()->startOfWeek();

        foreach ($days as $index => $dayName) {
            $dayOfWeek = $index === 6 ? 0 : $index + 1; // Convert to MySQL day_of_week (0=Sunday)
            $date = $startOfWeek->copy()->addDays($index);

            // Get availability for this day
            $dayAvail = $availability->get($dayOfWeek);
            $startTime = $dayAvail->start_time ?? '08:00:00';
            $endTime = $dayAvail->end_time ?? '22:00:00';
            $isAvailable = $dayAvail->is_available ?? true;

            // Get reservations for this day between 8am-10pm
            $reservations = DB::table('reservas')
                ->where('entrenador_id', $user->id)
                ->where('fecha', $date->format('Y-m-d'))
                ->whereTime('hora', '>=', '08:00:00')
                ->whereTime('hora', '<=', '22:00:00')
                ->orderBy('hora')
                ->get();

            $items = [];
            foreach ($reservations as $r) {
                $items[] = [
                    'time' => substr($r->hora, 0, 5),
                    'pet' => $r->mascota_id ?? 'Mascota',
                    'activity' => $r->servicio ?? 'Servicio',
                    'status' => $r->estado ?? 'pendiente',
                ];
            }

            $week[$dayName] = [
                'available' => $isAvailable,
                'start_time' => substr($startTime, 0, 5),
                'end_time' => substr($endTime, 0, 5),
                'items' => $items,
                'date' => $date->format('d/m'),
            ];
        }

        return view('entrenador.horario', [
            'user' => $user,
            'week' => $week,
        ]);
    }

    public function updateAvailability(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'is_available' => ['required', 'boolean'],
        ]);

        // Validate time range is between 8am and 10pm
        $minTime = '08:00';
        $maxTime = '22:00';

        if ($validated['start_time'] < $minTime || $validated['end_time'] > $maxTime) {
            return back()->withErrors(['time' => 'El horario debe estar entre 08:00 y 22:00']);
        }

        DB::table('trainer_availability')->updateOrInsert(
            [
                'trainer_id' => $user->id,
                'day_of_week' => $validated['day_of_week'],
            ],
            [
                'start_time' => $validated['start_time'] . ':00',
                'end_time' => $validated['end_time'] . ':00',
                'is_available' => $validated['is_available'],
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()->route('entrenador.horario')->with('success', 'Horario actualizado correctamente');
    }

    public function historial()
    {
        $user = Auth::user();

        $records = [
            [
                'date' => '18/03/2026',
                'pet' => 'Max',
                'service' => 'Paseo matutino',
                'duration' => '45 min',
                'notes' => 'Excelente comportamiento',
            ],
            [
                'date' => '18/03/2026',
                'pet' => 'Luna',
                'service' => 'Entrenamiento',
                'duration' => '60 min',
                'notes' => 'Progreso en comandos básicos',
            ],
            [
                'date' => '17/03/2026',
                'pet' => 'Rocky',
                'service' => 'Cuidado diario',
                'duration' => '8 hrs',
                'notes' => 'Día tranquilo',
            ],
            [
                'date' => '17/03/2026',
                'pet' => 'Max',
                'service' => 'Paseo vespertino',
                'duration' => '30 min',
                'notes' => 'Socialización con otros perros',
            ],
            [
                'date' => '16/03/2026',
                'pet' => 'Bella',
                'service' => 'Entrenamiento',
                'duration' => '45 min',
                'notes' => 'Primera sesión completada',
            ],
        ];

        return view('entrenador.historial', [
            'user' => $user,
            'records' => $records,
        ]);
    }

    public function reservas()
    {
        $user = Auth::user();

        $reservas = [
            [
                'id' => 1,
                'pet' => 'Max',
                'owner' => 'Carlos Rodriguez',
                'service' => 'Paseo matutino',
                'date' => '2026-03-24',
                'time' => '08:00',
                'price' => 15000,
                'status' => 'pendiente',
                'comments' => 'Perro muy activo',
            ],
            [
                'id' => 2,
                'pet' => 'Luna',
                'owner' => 'Maria Garcia',
                'service' => 'Entrenamiento avanzado',
                'date' => '2026-03-24',
                'time' => '10:00',
                'price' => 50000,
                'status' => 'pendiente',
                'comments' => 'Trabajar obediencia',
            ],
            [
                'id' => 3,
                'pet' => 'Rocky',
                'owner' => 'Ana Martinez',
                'service' => 'Cuidado diario',
                'date' => '2026-03-25',
                'time' => '09:00',
                'price' => 45000,
                'status' => 'pendiente',
                'comments' => '',
            ],
            [
                'id' => 4,
                'pet' => 'Bella',
                'owner' => 'Pedro Sanchez',
                'service' => 'Entrenamiento básico',
                'date' => '2026-03-26',
                'time' => '14:00',
                'price' => 35000,
                'status' => 'confirmado',
                'comments' => 'Primera sesión',
            ],
        ];

        $counts = [
            'pendientes' => 3,
            'confirmadas' => 1,
            'total' => 4,
        ];

        return view('entrenador.reservas', [
            'user' => $user,
            'reservas' => $reservas,
            'counts' => $counts,
        ]);
    }

    public function chat()
    {
        $user = Auth::user();

        $conversations = [
            [
                'initial' => 'C',
                'name' => 'Carlos Rodriguez',
                'subtitle' => 'Dueño de Max',
                'active' => true,
            ],
            [
                'initial' => 'M',
                'name' => 'Maria Garcia',
                'subtitle' => 'Dueño de Luna',
                'active' => false,
            ],
            [
                'initial' => 'A',
                'name' => 'Ana Martinez',
                'subtitle' => 'Dueño de Rocky',
                'active' => false,
            ],
            [
                'initial' => 'P',
                'name' => 'Pedro Sanchez',
                'subtitle' => 'Dueño de Bella',
                'active' => false,
            ],
        ];

        $active = collect($conversations)->firstWhere('active', true) ?? $conversations[0];

        $messages = [
            [
                'from' => 'owner',
                'text' => 'Hola Juan, como estuvo Max hoy en el paseo?',
            ],
            [
                'from' => 'trainer',
                'text' => 'Hola Carlos! Max estuvo excelente, muy energico y obediente.',
            ],
            [
                'from' => 'owner',
                'text' => 'Que bueno escuchar eso! Ha mejorado mucho con el entrenamiento.',
            ],
            [
                'from' => 'trainer',
                'text' => 'Si, su progreso es muy notable. Mañana trabajaremos en comandos avanzados.',
            ],
        ];

        return view('entrenador.chat', [
            'user' => $user,
            'conversations' => $conversations,
            'activeConversation' => $active,
            'messages' => $messages,
        ]);
    }

    public function notificaciones()
    {
        $user = Auth::user();

        $notifications = [
            [
                'color' => 'red',
                'icon' => 'bell',
                'title' => 'Nueva cita asignada: Bella - Viernes 10:00 AM',
                'time' => 'Hace 5 min',
                'unread' => true,
            ],
            [
                'color' => 'blue',
                'icon' => 'bell',
                'title' => 'Carlos Rodriguez dejo un mensaje sobre Max',
                'time' => 'Hace 30 min',
                'unread' => true,
            ],
            [
                'color' => 'green',
                'icon' => 'bell',
                'title' => 'Pago recibido por servicios de marzo',
                'time' => 'Hace 2 horas',
                'unread' => false,
            ],
            [
                'color' => 'orange',
                'icon' => 'bell',
                'title' => 'Recordatorio: Vacuna de Luna vence en 5 dias',
                'time' => 'Hace 1 dia',
                'unread' => false,
            ],
        ];

        return view('entrenador.notificaciones', [
            'user' => $user,
            'notifications' => $notifications,
        ]);
    }

    public function perfil()
    {
        $user = Auth::user();

        $fullName = trim((string) ($user->name ?? ''));
        $parts = preg_split('/\s+/', $fullName, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $firstName = $parts[0] ?? '';
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

        $profile = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => '+52 555 123 4567',
            'specialty' => 'Entrenamiento de obediencia, Socializacion',
            'title' => 'Entrenador Senior',
        ];

        return view('entrenador.perfil', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}
