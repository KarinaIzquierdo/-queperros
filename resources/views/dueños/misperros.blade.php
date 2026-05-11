<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mis Mascotas</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/misperros.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dueño/panel.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
    </head>

    <body class="mq-dashboard-page">
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;
        @endphp
        <div class="mq-dashboard">
            @include('partials.dueno-sidebar')

            <main class="mq-dashboard-main">
                @include('partials.mq-topbar', ['user' => Auth::user(), 'user' => Auth::user(), 
                    'user' => $user,
                    'roleLabel' => 'Propietario',
                    'profileUrl' => route('owner.perfil'),
                    'settingsUrl' => route('owner.perfil'),
                    'helpUrl' => route('owner.chat'),
                    'notificationsUrl' => route('owner.notificaciones'),
                    'notifCount' => 2,
                ])

                <div class="mq-dashboard-content">
                    <section class="mq-page">
                    <div class="mq-page-head">
                        <div>
                            <h1 class="mq-page-title">Mis Mascotas</h1>
                            <p class="mq-page-sub">Administra la informacion de tus compañeros peludos</p>
                        </div>
                        <button type="button" class="mq-primary-btn" id="opOpenNewPet">
                            <i class="bi bi-plus" aria-hidden="true"></i>
                            <span>Agregar Mascota</span>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('owner.pets') }}" class="mq-search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Buscar por nombre o raza..." />
                    </form>

                    <div class="mq-pets-grid">
                        @foreach ($pets as $pet)
                            @php
                                $photo = trim((string) ($pet->foto ?? ''));
                                $photoUrl = $photo !== '' ? asset('storage/' . $photo) : '';
                                $initial = strtoupper(mb_substr((string) $pet->nombre, 0, 1));

                                $vaccineCount = 0;
                                $vacunas = trim((string) ($pet->vacunas ?? ''));
                                if ($vacunas !== '') {
                                    $vaccineCount = count(array_filter(array_map('trim', preg_split('/[;,\n]+/', $vacunas))));
                                }
                                if ($vaccineCount <= 0 && ((string) ($pet->fecha_ultima_vacuna_tos ?? '')) !== '') {
                                    $vaccineCount = 1;
                                }

                                $ageText = $pet->edad !== null ? ((int) $pet->edad . ' años') : '-';
                                $pesoText = '-';
                                $tamText = trim((string) ($pet->tipo ?? '')) !== '' ? (string) $pet->tipo : '-';

                                $lastDate = '';
                                if ($pet->created_at) {
                                    try {
                                        $lastDate = $pet->created_at->toDateString();
                                    } catch (Throwable $e) {
                                        $lastDate = '';
                                    }
                                }
                            @endphp

                            <article class="mq-pet-card">
                                <div class="mq-pet-hero">
                                    @if ($photoUrl !== '')
                                        <img src="{{ $photoUrl }}" alt="{{ $pet->nombre }}" loading="lazy" />
                                    @else
                                        <div class="mq-pet-fallback" aria-hidden="true">{{ $initial }}</div>
                                    @endif

                                    <div class="mq-pet-hero-content">
                                        @if ($lastDate !== '')
                                            <div class="mq-pet-chip">
                                                <i class="bi bi-clock" aria-hidden="true"></i>
                                                <span>Cita: {{ $lastDate }}</span>
                                            </div>
                                        @endif

                                        <button class="mq-heart" type="button" aria-label="Favorito">
                                            <i class="bi bi-heart" aria-hidden="true"></i>
                                        </button>

                                        <div class="mq-pet-info-top">
                                            <div class="mq-pet-name">{{ $pet->nombre }}</div>
                                            <div class="mq-pet-breed">{{ $pet->raza }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mq-pet-stats">
                                    <div class="mq-stat">
                                        <div class="mq-stat-label">Edad</div>
                                        <div class="mq-stat-value">{{ $ageText }}</div>
                                    </div>
                                    <div class="mq-stat">
                                        <div class="mq-stat-label">Peso</div>
                                        <div class="mq-stat-value">{{ $pesoText }}</div>
                                    </div>
                                    <div class="mq-stat">
                                        <div class="mq-stat-label">Tamaño</div>
                                        <div class="mq-stat-value">{{ $tamText }}</div>
                                    </div>
                                </div>

                                <div class="mq-pet-meta">
                                    <div class="mq-meta-item">
                                        <i class="bi bi-needle" aria-hidden="true"></i>
                                        <span>{{ $vaccineCount }} vacunas</span>
                                    </div>
                                    <div class="mq-meta-item">
                                        <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                        <span>0 registros</span>
                                    </div>
                                </div>

                                <div class="mq-pet-actions">
                                    <button class="mq-detail" type="button" data-pet='@json($pet)'>Ver detalle <i class="bi bi-chevron-right" aria-hidden="true"></i></button>
                                    <button class="mq-trash" type="button" aria-label="Eliminar" data-destroy-url="{{ route('owner.pets.destroy', $pet->id) }}" data-pet-name="{{ $pet->nombre }}">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </article>
                        @endforeach

                        <article class="mq-add-card" aria-label="Agregar nueva mascota">
                            <button class="mq-add-inner" type="button" id="opOpenNewPetAlt">
                                <div class="mq-add-plus">
                                    <i class="bi bi-plus" aria-hidden="true"></i>
                                </div>
                                <div class="mq-add-text">Agregar nueva mascota</div>
                            </button>
                        </article>
                    </div>
                </section>

                <div class="mq-bottom-mark">Mas que Perros — Tu perro feliz, tu tranquilo</div>

                @if(session('success'))
                <div class="mq-alert mq-alert--success" role="alert">
                    <i class="bi bi-check-circle" aria-hidden="true"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <div class="gm-modal" id="opNewPetModal" aria-hidden="true">
                    <div class="gm-modal-backdrop" data-gm-close="true"></div>
                    <div class="gm-modal-card" role="dialog" aria-modal="true" aria-label="Registrar Mascota">
                        <div class="gm-modal-head">
                            <div class="gm-modal-title">Registrar Nueva Mascota</div>
                            <button class="gm-modal-close" type="button" aria-label="Cerrar" data-gm-close="true">×</button>
                        </div>

                        <div class="gm-modal-progress" id="opWizardProgress">
                            <div class="gm-stepper" aria-hidden="true">
                                <div class="gm-stepper-items">
                                    <div class="gm-stepper-item" data-step="1">
                                        <div class="gm-stepper-circle"><span class="gm-stepper-num">1</span><span class="gm-stepper-check">✓</span></div>
                                        <div class="gm-stepper-label">Datos</div>
                                    </div>
                                    <div class="gm-stepper-line"></div>
                                    <div class="gm-stepper-item" data-step="2">
                                        <div class="gm-stepper-circle"><span class="gm-stepper-num">2</span><span class="gm-stepper-check">✓</span></div>
                                        <div class="gm-stepper-label">Salud</div>
                                    </div>
                                    <div class="gm-stepper-line"></div>
                                    <div class="gm-stepper-item" data-step="3">
                                        <div class="gm-stepper-circle"><span class="gm-stepper-num">3</span><span class="gm-stepper-check">✓</span></div>
                                        <div class="gm-stepper-label">Adicional</div>
                                    </div>
                                    <div class="gm-stepper-line"></div>
                                    <div class="gm-stepper-item" data-step="4">
                                        <div class="gm-stepper-circle"><span class="gm-stepper-num">4</span><span class="gm-stepper-check">✓</span></div>
                                        <div class="gm-stepper-label">Servicio</div>
                                    </div>
                                </div>
                                <div class="gm-stepper-bar">
                                    <div class="gm-stepper-fill" id="opStepperFill"></div>
                                </div>
                                <div class="gm-stepper-sub" id="opWizardStepText">Paso 1 de 4</div>
                            </div>
                        </div>

                        <form class="gm-modal-body" method="POST" action="{{ route('owner.pets.store') }}" enctype="multipart/form-data" id="opNewPetForm">
                            @csrf
                            <div class="gm-wizard" id="opWizard">
                                <section class="gm-form-section gm-step" data-gm-step="1">
                                    <div class="gm-form-section-title">DATOS DEL PERRO</div>
                                    <div class="gm-dog-grid">
                                        <div class="gm-photo">
                                            <label class="gm-photo-box" for="opPhotoInput">
                                                <img id="opPhotoPreview" class="gm-photo-preview" alt="" />
                                                <i class="bi bi-camera gm-photo-icon" aria-hidden="true"></i>
                                            </label>
                                            <div class="gm-photo-caption">Foto</div>
                                            <input id="opPhotoInput" name="foto" type="file" accept="image/*" class="gm-hidden" />
                                        </div>

                                        <div class="gm-fields">
                                            <div class="gm-row-2">
                                                <div class="gm-field">
                                                    <label class="gm-label">Nombre *</label>
                                                    <input class="gm-input" name="nombre" type="text" placeholder="Nombre del perro" required />
                                                </div>
                                                <div class="gm-field">
                                                    <label class="gm-label">Raza *</label>
                                                    <input class="gm-input" name="raza" type="text" placeholder="Raza del perro" required />
                                                </div>
                                            </div>
                                            <input type="hidden" name="tipo" value="Perro" />
                                            <div class="gm-row-3">
                                                <div class="gm-field">
                                                    <label class="gm-label">Edad</label>
                                                    <input class="gm-input" name="edad" type="number" min="0" max="50" placeholder="ej: 3" />
                                                </div>
                                                <div class="gm-field">
                                                    <label class="gm-label">Peso (kg)</label>
                                                    <input class="gm-input" name="peso" type="number" step="0.1" min="0" placeholder="ej: 12.5" />
                                                </div>
                                                <div class="gm-field">
                                                    <label class="gm-label">Sexo</label>
                                                    <select class="gm-input" name="sexo">
                                                        <option value="">Seleccionar</option>
                                                        <option value="Macho">Macho</option>
                                                        <option value="Hembra">Hembra</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="gm-form-section gm-step" data-gm-step="2" style="display:none">
                                    <div class="gm-form-section-title">INFORMACION DE SALUD</div>
                                    <div class="gm-field">
                                        <label class="gm-label">Vacunas Aplicadas</label>
                                        <div class="gm-checkbox-grid">
                                            @foreach(['Moquillo', 'Parvovirus', 'Hepatitis', 'Parainfluenza', 'Leptospira', 'Rabia', 'Multiple (DHPP)', 'Sextuple'] as $vacuna)
                                                <label class="gm-checkbox-item">
                                                    <input type="checkbox" name="vacunas_list[]" value="{{ $vacuna }}">
                                                    <span>{{ $vacuna }}</span>
                                                </label>
                                            @endforeach
                                            <label class="gm-checkbox-item">
                                                <input type="checkbox" name="vacunas_list[]" value="Ninguna" id="vacunaNone">
                                                <span>Ninguna</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="gm-row-2">
                                        <div class="gm-field">
                                            <label class="gm-label">Fecha ultima desparasitacion</label>
                                            <input class="gm-input" name="fecha_ultima_desparasitacion" type="date" />
                                        </div>
                                        <div class="gm-field">
                                            <label class="gm-label">Fecha ultima vacuna tos de perreras</label>
                                            <input class="gm-input" name="fecha_vacuna_tos_perreras" type="date" />
                                        </div>
                                    </div>
                                </section>

                                <section class="gm-form-section gm-form-warn gm-step" data-gm-step="3" style="display:none">
                                    <div class="gm-warn-head">
                                        <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                                        <div>
                                            <div class="gm-warn-title">INFORMACION ADICIONAL</div>
                                            <div class="gm-warn-sub">Comportamientos negativos de tu perro que debemos saber</div>
                                        </div>
                                    </div>
                                    <textarea class="gm-textarea" name="info_adicional" rows="3" placeholder="Describe cualquier comportamiento que debamos conocer: reactividad, ansiedad, miedos, agresividad, etc."></textarea>
                                </section>

                                <section class="gm-form-section gm-step" data-gm-step="4" style="display:none">
                                    <div class="gm-form-section-title">QUE SERVICIO REQUIERES DE MAS QUE PERROS</div>
                                    <div class="gm-radio-list">
                                        <label class="gm-radio-card">
                                            <input type="radio" name="servicio_requerido" value="Hotel Canino" />
                                            <span class="gm-radio-ui"></span>
                                            <span class="gm-radio-text">
                                                <span class="gm-radio-name">Hotel Canino</span>
                                                <span class="gm-radio-sub">Semi Interno (mas de 8 dias) o Interno Permanente</span>
                                            </span>
                                        </label>
                                        <label class="gm-radio-card gm-radio-card--active">
                                            <input type="radio" name="servicio_requerido" value="Entrenamiento" checked />
                                            <span class="gm-radio-ui"></span>
                                            <span class="gm-radio-text">
                                                <span class="gm-radio-name">Entrenamiento</span>
                                                <span class="gm-radio-sub">Basico Integral y Deportivo (OCI, Disc Dog)</span>
                                            </span>
                                        </label>
                                        <label class="gm-radio-card">
                                            <input type="radio" name="servicio_requerido" value="Formacion Perros de Trabajo" />
                                            <span class="gm-radio-ui"></span>
                                            <span class="gm-radio-text">
                                                <span class="gm-radio-name">Formacion Perros de Trabajo</span>
                                                <span class="gm-radio-sub">Proteccion, Deteccion, Olfato y Busqueda</span>
                                            </span>
                                        </label>
                                    </div>

                                    <div class="gm-row-2 gm-last-row">
                                        <div class="gm-field">
                                            <label class="gm-label">Estado actual en instalaciones</label>
                                            <select class="gm-input" name="estado_actual">
                                                <option value="En Casa" selected>En Casa</option>
                                                <option value="En Guarderia">En Guarderia</option>
                                                <option value="Hotel Canino">Hotel Canino</option>
                                                <option value="En Entrenamiento">En Entrenamiento</option>
                                            </select>
                                        </div>
                                        <div class="gm-field">
                                            <label class="gm-label">Notas adicionales</label>
                                            <input class="gm-input" name="notas_adicionales" type="text" placeholder="Alimentacion especial, alergias, etc." />
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div class="gm-modal-foot">
                                <button class="gm-foot-btn gm-foot-btn--ghost" type="button" data-gm-close="true">Cancelar</button>
                                <button class="gm-foot-btn gm-foot-btn--ghost" type="button" id="opWizardPrev">Atrás</button>
                                <button class="gm-foot-btn gm-foot-btn--primary" type="button" id="opWizardNext">Siguiente</button>
                                <button class="gm-foot-btn gm-foot-btn--primary" type="submit" id="opWizardSubmit" style="display:none">Registrar Mascota</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Detalle Mascota -->
                <div class="gm-modal" id="petDetailModal" aria-hidden="true">
                    <div class="gm-modal-backdrop" data-gm-close="true"></div>
                    <div class="gm-modal-card gm-modal-card--detail" role="dialog" aria-modal="true">
                        <div class="dt-hero">
                            <img id="dtHeroImg" src="" alt="" class="dt-hero-bg">
                            <div class="dt-hero-overlay">
                                <div class="dt-hero-content">
                                    <h2 id="dtPetName" class="dt-pet-name"></h2>
                                    <p id="dtPetSub" class="dt-pet-sub"></p>
                                </div>
                                <button class="dt-close" type="button" data-gm-close="true">×</button>
                            </div>
                        </div>

                        <div class="dt-tabs">
                            <button class="dt-tab dt-tab--active" data-target="dtInfo">
                                <i class="bi bi-activity"></i> Informacion
                            </button>
                            <button class="dt-tab" data-target="dtVacunas">
                                <i class="bi bi-needle"></i> Vacunas
                            </button>
                            <button class="dt-tab" data-target="dtHistorial">
                                <i class="bi bi-file-earmark-text"></i> Historial
                            </button>
                        </div>

                        <div class="dt-body">
                            <!-- Tab Informacion -->
                            <div class="dt-pane dt-pane--active" id="dtInfo">
                                <div class="dt-info-grid">
                                    <div class="dt-info-card">
                                        <div class="dt-info-label">Fecha de nacimiento</div>
                                        <div class="dt-info-value" id="dtBirth"></div>
                                    </div>
                                    <div class="dt-info-card">
                                        <div class="dt-info-label">Edad</div>
                                        <div class="dt-info-value" id="dtAge"></div>
                                    </div>
                                    <div class="dt-info-card">
                                        <div class="dt-info-label">Peso</div>
                                        <div class="dt-info-value" id="dtWeight">32 kg</div>
                                    </div>
                                    <div class="dt-info-card">
                                        <div class="dt-info-label">Color</div>
                                        <div class="dt-info-value" id="dtColor">Negro y cafe</div>
                                    </div>
                                    <div class="dt-info-card">
                                        <div class="dt-info-label">Esterilizado</div>
                                        <div class="dt-info-value" id="dtSterilized">Si</div>
                                    </div>
                                    <div class="dt-info-card">
                                        <div class="dt-info-label">Microchip</div>
                                        <div class="dt-info-value" id="dtMicrochip">985141000123456</div>
                                    </div>
                                </div>
                                <div class="dt-next-appt">
                                    <div class="dt-appt-icon">
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                    <div class="dt-appt-info">
                                        <div class="dt-appt-label">Proxima cita</div>
                                        <div class="dt-appt-value" id="dtNextAppt"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Vacunas -->
                            <div class="dt-pane" id="dtVacunas">
                                <div class="dt-list" id="dtVacunasList">
                                    <!-- Dinámico -->
                                </div>
                            </div>

                            <!-- Tab Historial -->
                            <div class="dt-pane" id="dtHistorial">
                                <div class="dt-list" id="dtHistorialList">
                                    <!-- Dinámico -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="gm-modal" id="petDeleteModal" aria-hidden="true">
                    <div class="gm-modal-backdrop" data-gm-close="true"></div>
                    <div class="gm-modal-card gm-modal-card--delete" role="dialog" aria-modal="true" aria-label="Eliminar mascota">
                        <div class="del-wrap">
                            <div class="del-icon" aria-hidden="true">
                                <span class="del-icon-inner"><i class="bi bi-exclamation" aria-hidden="true"></i></span>
                            </div>
                            <h3 class="del-title">Eliminar mascota?</h3>
                            <p class="del-sub">Esta accion no se puede deshacer.</p>

                            <div class="del-actions">
                                <button class="del-btn del-btn--ghost" type="button" data-gm-close="true">Cancelar</button>
                                <form id="petDeleteForm" method="POST" action="">
                                    @csrf
                                    @method('DELETE')
                                    <button class="del-btn del-btn--danger" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </main>
        </div>

        <script>
            (function () {
                const openBtn = document.getElementById('opOpenNewPet');
                const openBtnAlt = document.getElementById('opOpenNewPetAlt');
                const modal = document.getElementById('opNewPetModal');
                if (!modal) return;

                const wizard = document.getElementById('opWizard');
                const form = document.getElementById('opNewPetForm');
                const steps = wizard ? Array.from(wizard.querySelectorAll('.gm-step')) : [];
                const btnPrev = document.getElementById('opWizardPrev');
                const btnNext = document.getElementById('opWizardNext');
                const btnSubmit = document.getElementById('opWizardSubmit');
                const progress = document.getElementById('opWizardProgress');
                const stepText = document.getElementById('opWizardStepText');
                const stepperFill = document.getElementById('opStepperFill');

                const photoInput = document.getElementById('opPhotoInput');
                const photoPreview = document.getElementById('opPhotoPreview');

                let currentStep = 1;
                const totalSteps = steps.length || 1;

                // Auto-hide success alerts
                const successAlerts = document.querySelectorAll('.mq-alert--success');
                successAlerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.transition = 'opacity 0.3s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 300);
                    }, 4000);
                });

                function setProgress() {
                    if (stepText) {
                        stepText.textContent = `Paso ${currentStep} de ${totalSteps}`;
                    }

                    if (stepperFill) {
                        const pct = totalSteps > 1 ? ((currentStep - 1) / (totalSteps - 1)) * 100 : 0;
                        stepperFill.style.width = `${pct}%`;
                    }

                    const items = progress ? progress.querySelectorAll('.gm-stepper-item') : [];
                    items.forEach((it) => {
                        const s = parseInt(it.getAttribute('data-step') || '0', 10);
                        it.classList.toggle('gm-stepper-item--active', s === currentStep);
                        it.classList.toggle('gm-stepper-item--done', s < currentStep);
                    });
                }

                function showStep(n) {
                    currentStep = Math.min(Math.max(1, n), totalSteps);
                    steps.forEach((el) => {
                        const s = parseInt(el.getAttribute('data-gm-step') || '0', 10);
                        el.style.display = s === currentStep ? '' : 'none';
                    });

                    if (btnPrev) btnPrev.style.display = currentStep === 1 ? 'none' : '';
                    if (btnNext) btnNext.style.display = currentStep === totalSteps ? 'none' : '';
                    if (btnSubmit) btnSubmit.style.display = currentStep === totalSteps ? '' : 'none';
                    setProgress();
                }

                function validateStep(stepNumber) {
                    const stepEl = steps.find((s) => parseInt(s.getAttribute('data-gm-step') || '0', 10) === stepNumber);
                    if (!stepEl) return true;

                    const requiredInputs = Array.from(stepEl.querySelectorAll('input[required], select[required], textarea[required]'));
                    for (const input of requiredInputs) {
                        if (!input.checkValidity()) {
                            input.reportValidity();
                            return false;
                        }
                    }

                    return true;
                }

                function openModal() {
                    modal.classList.add('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('gm-lock');
                    if (steps.length) showStep(1);
                }

                function closeModal() {
                    modal.classList.remove('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('gm-lock');
                }

                if (openBtn) openBtn.addEventListener('click', openModal);
                if (openBtnAlt) openBtnAlt.addEventListener('click', openModal);

                modal.addEventListener('click', (e) => {
                    const close = e.target.closest('[data-gm-close="true"]');
                    if (close) closeModal();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('gm-modal--open')) {
                        closeModal();
                    }
                });

                if (btnPrev) {
                    btnPrev.addEventListener('click', () => {
                        showStep(currentStep - 1);
                    });
                }
                if (btnNext) {
                    btnNext.addEventListener('click', () => {
                        if (!validateStep(currentStep)) return;
                        showStep(currentStep + 1);
                    });
                }
                if (form) {
                    form.addEventListener('submit', (e) => {
                        if (!validateStep(currentStep)) {
                            e.preventDefault();
                        }
                    });
                }

                const radioCards = modal.querySelectorAll('.gm-radio-card');
                radioCards.forEach((card) => {
                    const input = card.querySelector('input[type="radio"]');
                    if (!input) return;
                    input.addEventListener('change', () => {
                        radioCards.forEach((c) => c.classList.remove('gm-radio-card--active'));
                        card.classList.add('gm-radio-card--active');
                    });
                    if (input.checked) {
                        card.classList.add('gm-radio-card--active');
                    }
                });

                if (photoInput && photoPreview) {
                    const icon = photoPreview.previousElementSibling;
                    photoInput.addEventListener('change', () => {
                        const file = photoInput.files && photoInput.files[0];
                        if (!file) {
                            photoPreview.removeAttribute('src');
                            photoPreview.classList.remove('gm-photo-preview--on');
                            if (icon && icon.classList.contains('bi-camera')) icon.style.display = '';
                            return;
                        }

                        const url = URL.createObjectURL(file);
                        photoPreview.src = url;
                        photoPreview.classList.add('gm-photo-preview--on');
                        if (icon && icon.classList.contains('bi-camera')) icon.style.display = 'none';
                    });
                }

                // --- Lógica del Modal de Detalles ---
                const detailModal = document.getElementById('petDetailModal');
                const detailBtns = document.querySelectorAll('.mq-detail');

                function closeDetailModal() {
                    if (!detailModal) return;
                    detailModal.classList.remove('gm-modal--open');
                    detailModal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('gm-lock');
                }

                function openDetailModal(pet) {
                    if (!detailModal) return;

                    // Datos Básicos
                    document.getElementById('dtPetName').textContent = pet.nombre;
                    document.getElementById('dtPetSub').textContent = `${pet.raza} - ${pet.sexo || ''}`;
                    
                    const heroImg = document.getElementById('dtHeroImg');
                    if (pet.foto) {
                        heroImg.src = `/storage/${pet.foto}`;
                    } else {
                        heroImg.src = 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&q=80&w=800';
                    }

                    // Tab Información
                    document.getElementById('dtBirth').textContent = pet.created_at ? pet.created_at.split('T')[0] : '2023-03-15';
                    document.getElementById('dtAge').textContent = pet.edad ? `${pet.edad} años` : '-';
                    document.getElementById('dtNextAppt').textContent = pet.created_at ? `${pet.created_at.split('T')[0]} - ${pet.servicio_requerido || 'Consulta general'}` : '-';

                    // Tab Vacunas (Simulado como en la imagen)
                    const vacunasList = document.getElementById('dtVacunasList');
                    vacunasList.innerHTML = `
                        <div class="dt-list-item">
                            <div class="dt-item-icon dt-item-icon--vacuna"><i class="bi bi-check-lg"></i></div>
                            <div class="dt-item-main">
                                <div class="dt-item-title">Antirabica <span class="dt-item-date">Aplicada: 2025-08-10</span></div>
                                <div class="dt-item-sub">Protección anual contra la rabia</div>
                            </div>
                            <div class="dt-item-next">
                                <div class="dt-item-next-label">Proxima</div>
                                <div class="dt-item-next-date">2026-08-10</div>
                            </div>
                        </div>
                        <div class="dt-list-item">
                            <div class="dt-item-icon dt-item-icon--vacuna"><i class="bi bi-check-lg"></i></div>
                            <div class="dt-item-main">
                                <div class="dt-item-title">Parvovirus <span class="dt-item-date">Aplicada: 2025-06-20</span></div>
                                <div class="dt-item-sub">Refuerzo esquema cachorros</div>
                            </div>
                            <div class="dt-item-next">
                                <div class="dt-item-next-label">Proxima</div>
                                <div class="dt-item-next-date">2026-06-20</div>
                            </div>
                        </div>
                    `;

                    // Tab Historial (Simulado como en la imagen)
                    const historialList = document.getElementById('dtHistorialList');
                    historialList.innerHTML = `
                        <div class="dt-list-item">
                            <div class="dt-item-icon dt-item-icon--entreno"><i class="bi bi-activity"></i></div>
                            <div class="dt-item-main">
                                <div class="dt-item-title">Entrenamiento <span class="dt-item-date">2026-03-10</span></div>
                                <div class="dt-item-sub">Sesion de obediencia basica</div>
                                <div class="dt-item-foot">Atendido por: Carlos Martinez</div>
                            </div>
                        </div>
                        <div class="dt-list-item">
                            <div class="dt-item-icon dt-item-icon--historial"><i class="bi bi-file-earmark-text"></i></div>
                            <div class="dt-item-main">
                                <div class="dt-item-title">Consulta <span class="dt-item-date">2026-02-15</span></div>
                                <div class="dt-item-sub">Revision general - Todo en orden</div>
                                <div class="dt-item-foot">Atendido por: Dra. Ana Lopez</div>
                            </div>
                        </div>
                    `;

                    detailModal.classList.add('gm-modal--open');
                    detailModal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('gm-lock');
                    
                    // Reset to first tab
                    document.querySelector('.dt-tab[data-target="dtInfo"]').click();
                }

                detailBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const pet = JSON.parse(btn.getAttribute('data-pet'));
                        openDetailModal(pet);
                    });
                });

                if (detailModal) {
                    detailModal.addEventListener('click', (e) => {
                        const close = e.target.closest('[data-gm-close="true"]');
                        if (close) closeDetailModal();
                    });

                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && detailModal.classList.contains('gm-modal--open')) {
                            closeDetailModal();
                        }
                    });
                }

                const deleteModal = document.getElementById('petDeleteModal');
                const deleteForm = document.getElementById('petDeleteForm');
                const trashBtns = document.querySelectorAll('.mq-trash');

                function openDeleteModal(destroyUrl) {
                    if (!deleteModal || !deleteForm) return;
                    deleteForm.setAttribute('action', destroyUrl);
                    deleteModal.classList.add('gm-modal--open');
                    deleteModal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('gm-lock');
                }

                function closeDeleteModal() {
                    if (!deleteModal) return;
                    deleteModal.classList.remove('gm-modal--open');
                    deleteModal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('gm-lock');
                }

                trashBtns.forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const url = btn.getAttribute('data-destroy-url');
                        if (!url) return;
                        openDeleteModal(url);
                    });
                });

                if (deleteModal) {
                    deleteModal.addEventListener('click', (e) => {
                        const close = e.target.closest('[data-gm-close="true"]');
                        if (close) closeDeleteModal();
                    });

                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && deleteModal.classList.contains('gm-modal--open')) {
                            closeDeleteModal();
                        }
                    });
                }

                // Lógica de Tabs
                const tabs = document.querySelectorAll('.dt-tab');
                const panes = document.querySelectorAll('.dt-pane');

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        const target = tab.getAttribute('data-target');
                        
                        tabs.forEach(t => t.classList.remove('dt-tab--active'));
                        panes.forEach(p => p.classList.remove('dt-pane--active'));
                        
                        tab.classList.add('dt-tab--active');
                        document.getElementById(target).classList.add('dt-pane--active');
                    });
                });
            })();
        </script>
    </body>
</html>
