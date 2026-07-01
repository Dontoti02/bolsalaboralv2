<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulación Enviada</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        .wrapper {
            width: 100%;
            background-color: #f3f4f6;
            padding: 40px 0;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #002741;
            padding: 32px;
            text-align: center;
            border-bottom: 4px solid #006b60;
        }
        .logo {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .body {
            padding: 40px 32px;
            color: #374151;
            line-height: 1.6;
        }
        h1 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-top: 0;
            margin-bottom: 8px;
            text-align: center;
        }
        .subtitle {
            font-size: 14px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 32px;
        }
        p {
            margin-top: 0;
            margin-bottom: 24px;
            font-size: 15px;
        }
        .details-card {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid #f3f4f6;
        }
        .detail-row {
            margin-bottom: 16px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 15px;
            font-weight: 500;
            color: #1f2937;
        }
        .action-container {
            text-align: center;
            margin: 32px 0;
        }
        .btn-primary {
            display: inline-block;
            background-color: #006b60;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 107, 96, 0.15);
            transition: background-color 0.2s;
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px 32px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="logo">Bolsa Laboral</div>
            </div>
            <div class="body">
                <h1>✅ Postulación Enviada con Éxito</h1>
                <div class="subtitle">Has completado tu proceso de postulación correctamente.</div>
                
                <p>Hola, <strong>{{ $studentName }}</strong>:</p>
                <p>Te confirmamos que tu postulación ha sido enviada con éxito a la empresa. A continuación, te mostramos el resumen de tu postulación:</p>
                
                <div class="details-card">
                    <div class="detail-row">
                        <div class="detail-label">Oferta Laboral</div>
                        <div class="detail-value" style="font-weight: 600; color: #006b60;">{{ $offerTitle }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Empresa</div>
                        <div class="detail-value">{{ $companyName }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Fecha de Envío</div>
                        <div class="detail-value">{{ $applicationDate }}</div>
                    </div>
                </div>

                <div class="action-container">
                    <a href="{{ url('/student/dashboard') }}" class="btn-primary">Ver mis Postulaciones</a>
                </div>

                <p style="font-size: 13px; color: #6b7280; text-align: center; margin-top: 24px;">Te notificaremos a través de correo electrónico cuando la empresa actualice el estado de tu proceso de selección.</p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} Bolsa Laboral. Todos los derechos reservados.</p>
                <p>Este es un correo automatizado de confirmación. Por favor, no responda directamente a este mensaje.</p>
            </div>
        </div>
    </div>
</body>
</html>
