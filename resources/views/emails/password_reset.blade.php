<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Recuperación de Contraseña</title>
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            background-color: #002741;
            padding: 32px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin: 0;
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
            margin-bottom: 16px;
        }
        p {
            margin-top: 0;
            margin-bottom: 24px;
            font-size: 15px;
        }
        .button-container {
            text-align: center;
            margin: 32px 0;
        }
        .button {
            display: inline-block;
            background-color: #006b60;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 107, 96, 0.2);
            transition: background-color 0.2s;
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px 32px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .footer a {
            color: #006b60;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 24px 0;
        }
        .link-text {
            font-size: 13px;
            word-break: break-all;
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
                <h1>Solicitud de Restablecimiento de Contraseña</h1>
                <p>Hola,</p>
                <p>Recibiste este correo electrónico porque se realizó una solicitud de restablecimiento de contraseña para tu cuenta en nuestra Bolsa Laboral.</p>
                
                <div class="button-container">
                    <a href="{{ $link }}" class="button" target="_blank">Restablecer Contraseña</a>
                </div>

                <p>Este enlace de restablecimiento de contraseña expirará en <strong>60 minutos</strong>.</p>
                <p>Si no realizaste esta solicitud, puedes ignorar este mensaje de forma segura; tu contraseña no cambiará.</p>
                
                <div class="divider"></div>
                
                <p class="link-text">Si tienes problemas para hacer clic en el botón "Restablecer Contraseña", copia y pega la siguiente URL en tu navegador web:</p>
                <p class="link-text"><a href="{{ $link }}" style="color: #006b60;">{{ $link }}</a></p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} Bolsa Laboral. Todos los derechos reservados.</p>
                <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            </div>
        </div>
    </div>
</body>
</html>
