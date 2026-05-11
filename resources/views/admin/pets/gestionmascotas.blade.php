<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión de Mascotas</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/shared/mq-topbar.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-dashboard.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/admin-sidebar-extras.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/dashboard-admin-v2.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/Admin/gestionmascotas.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        @include('partials.page-loader')
        @php
            use Illuminate\Support\Str;
        @endphp
        <div class="admin-layout">
            @include('partials.admin-sidebar')

            <main class="admin-main">
                @include('partials.mq-topbar', ['user' => Auth::user(), 'user' => Auth::user(), 
                    'user' => $admin,
                    'roleLabel' => 'Administrador',
                    'profileUrl' => route('admin.settings'),
                    'settingsUrl' => route('admin.settings'),
                    'helpUrl' => route('admin.dashboard'),
                    'notificationsUrl' => route('admin.dashboard'),
                    'notifCount' => 2,
                ])
                <div class="gm-top">
                    <div>
                        <h1 class="gm-title">Gestion de Mascotas</h1>
                        <p class="gm-subtitle">Administra las mascotas, su entrenamiento y servicios activos</p>
                    </div>
                    <button class="gm-primary-btn" type="button" id="gmOpenNewPet">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                        <span>Nueva Mascota</span>
                    </button>
                </div>

                @if (session('success'))
                    <div class="gm-toast" id="gmToastSuccess">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="gm-toast" id="gmToastError">{{ (string) $errors->first() }}</div>
                @endif

                <div class="gm-modal" id="gmNewPetModal" aria-hidden="true">
                    <div class="gm-modal-backdrop" data-gm-close="true"></div>
                    <div class="gm-modal-card" role="dialog" aria-modal="true" aria-label="Registrar Nueva Mascota">
                        <div class="gm-modal-head">
                            <div class="gm-modal-title">Registrar Nueva Mascota</div>
                            <button class="gm-modal-close" type="button" aria-label="Cerrar" data-gm-close="true">×</button>
                        </div>

                        <div class="gm-modal-progress" id="gmWizardProgress">
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
                                    <div class="gm-stepper-line"></div>
                                    <div class="gm-stepper-item" data-step="5">
                                        <div class="gm-stepper-circle"><span class="gm-stepper-num">5</span><span class="gm-stepper-check">✓</span></div>
                                        <div class="gm-stepper-label">Dueño</div>
                                    </div>
                                </div>
                                <div class="gm-stepper-bar">
                                    <div class="gm-stepper-fill" id="gmStepperFill"></div>
                                </div>
                                <div class="gm-stepper-sub" id="gmWizardStepText">Paso 1 de 5</div>
                            </div>
                        </div>

                        <form class="gm-modal-body" method="POST" action="{{ route('admin.pets.store') }}" enctype="multipart/form-data" id="gmNewPetForm">
                            @csrf
                            <div class="gm-wizard" id="gmWizard">
                            <section class="gm-form-section gm-step" data-gm-step="1">
                                <div class="gm-form-section-title">DATOS DEL PERRO</div>
                                <div class="gm-dog-grid">
                                    <div class="gm-photo">
                                        <label class="gm-photo-box" for="gmPhotoInput">
                                            <img id="gmPhotoPreview" class="gm-photo-preview" alt="" />
                                            <i class="bi bi-camera" aria-hidden="true"></i>
                                        </label>
                                        <div class="gm-photo-caption">Foto</div>
                                        <input id="gmPhotoInput" name="foto" type="file" accept="image/*" class="gm-hidden" />
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
                                        <div class="gm-row-2">
                                            <div class="gm-field">
                                                <label class="gm-label">Edad</label>
                                                <input class="gm-input" name="edad" type="number" min="0" max="50" placeholder="ej: 3" />
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
                                    <input class="gm-hidden" name="vacunas" id="gmVacunasInput" type="text" />
                                    <div class="gm-checkbox-grid" id="gmVacunasGrid">
                                        @foreach(['Moquillo', 'Parvovirus', 'Hepatitis', 'Parainfluenza', 'Leptospira', 'Rabia', 'Multiple (DHPP)', 'Sextuple', 'Ninguna'] as $vacuna)
                                            <label class="gm-checkbox-item">
                                                <input type="checkbox" name="vacunas_list[]" value="{{ $vacuna }}">
                                                <span>{{ $vacuna }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="gm-row-2">
                                    <div class="gm-field">
                                        <label class="gm-label">Fecha ultima desparasitacion</label>
                                        <input class="gm-input" name="fecha_ultima_desparasitacion" type="date" />
                                    </div>
                                    <div class="gm-field">
                                        <label class="gm-label">Fecha ultima vacuna tos de perreras</label>
                                        <input class="gm-input" name="fecha_ultima_vacuna_tos" type="date" />
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

                            <section class="gm-form-section gm-step" data-gm-step="5" style="display:none">
                                <div class="gm-form-section-title">SELECCIONAR DUEÑO</div>
                                <div class="gm-field">
                                    <label class="gm-label">Dueño *</label>
                                    <div class="gm-owner">
                                        <div class="gm-owner-search">
                                            <i class="bi bi-search" aria-hidden="true"></i>
                                            <input class="gm-owner-input" id="gmOwnerSearch" type="text" placeholder="Buscar dueño por nombre o correo..." autocomplete="off" />
                                        </div>
                                        <input type="hidden" name="id_dueno" id="gmOwnerId" value="" />
                                        <div class="gm-owner-list" id="gmOwnerList" aria-hidden="true">
                                            @foreach(($owners ?? []) as $o)
                                                @php
                                                    $ownerName = (string) ($o['display'] ?? '');
                                                    $ownerEmail = (string) ($o['email'] ?? '');
                                                    $ownerInitials = mb_strtoupper(mb_substr(trim($ownerName) !== '' ? $ownerName : 'DU', 0, 2));
                                                @endphp
                                                <button type="button" class="gm-owner-item" data-id="{{ $o['id'] ?? 0 }}" data-name="{{ $ownerName }}" data-email="{{ $ownerEmail }}">
                                                    <span class="gm-owner-avatar">{{ $ownerInitials }}</span>
                                                    <span class="gm-owner-meta">
                                                        <span class="gm-owner-name">{{ $ownerName }}</span>
                                                        <span class="gm-owner-email">{{ $ownerEmail }}</span>
                                                    </span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>
                            </div>

                            <div class="gm-modal-foot">
                                <button class="gm-foot-btn gm-foot-btn--ghost" type="button" data-gm-close="true">Cancelar</button>
                                <button class="gm-foot-btn gm-foot-btn--ghost" type="button" id="gmWizardPrev">Atrás</button>
                                <button class="gm-foot-btn gm-foot-btn--primary" type="button" id="gmWizardNext">Siguiente</button>
                                <button class="gm-foot-btn gm-foot-btn--primary" type="submit" id="gmWizardSubmit" style="display:none">Registrar Mascota</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="gm-modal" id="gmEditPetModal" aria-hidden="true">
                    <div class="gm-modal-backdrop" data-gm-close="true"></div>
                    <div class="gm-modal-card" role="dialog" aria-modal="true" aria-label="Editar Mascota">
                        <div class="gm-modal-head">
                            <div class="gm-modal-title">Editar Mascota</div>
                            <button class="gm-modal-close" type="button" aria-label="Cerrar" data-gm-close="true">×</button>
                        </div>

                        <form class="gm-modal-body" method="POST" action="" enctype="multipart/form-data" id="gmEditPetForm">
                            @csrf
                            @method('PUT')

                            <div class="gm-form-section">
                                <div class="gm-form-section-title">DATOS DEL PERRO</div>
                                <div class="gm-dog-grid">
                                    <div class="gm-photo">
                                        <label class="gm-photo-box" for="gmEditFoto">
                                            <img id="gmEditPhotoPreview" class="gm-photo-preview" alt="" />
                                            <span id="gmEditInitials">--</span>
                                        </label>
                                        <div class="gm-photo-caption">Foto</div>
                                        <input id="gmEditFoto" name="foto" type="file" accept="image/*" class="gm-hidden" />
                                    </div>

                                    <div class="gm-fields">
                                        <div class="gm-row-2">
                                            <div class="gm-field">
                                                <label class="gm-label">Nombre *</label>
                                                <input class="gm-input" name="nombre" id="gmEditNombre" type="text" required />
                                            </div>
                                            <div class="gm-field">
                                                <label class="gm-label">Raza *</label>
                                                <input class="gm-input" name="raza" id="gmEditRaza" type="text" required />
                                            </div>
                                        </div>
                                        <div class="gm-row-2">
                                            <div class="gm-field">
                                                <label class="gm-label">Edad</label>
                                                <input class="gm-input" name="edad" id="gmEditEdad" type="number" min="0" max="50" />
                                            </div>
                                            <div class="gm-field">
                                                <label class="gm-label">Sexo</label>
                                                <select class="gm-input" name="sexo" id="gmEditSexo">
                                                    <option value="">Seleccionar</option>
                                                    <option value="Macho">Macho</option>
                                                    <option value="Hembra">Hembra</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="gm-form-section">
                                <div class="gm-form-section-title">INFORMACION DE SALUD</div>
                                <div class="gm-field">
                                    <label class="gm-label">Vacunas Aplicadas</label>
                                    <input class="gm-hidden" name="vacunas" id="gmEditVacunas" type="text" />
                                    <div class="gm-checkbox-grid" id="gmEditVacunasGrid">
                                        @foreach(['Moquillo', 'Parvovirus', 'Hepatitis', 'Parainfluenza', 'Leptospira', 'Rabia', 'Multiple (DHPP)', 'Sextuple', 'Ninguna'] as $vacuna)
                                            <label class="gm-checkbox-item">
                                                <input type="checkbox" name="vacunas_list_edit[]" value="{{ $vacuna }}">
                                                <span>{{ $vacuna }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="gm-row-2">
                                    <div class="gm-field">
                                        <label class="gm-label">Fecha ultima desparasitacion</label>
                                        <input class="gm-input" name="fecha_ultima_desparasitacion" id="gmEditDepart" type="date" />
                                    </div>
                                    <div class="gm-field">
                                        <label class="gm-label">Fecha ultima vacuna tos de perreras</label>
                                        <input class="gm-input" name="fecha_ultima_vacuna_tos" id="gmEditLast" type="date" />
                                    </div>
                                </div>
                            </div>

                            <div class="gm-form-section">
                                <div class="gm-form-section-title">INFORMACION ADICIONAL</div>
                                <div class="gm-field">
                                    <label class="gm-label">Comportamientos a tener en cuenta</label>
                                    <textarea class="gm-textarea" name="info_adicional" id="gmEditBehavior" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="gm-form-section">
                                <div class="gm-form-section-title">SERVICIO</div>
                                <div class="gm-row-2">
                                    <div class="gm-field">
                                        <label class="gm-label">Servicio requerido</label>
                                        <input class="gm-input" name="servicio_requerido" id="gmEditService" type="text" />
                                    </div>
                                    <div class="gm-field">
                                        <label class="gm-label">Estado actual</label>
                                        <select class="gm-input" name="estado_actual" id="gmEditStatus">
                                            <option value="En Casa">En Casa</option>
                                            <option value="En Guarderia">En Guarderia</option>
                                            <option value="Hotel Canino">Hotel Canino</option>
                                            <option value="En Entrenamiento">En Entrenamiento</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="gm-field">
                                    <label class="gm-label">Notas</label>
                                    <input class="gm-input" name="notas_adicionales" id="gmEditNotes" type="text" />
                                </div>
                                <div class="gm-field">
                                    <label class="gm-label">Telefono (si aplica)</label>
                                    <input class="gm-input" name="telefono" id="gmEditPhone" type="text" />
                                </div>
                            </div>

                            <div class="gm-modal-foot">
                                <button class="gm-foot-btn gm-foot-btn--ghost" type="button" data-gm-close="true">Cancelar</button>
                                <button class="gm-foot-btn gm-foot-btn--primary" type="submit">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="gm-modal" id="gmPetDetailsModal" aria-hidden="true">
                    <div class="gm-modal-backdrop" data-gm-close="true"></div>
                    <div class="gm-details-drawer" role="dialog" aria-modal="true" aria-label="Detalle de Mascota">
                        <div class="gm-details-head">
                            <div class="gm-details-left">
                                <div class="gm-details-avatar" id="gmDetailsAvatar">MX</div>
                                <div class="gm-details-title">
                                    <div class="gm-details-name" id="gmDetailsName">Mascota</div>
                                    <div class="gm-details-sub" id="gmDetailsSub">Raza - Edad - Sexo</div>
                                </div>
                            </div>
                            <div class="gm-details-right">
                                <div class="gm-details-status" id="gmDetailsStatus">En Casa</div>
                                <button class="gm-modal-close" type="button" aria-label="Cerrar" data-gm-close="true">×</button>
                            </div>
                        </div>

                        <div class="gm-details-body">
                            <div class="gm-details-badges" id="gmDetailsBadges"></div>

                            <div class="gm-details-grid">
                                <div class="gm-details-info">
                                    <div class="gm-details-mini">
                                        <div class="gm-details-mini-ico"><i class="bi bi-person" aria-hidden="true"></i></div>
                                        <div class="gm-details-mini-meta">
                                            <div class="gm-details-mini-label">TUTOR</div>
                                            <div class="gm-details-mini-value" id="gmDetailsTutor">-</div>
                                        </div>
                                    </div>
                                    <div class="gm-details-mini">
                                        <div class="gm-details-mini-ico"><i class="bi bi-telephone" aria-hidden="true"></i></div>
                                        <div class="gm-details-mini-meta">
                                            <div class="gm-details-mini-label">TELEFONO</div>
                                            <div class="gm-details-mini-value" id="gmDetailsPhone">-</div>
                                        </div>
                                    </div>
                                    <div class="gm-details-mini">
                                        <div class="gm-details-mini-ico"><i class="bi bi-geo-alt" aria-hidden="true"></i></div>
                                        <div class="gm-details-mini-meta">
                                            <div class="gm-details-mini-label">DIRECCION</div>
                                            <div class="gm-details-mini-value" id="gmDetailsAddress">-</div>
                                        </div>
                                    </div>
                                    <div class="gm-details-mini">
                                        <div class="gm-details-mini-ico"><i class="bi bi-calendar3" aria-hidden="true"></i></div>
                                        <div class="gm-details-mini-meta">
                                            <div class="gm-details-mini-label">FECHA DE INGRESO</div>
                                            <div class="gm-details-mini-value" id="gmDetailsIngreso">-</div>
                                        </div>
                                    </div>
                                    <div class="gm-details-mini">
                                        <div class="gm-details-mini-ico"><i class="bi bi-gender-ambiguous" aria-hidden="true"></i></div>
                                        <div class="gm-details-mini-meta">
                                            <div class="gm-details-mini-label">SEXO</div>
                                            <div class="gm-details-mini-value" id="gmDetailsSex">-</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="gm-details-health">
                                    <div class="gm-details-section-title"><i class="bi bi-heart-pulse" aria-hidden="true"></i><span>Informacion de Salud</span></div>
                                    <div class="gm-details-panel">
                                        <div class="gm-details-health-grid">
                                            <div class="gm-details-health-item">
                                                <div class="gm-details-health-label">VACUNAS</div>
                                                <div class="gm-details-health-value" id="gmDetailsVaccines">-</div>
                                            </div>
                                            <div class="gm-details-health-item">
                                                <div class="gm-details-health-label">ULT. DESPARASITACION</div>
                                                <div class="gm-details-health-value" id="gmDetailsDepart">-</div>
                                            </div>
                                            <div class="gm-details-health-item">
                                                <div class="gm-details-health-label">ULT. VACUNA TOS DE PERRERAS</div>
                                                <div class="gm-details-health-value" id="gmDetailsLast">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="gm-details-behavior">
                                    <div class="gm-details-section-title gm-details-section-title--warn"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i><span>Comportamientos a tener en cuenta</span></div>
                                    <div class="gm-details-warn" id="gmDetailsBehavior">-</div>
                                </div>

                                <div class="gm-details-notes">
                                    <div class="gm-details-section-title"><i class="bi bi-journal-text" aria-hidden="true"></i><span>Notas</span></div>
                                    <div class="gm-details-panel" id="gmDetailsNotes">-</div>
                                </div>

                                <div class="gm-details-program">
                                    <div class="gm-details-section-title"><i class="bi bi-graph-up" aria-hidden="true"></i><span>Programas de Entrenamiento</span></div>
                                    <div class="gm-details-program-card">
                                        <div class="gm-details-program-top">
                                            <div class="gm-details-program-name" id="gmDetailsProgramName">-</div>
                                            <div class="gm-details-program-pct" id="gmDetailsProgramPct">0%</div>
                                        </div>
                                        <div class="gm-details-program-sub" id="gmDetailsProgramMeta">-</div>
                                        <div class="gm-details-program-bar"><div class="gm-details-program-fill" id="gmDetailsProgramFill" style="width:0%"></div></div>
                                        <div class="gm-details-program-foot" id="gmDetailsProgramFoot">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="gm-stats">
                    <div class="gm-stat">
                        <div class="gm-stat-icon gm-icon-purple"><i class="bi bi-emoji-smile" aria-hidden="true"></i></div>
                        <div class="gm-stat-meta">
                            <div class="gm-stat-value">{{ (int) ($stats['total_pets'] ?? 0) }}</div>
                            <div class="gm-stat-label">Total mascotas</div>
                        </div>
                    </div>
                    <div class="gm-stat">
                        <div class="gm-stat-icon gm-icon-green"><i class="bi bi-house-door" aria-hidden="true"></i></div>
                        <div class="gm-stat-meta">
                            <div class="gm-stat-value">{{ (int) ($stats['guarderia'] ?? 0) }}</div>
                            <div class="gm-stat-label">En guarderia</div>
                        </div>
                    </div>
                    <div class="gm-stat">
                        <div class="gm-stat-icon gm-icon-blue"><i class="bi bi-building" aria-hidden="true"></i></div>
                        <div class="gm-stat-meta">
                            <div class="gm-stat-value">{{ (int) ($stats['hotel'] ?? 0) }}</div>
                            <div class="gm-stat-label">En hotel</div>
                        </div>
                    </div>
                    <div class="gm-stat">
                        <div class="gm-stat-icon gm-icon-yellow"><i class="bi bi-award" aria-hidden="true"></i></div>
                        <div class="gm-stat-meta">
                            <div class="gm-stat-value">{{ (int) ($stats['entrenamiento'] ?? 0) }}</div>
                            <div class="gm-stat-label">En entrenamiento</div>
                        </div>
                    </div>
                </section>

                <section class="gm-controls">
                    <div class="gm-search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input id="gmSearch" type="text" placeholder="Buscar mascota o raza..." />
                    </div>

                    <div class="gm-chips" id="gmChips">
                        <button type="button" class="gm-chip gm-chip--active" data-status="Todos">Todos</button>
                        <button type="button" class="gm-chip" data-status="En Guarderia">Guarderia</button>
                        <button type="button" class="gm-chip" data-status="Hotel Canino">Hotel</button>
                        <button type="button" class="gm-chip" data-status="En Entrenamiento">Entrenamiento</button>
                        <button type="button" class="gm-chip" data-status="En Casa">En Casa</button>
                    </div>
                </section>

                <section class="gm-grid" id="gmGrid">
                    @foreach (($pets ?? []) as $pet)
                        @php
                            $status = (string) ($pet['status'] ?? '');
                            $statusLower = mb_strtolower($status);
                            $statusClass = match (true) {
                                str_contains($statusLower, 'guard') => 'gm-pill--green',
                                str_contains($statusLower, 'hotel') => 'gm-pill--blue',
                                str_contains($statusLower, 'entrena') => 'gm-pill--orange',
                                default => 'gm-pill--gray',
                            };

                            $hasAlert = trim((string) ($pet['depart'] ?? '')) === '' || trim((string) ($pet['last'] ?? '')) === '';

                            $photo = trim((string) ($pet['photo'] ?? ''));
                            $photoUrl = $photo !== '' ? asset('storage/' . ltrim($photo, '/')) : '';
                        @endphp

                        <article class="gm-card" data-name="{{ (string) ($pet['name'] ?? '') }}" data-breed="{{ (string) ($pet['breed'] ?? '') }}" data-status="{{ (string) ($pet['status'] ?? '') }}">
                            <header class="gm-hero">
                                @if ($photoUrl !== '')
                                    <img class="gm-hero-img" src="{{ $photoUrl }}" alt="" loading="lazy" />
                                @else
                                    <div class="gm-hero-img gm-hero-img--placeholder" aria-hidden="true"></div>
                                @endif

                                <div class="gm-hero-badges">
                                    @if ($hasAlert)
                                        <span class="gm-badge gm-badge--alert"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i><span>Alerta</span></span>
                                    @endif
                                    <span class="gm-badge gm-badge--status {{ $statusClass }}">{{ $status }}</span>
                                </div>

                                <div class="gm-hero-overlay">
                                    <div class="gm-hero-name">{{ (string) ($pet['name'] ?? '') }}</div>
                                    <div class="gm-hero-sub">{{ (string) ($pet['breed_age'] ?? '') }}</div>
                                </div>
                            </header>

                            <div class="gm-card-body">

                            <div class="gm-tutor"><span class="gm-muted">Tutor:</span><span class="gm-strong">{{ (string) ($pet['tutor'] ?? '') }}</span></div>

                            <div class="gm-tags">
                                @foreach (($pet['tags'] ?? []) as $tag)
                                    @php
                                        $tagLower = mb_strtolower((string) $tag);
                                        $tagClass = str_contains($tagLower, 'comport') ? 'gm-tag--orange' : (str_contains($tagLower, 'hotel') ? 'gm-tag--blue' : (str_contains($tagLower, 'formacion') || str_contains($tagLower, 'formación') ? 'gm-tag--red' : 'gm-tag--orange'));
                                        $tagIcon = str_contains($tagLower, 'comport') ? 'bi-exclamation-triangle' : (str_contains($tagLower, 'hotel') ? 'bi-building' : (str_contains($tagLower, 'formacion') || str_contains($tagLower, 'formación') ? 'bi-mortarboard' : 'bi-lightning-charge'));
                                    @endphp
                                    <span class="gm-tag {{ $tagClass }}"><i class="bi {{ $tagIcon }}" aria-hidden="true"></i>{{ (string) $tag }}</span>
                                @endforeach
                            </div>

                            <div class="gm-dates">
                                <div class="gm-date"><i class="bi bi-calendar-event" aria-hidden="true"></i><span>Despar: {{ (string) ($pet['depart'] ?? '') }}</span></div>
                                <div class="gm-date"><i class="bi bi-clock" aria-hidden="true"></i><span>Tos: {{ (string) ($pet['last'] ?? '') }}</span></div>
                            </div>

                            @if (!empty($pet['program']) && $pet['progress'] !== null)
                                <div class="gm-program">
                                    <div class="gm-program-top">
                                        <div class="gm-program-name"><i class="bi bi-graph-up" aria-hidden="true"></i><span>{{ (string) $pet['program'] }}</span></div>
                                        <div class="gm-program-pct">{{ (int) $pet['progress'] }}%</div>
                                    </div>
                                    <div class="gm-bar">
                                        <div class="gm-bar-fill" style="width: {{ (int) $pet['progress'] }}%"></div>
                                    </div>
                                    <div class="gm-program-foot">Entrenador: {{ (string) ($pet['trainer'] ?? '') }}</div>
                                </div>
                            @else
                                <div class="gm-program gm-program--empty">
                                    <div>Sin programa de entrenamiento</div>
                                </div>
                            @endif

                            <footer class="gm-actions">
                                <button class="gm-action gm-action--full" type="button" data-gm-view="pet" data-pet='@json($pet)'><i class="bi bi-eye" aria-hidden="true"></i><span>Ver detalle</span></button>
                                <button class="gm-action" type="button" aria-label="Editar" data-gm-edit="pet" data-pet='@json($pet)'><i class="bi bi-pencil" aria-hidden="true"></i></button>
                                <button class="gm-action" type="button" aria-label="Eliminar" data-gm-delete="pet" data-id="{{ (int) ($pet['id'] ?? 0) }}" data-name="{{ (string) ($pet['name'] ?? '') }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                            </footer>
                            </div>
                        </article>
                    @endforeach
                </section>

                <form id="gmDeletePetForm" method="POST" action="" style="display:none">
                    @csrf
                    @method('DELETE')
                </form>

                <div class="gm-modal" id="gmDeleteConfirmModal" aria-hidden="true">
                    <div class="gm-modal-backdrop" data-gm-delete-close="true"></div>
                    <div class="gm-confirm-card" role="dialog" aria-modal="true" aria-label="Eliminar mascota">
                        <div class="gm-confirm-head">
                            <div class="gm-confirm-title">Eliminar mascota</div>
                            <button class="gm-confirm-close" type="button" aria-label="Cerrar" data-gm-delete-close="true">×</button>
                        </div>
                        <div class="gm-confirm-body" id="gmDeleteConfirmText">Esta acción eliminará el registro de la mascota y todo su historial de entrenamiento.</div>
                        <div class="gm-confirm-actions">
                            <button class="gm-confirm-btn gm-confirm-btn--ghost" type="button" data-gm-delete-close="true">Cancelar</button>
                            <button class="gm-confirm-btn gm-confirm-btn--danger" type="button" id="gmDeleteConfirmBtn">Eliminar</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script>
            (function () {
                const grid = document.getElementById('gmGrid');
                const search = document.getElementById('gmSearch');
                const chips = document.getElementById('gmChips');
                if (!grid || !search || !chips) return;

                let activeStatus = 'Todos';

                function normalize(v) {
                    return (v || '').toString().trim().toLowerCase();
                }

                function applyFilters() {
                    const q = normalize(search.value);
                    const cards = grid.querySelectorAll('.gm-card');

                    cards.forEach((card) => {
                        const status = (card.getAttribute('data-status') || '').trim();
                        const name = normalize(card.getAttribute('data-name'));
                        const breed = normalize(card.getAttribute('data-breed'));

                        const matchesStatus = activeStatus === 'Todos' || status === activeStatus;
                        const matchesText = !q || name.includes(q) || breed.includes(q) || normalize(status).includes(q);

                        card.style.display = matchesStatus && matchesText ? '' : 'none';
                    });
                }

                chips.addEventListener('click', (e) => {
                    const btn = e.target.closest('.gm-chip');
                    if (!btn) return;

                    chips.querySelectorAll('.gm-chip').forEach((b) => b.classList.remove('gm-chip--active'));
                    btn.classList.add('gm-chip--active');
                    activeStatus = btn.getAttribute('data-status') || 'Todos';
                    applyFilters();
                });

                search.addEventListener('input', applyFilters);
                applyFilters();
            })();

            (function () {
                const openBtn = document.getElementById('gmOpenNewPet');
                const modal = document.getElementById('gmNewPetModal');
                if (!openBtn || !modal) return;

                const wizard = document.getElementById('gmWizard');
                const form = document.getElementById('gmNewPetForm');
                const steps = wizard ? Array.from(wizard.querySelectorAll('.gm-step')) : [];
                const btnPrev = document.getElementById('gmWizardPrev');
                const btnNext = document.getElementById('gmWizardNext');
                const btnSubmit = document.getElementById('gmWizardSubmit');
                const progress = document.getElementById('gmWizardProgress');
                const stepText = document.getElementById('gmWizardStepText');
                const stepperFill = document.getElementById('gmStepperFill');

                let currentStep = 1;
                const totalSteps = steps.length || 1;

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

                    const modalCard = modal.querySelector('.gm-modal-card');
                    if (modalCard) {
                        modalCard.classList.toggle('gm-modal-card--overlay', currentStep === totalSteps);
                    }

                    if (currentStep === totalSteps) {
                        const ownerSearch = document.getElementById('gmOwnerSearch');
                        if (ownerSearch) {
                            ownerSearch.focus();
                            ownerSearch.dispatchEvent(new Event('focus'));
                        }
                    }
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

                    if (stepNumber === totalSteps) {
                        const ownerId = document.getElementById('gmOwnerId');
                        const ownerSearch = document.getElementById('gmOwnerSearch');
                        if (!ownerId || !ownerSearch) return true;
                        if (!ownerId.value) {
                            ownerSearch.focus();
                            return false;
                        }
                    }

                    return true;
                }

                function openModal() {
                    modal.classList.add('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('gm-lock');

                    if (steps.length) {
                        showStep(1);
                    }
                }

                function closeModal() {
                    modal.classList.remove('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('gm-lock');
                }

                openBtn.addEventListener('click', openModal);
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

                const toast = document.getElementById('gmToastSuccess');
                if (toast) {
                    setTimeout(() => {
                        toast.classList.add('gm-toast--hide');
                    }, 2200);
                }

                const toastErr = document.getElementById('gmToastError');
                if (toastErr) {
                    setTimeout(() => {
                        toastErr.classList.add('gm-toast--hide');
                    }, 3500);
                }

                const ownerSearch = document.getElementById('gmOwnerSearch');
                const ownerId = document.getElementById('gmOwnerId');
                const ownerList = document.getElementById('gmOwnerList');
                if (ownerSearch && ownerId && ownerList) {
                    function normalize(v) {
                        return (v || '').toString().trim().toLowerCase();
                    }

                    function openList() {
                        ownerList.classList.add('gm-owner-list--open');
                        ownerList.setAttribute('aria-hidden', 'false');
                    }

                    function closeList() {
                        ownerList.classList.remove('gm-owner-list--open');
                        ownerList.setAttribute('aria-hidden', 'true');
                    }

                    function filterList() {
                        const q = normalize(ownerSearch.value);
                        const items = ownerList.querySelectorAll('.gm-owner-item');
                        let visible = 0;
                        items.forEach((it) => {
                            const name = normalize(it.getAttribute('data-name'));
                            const email = normalize(it.getAttribute('data-email'));
                            const show = !q || name.includes(q) || email.includes(q);
                            it.style.display = show ? '' : 'none';
                            if (show) visible += 1;
                        });
                        if (!q) {
                            if (items.length > 0) openList();
                            return;
                        }
                        if (visible > 0) {
                            openList();
                        }
                    }

                    ownerSearch.addEventListener('focus', () => {
                        ownerId.value = '';
                        filterList();
                    });
                    ownerSearch.addEventListener('input', () => {
                        ownerId.value = '';
                        filterList();
                    });

                    ownerSearch.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            closeList();
                        }
                    });

                    ownerList.addEventListener('click', (e) => {
                        const item = e.target.closest('.gm-owner-item');
                        if (!item) return;
                        ownerId.value = item.getAttribute('data-id') || '';
                        ownerSearch.value = item.getAttribute('data-name') || '';
                        closeList();
                    });

                    document.addEventListener('click', (e) => {
                        if (!modal.classList.contains('gm-modal--open')) return;
                        if (e.target.closest('#gmOwnerList') || e.target.closest('#gmOwnerSearch')) return;
                        closeList();
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

                const photoInput = document.getElementById('gmPhotoInput');
                const photoPreview = document.getElementById('gmPhotoPreview');
                if (photoInput && photoPreview) {
                    photoInput.addEventListener('change', () => {
                        const file = photoInput.files && photoInput.files[0];
                        if (!file) {
                            photoPreview.removeAttribute('src');
                            photoPreview.classList.remove('gm-photo-preview--on');
                            return;
                        }

                        const url = URL.createObjectURL(file);
                        photoPreview.src = url;
                        photoPreview.classList.add('gm-photo-preview--on');
                    });
                }
            })();

            (function () {
                const grid = document.getElementById('gmGrid');
                const modal = document.getElementById('gmPetDetailsModal');
                if (!grid || !modal) return;

                const avatar = document.getElementById('gmDetailsAvatar');
                const name = document.getElementById('gmDetailsName');
                const sub = document.getElementById('gmDetailsSub');
                const status = document.getElementById('gmDetailsStatus');
                const badges = document.getElementById('gmDetailsBadges');

                const tutor = document.getElementById('gmDetailsTutor');
                const phone = document.getElementById('gmDetailsPhone');
                const address = document.getElementById('gmDetailsAddress');
                const ingreso = document.getElementById('gmDetailsIngreso');
                const sex = document.getElementById('gmDetailsSex');

                const vaccines = document.getElementById('gmDetailsVaccines');
                const depart = document.getElementById('gmDetailsDepart');
                const last = document.getElementById('gmDetailsLast');
                const behavior = document.getElementById('gmDetailsBehavior');
                const notes = document.getElementById('gmDetailsNotes');

                const programName = document.getElementById('gmDetailsProgramName');
                const programPct = document.getElementById('gmDetailsProgramPct');
                const programFill = document.getElementById('gmDetailsProgramFill');
                const programMeta = document.getElementById('gmDetailsProgramMeta');
                const programFoot = document.getElementById('gmDetailsProgramFoot');

                function safeText(el, v) {
                    if (!el) return;
                    el.textContent = (v && v.toString().trim() !== '') ? v.toString() : '-';
                }

                function openModal() {
                    modal.classList.add('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('gm-lock');
                }

                function closeModal() {
                    modal.classList.remove('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('gm-lock');
                }

                function render(pet) {
                    const initials = (pet.initials || '').toString() || ((pet.name || '').toString().slice(0, 2).toUpperCase());
                    safeText(avatar, initials);
                    safeText(name, pet.name);

                    const subText = [pet.breed || '', pet.age ? `${pet.age} años` : '', pet.sex || '']
                        .filter((x) => (x || '').toString().trim() !== '')
                        .join(' - ');
                    safeText(sub, subText);

                    safeText(status, pet.status);

                    if (badges) {
                        badges.innerHTML = '';
                        const tagList = Array.isArray(pet.tags) ? pet.tags : [];
                        tagList.slice(0, 3).forEach((t) => {
                            const b = document.createElement('span');
                            b.className = 'gm-details-badge';
                            b.textContent = t;
                            badges.appendChild(b);
                        });
                    }

                    safeText(tutor, pet.tutor);
                    safeText(phone, pet.phone);
                    safeText(address, pet.address);
                    safeText(ingreso, pet.ingreso);
                    safeText(sex, pet.sex);

                    safeText(vaccines, pet.vaccines);
                    safeText(depart, pet.depart);
                    safeText(last, pet.last);

                    safeText(behavior, pet.behavior);
                    safeText(notes, pet.notes);

                    safeText(programName, pet.service || pet.program);
                    safeText(programMeta, pet.trainer ? `Entrenador: ${pet.trainer}` : '');
                    const pct = typeof pet.progress === 'number' ? pet.progress : 0;
                    if (programPct) programPct.textContent = `${pct}%`;
                    if (programFill) programFill.style.width = `${pct}%`;
                    safeText(programFoot, pet.program ? `Programa: ${pet.program}` : '');
                }

                grid.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-gm-view="pet"]');
                    if (!btn) return;

                    const raw = btn.dataset && btn.dataset.pet ? btn.dataset.pet : (btn.getAttribute('data-pet') || '{}');
                    let pet = {};
                    try {
                        pet = JSON.parse(raw);
                    } catch (_) {
                        pet = {};
                    }
                    render(pet);
                    openModal();
                });

                modal.addEventListener('click', (e) => {
                    const close = e.target.closest('[data-gm-close="true"]');
                    if (close) closeModal();
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('gm-modal--open')) {
                        closeModal();
                    }
                });
            })();

            (function () {
                const grid = document.getElementById('gmGrid');
                const deleteForm = document.getElementById('gmDeletePetForm');
                const modal = document.getElementById('gmDeleteConfirmModal');
                const confirmText = document.getElementById('gmDeleteConfirmText');
                const confirmBtn = document.getElementById('gmDeleteConfirmBtn');
                if (!grid || !deleteForm || !modal || !confirmBtn) return;

                let pendingDeleteId = '';

                function openModal() {
                    modal.classList.add('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('gm-lock');
                }

                function closeModal() {
                    modal.classList.remove('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'true');
                    pendingDeleteId = '';
                    document.body.classList.remove('gm-lock');
                }

                grid.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-gm-delete="pet"]');
                    if (!btn) return;

                    const id = (btn.getAttribute('data-id') || '').toString().trim();
                    if (!id) return;

                    const name = (btn.getAttribute('data-name') || 'esta mascota').toString().trim();
                    pendingDeleteId = id;
                    if (confirmText) {
                        confirmText.textContent = `Esta acción eliminará el registro de ${name} y todo su historial de entrenamiento.`;
                    }
                    openModal();
                });

                confirmBtn.addEventListener('click', () => {
                    if (!pendingDeleteId) return;
                    deleteForm.action = `/admin/pets/${pendingDeleteId}`;
                    deleteForm.submit();
                });

                modal.addEventListener('click', (e) => {
                    const close = e.target.closest('[data-gm-delete-close="true"]');
                    if (close) closeModal();
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('gm-modal--open')) {
                        closeModal();
                    }
                });
            })();

            (function () {
                const grid = document.getElementById('gmGrid');
                const modal = document.getElementById('gmEditPetModal');
                const form = document.getElementById('gmEditPetForm');
                if (!grid || !modal || !form) return;

                const inputNombre = document.getElementById('gmEditNombre');
                const inputRaza = document.getElementById('gmEditRaza');
                const inputEdad = document.getElementById('gmEditEdad');
                const inputSexo = document.getElementById('gmEditSexo');
                const inputVacunas = document.getElementById('gmEditVacunas');
                const inputDepart = document.getElementById('gmEditDepart');
                const inputLast = document.getElementById('gmEditLast');
                const inputBehavior = document.getElementById('gmEditBehavior');
                const inputService = document.getElementById('gmEditService');
                const inputStatus = document.getElementById('gmEditStatus');
                const inputNotes = document.getElementById('gmEditNotes');
                const inputPhone = document.getElementById('gmEditPhone');
                const photoInput = document.getElementById('gmEditFoto');
                const photoPreview = document.getElementById('gmEditPhotoPreview');
                const initialsEl = document.getElementById('gmEditInitials');

                const storageBase = `{{ asset('storage') }}`;

                function openModal() {
                    modal.classList.add('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('gm-lock');
                }

                function closeModal() {
                    modal.classList.remove('gm-modal--open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('gm-lock');
                }

                function safeSet(el, v) {
                    if (!el) return;
                    el.value = (v === null || v === undefined) ? '' : v;
                }

                function setInitials(v) {
                    if (!initialsEl) return;
                    const t = (v || '').toString().trim();
                    initialsEl.textContent = t !== '' ? t : '--';
                }

                function clearPhotoPreview() {
                    if (!photoPreview) return;
                    photoPreview.removeAttribute('src');
                    photoPreview.classList.remove('gm-photo-preview--on');
                }

                function setPhotoFromPet(pet) {
                    clearPhotoPreview();
                    if (!photoPreview) return;
                    const path = (pet && pet.photo) ? pet.photo.toString().trim() : '';
                    if (!path) return;
                    photoPreview.src = `${storageBase}/${path.replace(/^\//, '')}`;
                    photoPreview.classList.add('gm-photo-preview--on');
                }

                grid.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-gm-edit="pet"]');
                    if (!btn) return;

                    const raw = btn.dataset && btn.dataset.pet ? btn.dataset.pet : (btn.getAttribute('data-pet') || '{}');
                    let pet = {};
                    try {
                        pet = JSON.parse(raw);
                    } catch (_) {
                        pet = {};
                    }

                    if (!pet.id) return;
                    form.setAttribute('action', `{{ url('/admin/pets') }}/${pet.id}`);

                    safeSet(inputNombre, pet.name || '');
                    safeSet(inputRaza, pet.breed || '');
                    safeSet(inputEdad, pet.age || '');
                    safeSet(inputSexo, pet.sex || '');
                    safeSet(inputVacunas, pet.vaccines || '');
                    safeSet(inputDepart, pet.depart || '');
                    safeSet(inputLast, pet.last || '');
                    safeSet(inputBehavior, pet.behavior || '');
                    safeSet(inputService, pet.service || pet.program || '');
                    safeSet(inputStatus, pet.status || 'En Casa');
                    safeSet(inputNotes, pet.notes || '');
                    safeSet(inputPhone, pet.phone || '');

                    if (photoInput) {
                        photoInput.value = '';
                    }
                    setInitials(pet.initials || (pet.name || '').toString().slice(0, 2).toUpperCase());
                    setPhotoFromPet(pet);

                    openModal();
                });

                if (photoInput && photoPreview) {
                    photoInput.addEventListener('change', () => {
                        const file = photoInput.files && photoInput.files[0];
                        if (!file) {
                            clearPhotoPreview();
                            return;
                        }

                        const url = URL.createObjectURL(file);
                        photoPreview.src = url;
                        photoPreview.classList.add('gm-photo-preview--on');
                    });
                }

                modal.addEventListener('click', (e) => {
                    const close = e.target.closest('[data-gm-close="true"]');
                    if (close) closeModal();
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('gm-modal--open')) {
                        closeModal();
                    }
                });
            })();

            (function () {
                function normalizeList(v) {
                    return (v || '').toString().trim();
                }

                function parseVaccines(raw) {
                    const t = normalizeList(raw);
                    if (!t) return [];
                    return t.split(/[;\n,]+/).map(s => s.trim()).filter(Boolean);
                }

                function syncCheckboxesToInput(gridEl, inputEl) {
                    if (!gridEl || !inputEl) return;
                    const checks = Array.from(gridEl.querySelectorAll('input[type="checkbox"]'));
                    const selected = checks.filter(c => c.checked).map(c => (c.value || '').toString().trim()).filter(Boolean);
                    inputEl.value = selected.join('; ');
                }

                function enforceNoneExclusive(gridEl) {
                    if (!gridEl) return;
                    const none = gridEl.querySelector('input[type="checkbox"][value="Ninguna"]');
                    const others = Array.from(gridEl.querySelectorAll('input[type="checkbox"]')).filter(c => c !== none);
                    if (!none) return;

                    none.addEventListener('change', () => {
                        if (none.checked) {
                            others.forEach(c => { c.checked = false; });
                        }
                    });

                    others.forEach(c => {
                        c.addEventListener('change', () => {
                            if (c.checked) none.checked = false;
                        });
                    });
                }

                function wireVaccines(gridId, inputId) {
                    const gridEl = document.getElementById(gridId);
                    const inputEl = document.getElementById(inputId);
                    if (!gridEl || !inputEl) return;

                    enforceNoneExclusive(gridEl);
                    gridEl.addEventListener('change', () => syncCheckboxesToInput(gridEl, inputEl));
                    syncCheckboxesToInput(gridEl, inputEl);
                }

                function setChecksFromInput(gridEl, inputEl) {
                    if (!gridEl || !inputEl) return;
                    const values = new Set(parseVaccines(inputEl.value));
                    const checks = Array.from(gridEl.querySelectorAll('input[type="checkbox"]'));
                    checks.forEach(c => {
                        const val = (c.value || '').toString().trim();
                        c.checked = values.has(val);
                    });
                    syncCheckboxesToInput(gridEl, inputEl);
                }

                wireVaccines('gmVacunasGrid', 'gmVacunasInput');
                wireVaccines('gmEditVacunasGrid', 'gmEditVacunas');

                const grid = document.getElementById('gmGrid');
                const modal = document.getElementById('gmEditPetModal');
                if (grid && modal) {
                    grid.addEventListener('click', (e) => {
                        const btn = e.target.closest('[data-gm-edit="pet"]');
                        if (!btn) return;
                        setTimeout(() => {
                            const gridEl = document.getElementById('gmEditVacunasGrid');
                            const inputEl = document.getElementById('gmEditVacunas');
                            setChecksFromInput(gridEl, inputEl);
                        }, 0);
                    });
                }
            })();
        </script>
    </body>
</html>
