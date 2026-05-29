# Configuración de MercadoPago para Pagos

Este proyecto utiliza MercadoPago como pasarela de pagos para el Plan Padrino y servicios del dueño.

## Pasos de Configuración

### 1. Crear cuenta en MercadoPago

1. Ve a [https://www.mercadopago.com.co/developers/panel](https://www.mercadopago.com.co/developers/panel)
2. Regístrate o inicia sesión
3. Crea una nueva aplicación

### 2. Obtener credenciales

1. En el panel de desarrolladores, selecciona tu aplicación
2. Copia el **Access Token** (Producción o Sandbox)
3. Copia el **Public Key** (Producción o Sandbox)
4. Configura el Webhook URL: `https://tu-dominio.com/payment/webhook`

### 3. Configurar en el proyecto

1. Abre el archivo `.env` en tu proyecto
2. Agrega las siguientes variables:

```env
MERCADOPAGO_ACCESS_TOKEN=tu_access_token_aqui
MERCADOPAGO_PUBLIC_KEY=tu_public_key_aqui
MERCADOPAGO_WEBHOOK_SECRET=tu_secreto_webhook_aqui
MERCADOPAGO_ENVIRONMENT=sandbox
```

**Nota:** Usa `sandbox` para pruebas y `production` para pagos reales.

### 4. Configurar HTTPS en Hostinger

MercadoPago requiere HTTPS para funcionar correctamente:

1. En el panel de Hostinger, ve a "Dominios"
2. Selecciona tu dominio
3. Activa "SSL/HTTPS gratuito" de Let's Encrypt
4. Fuerza HTTPS en tu aplicación agregando esto en `AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\URL;

public function boot(): void
{
    if (app()->environment('production')) {
        URL::forceScheme('https');
    }
}
```

### 5. Configurar Webhook en MercadoPago

1. En el panel de MercadoPago, ve a la sección "Webhooks"
2. Agrega la URL: `https://tu-dominio.com/payment/webhook`
3. Selecciona los eventos:
   - `payment.created`
   - `payment.updated`
4. Guarda la configuración

## Flujo de Pagos

### Plan Padrino
1. El usuario hace clic en "Apadrinar con MercadoPago"
2. Se crea una preferencia de pago en MercadoPago
3. El usuario es redirigido al checkout de MercadoPago
4. Después del pago, MercadoPago redirige a `/payment/success/sponsorship/{id}`
5. El webhook actualiza el estado del pago en la base de datos

### Servicios del Dueño
1. El usuario hace clic en "Pagar" en mis-servicios
2. Se crea una preferencia de pago en MercadoPago
3. El usuario es redirigido al checkout de MercadoPago
4. Después del pago, MercadoPago redirige a `/payment/success/service/{id}`
5. El webhook actualiza el estado del pago y marca el servicio como pagado

## Pruebas

Para probar en modo Sandbox:
1. Usa las credenciales de Sandbox de MercadoPago
2. MercadoPago proporciona tarjetas de prueba en su documentación
3. No se realizarán cargos reales

## Soporte

- Documentación de MercadoPago: https://www.mercadopago.com.co/developers/es/docs
- Soporte Hostinger: https://support.hostinger.com/es
