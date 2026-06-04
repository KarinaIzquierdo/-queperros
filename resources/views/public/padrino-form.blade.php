<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apadrinar a {{ $dog->nombre }} - Más que Perros</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=lilita-one:400" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
    <style>
        .padrino-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            padding: 2rem 1rem;
        }
        .padrino-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 900px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .padrino-info {
            background: #9b59b6;
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        .padrino-info img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 4px solid rgba(255,255,255,0.3);
        }
        .padrino-info h1 {
            font-family: 'Lilita One', cursive;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .padrino-form-container {
            padding: 3rem;
        }
        .padrino-title {
            font-family: 'Lilita One', cursive;
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 2rem;
            text-align: center;
        }
        .plan-options {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .plan-option {
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .plan-option:hover {
            border-color: #9b59b6;
            background: #fdf4ff;
        }
        .plan-option.selected {
            border-color: #9b59b6;
            background: #f5e1ff;
        }
        .plan-option input {
            display: none;
        }
        .plan-name {
            display: block;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .plan-price {
            display: block;
            font-family: 'Lilita One', cursive;
            font-size: 1.2rem;
            color: #9b59b6;
        }
        .plan-description-box {
            background: #f1f5f9;
            border-radius: 15px;
            padding: 1.2rem;
            margin-bottom: 2rem;
            border-left: 5px solid #9b59b6;
        }
        .plan-description-box h4 {
            margin: 0 0 0.5rem 0;
            color: #2c3e50;
            font-size: 0.95rem;
            font-weight: 800;
        }
        .plan-description-box p {
            margin: 0;
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.4;
        }
        .btn-padrino {
            width: 100%;
            padding: 1rem;
            background: #9b59b6;
            color: white;
            border: none;
            border-radius: 999px;
            font-family: 'Lilita One', cursive;
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: 0 8px 0 rgba(0,0,0,0.15);
            transition: all 0.2s;
            margin-top: 1rem;
        }
        .btn-padrino:active {
            transform: translateY(4px);
            box-shadow: 0 4px 0 rgba(0,0,0,0.15);
        }
        @media (max-width: 768px) {
            .padrino-card {
                grid-template-columns: 1fr;
            }
            .padrino-info {
                padding: 2rem;
            }
            .padrino-form-container {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="padrino-wrap">
        <div class="padrino-card">
            <div class="padrino-info">
                @php
                    $photo = $dog->foto ? asset('storage/' . ltrim($dog->foto, '/')) : asset('img/pet.png');
                @endphp
                <img src="{{ $photo }}" alt="{{ $dog->nombre }}">
                <h1>{{ $dog->nombre }}</h1>
                <p>{{ $dog->historia }}</p>
                <p style="margin-top: 2rem; font-size: 0.9rem; opacity: 0.9;">
                    Tu apoyo ayudará a cubrir su alimentación, servicios veterinarios y bienestar.
                </p>
            </div>
            <div class="padrino-form-container">
                <h2 class="padrino-title">¡QUIERO APADRINAR!</h2>
                
                <form action="{{ route('public.padrino.process', $dog) }}" method="POST">
                    @csrf
                    
                    <div class="mq-field">
                        <label for="nombre">Tu Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="mq-input" required placeholder="Ej: Juan Pérez">
                    </div>

                    <div class="mq-field">
                        <label for="email">Tu Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="mq-input" required placeholder="juan@ejemplo.com">
                    </div>

                    <div class="mq-field">
                        <label for="telefono">Teléfono de Contacto</label>
                        <input type="text" name="telefono" id="telefono" class="mq-input" placeholder="Opcional">
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <label style="display: block; font-weight: 800; margin-bottom: 0.8rem;">Escoge tu plan de aporte mensual:</label>
                        <div class="plan-options">
                            <label class="plan-option" id="label-basico">
                                <input type="radio" name="plan" value="basico" checked onchange="updateSelection('basico')">
                                <span class="plan-name">BÁSICO</span>
                                <span class="plan-price">$30.000</span>
                            </label>
                            <label class="plan-option" id="label-cuidador">
                                <input type="radio" name="plan" value="cuidador" onchange="updateSelection('cuidador')">
                                <span class="plan-name">CUIDADOR</span>
                                <span class="plan-price">$50.000</span>
                            </label>
                            <label class="plan-option" id="label-protector">
                                <input type="radio" name="plan" value="protector" onchange="updateSelection('protector')">
                                <span class="plan-name">PROTECTOR</span>
                                <span class="plan-price">$100.000</span>
                            </label>
                        </div>

                        <div class="plan-description-box">
                            <h4>¿Qué cubre este plan?</h4>
                            <p id="plan-desc">Selecciona un plan para ver los beneficios.</p>
                        </div>
                    </div>

                    <button type="submit" class="btn-padrino">CONTINUAR AL PAGO</button>
                    
                    <p style="text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: #64748b;">
                        Serás redirigido a la pasarela segura de Mercado Pago.
                    </p>
                    
                    <div style="text-align: center; margin-top: 1rem;">
                        <a href="{{ url('/') }}" style="color: #9b59b6; text-decoration: none; font-size: 0.9rem; font-weight: 700;">← Volver al inicio</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const descriptions = {
            'basico': 'Cubre alimentación balanceada y snacks saludables por una semana completa para el perrito.',
            'cuidador': 'Cubre alimentación mensual completa, desparasitación básica y atención preventiva para garantizar su salud.',
            'protector': 'Cubre alimentación premium, salud completa (vacunas), higiene, juguetes y bienestar integral durante todo el mes.'
        };

        function updateSelection(plan) {
            document.querySelectorAll('.plan-option').forEach(opt => opt.classList.remove('selected'));
            document.getElementById('label-' + plan).classList.add('selected');
            document.getElementById('plan-desc').textContent = descriptions[plan];
        }
        // Init selection
        updateSelection('basico');
    </script>
</body>
</html>
