<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa verificada</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: #002741; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 600; }
        .badge { display: inline-block; padding: 8px 20px; border-radius: 20px; font-weight: 700; font-size: 14px; margin: 10px 0; background: #dcfce7; color: #166534; }
        .body { padding: 30px; color: #333333; }
        .body p { font-size: 15px; line-height: 1.6; margin: 0 0 16px 0; }
        .details { background: #f8fafc; border-radius: 8px; padding: 20px; margin: 20px 0; border: 1px solid #e2e8f0; }
        .details dt { font-weight: 600; font-size: 13px; color: #64748b; margin-top: 12px; }
        .details dt:first-child { margin-top: 0; }
        .details dd { margin: 4px 0 0 0; font-size: 15px; color: #1e293b; }
        .info-box { background: #f0f9ff; border-left: 4px solid #0284c7; padding: 16px; border-radius: 6px; margin: 16px 0; color: #0c4a6e; font-size: 14px; line-height: 1.5; }
        .footer { padding: 20px 30px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; padding: 12px 28px; background: #002741; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bolsa Laboral</h1>
        </div>
        <div class="body">
            <p>Hola, <strong>{{ $company->name }}</strong>:</p>

            <div style="text-align: center;">
                <span class="badge">Empresa Verificada</span>
            </div>

            <p>Nos complace informarte que tu empresa ha sido <strong>verificada exitosamente</strong> por nuestro equipo administrativo.</p>

            <div class="details">
                <dl>
                    <dt>Empresa</dt>
                    <dd>{{ $company->name }}</dd>

                    <dt>RUC</dt>
                    <dd>{{ $company->ruc }}</dd>
                </dl>
            </div>

            <div class="info-box">
                <strong>¿Qué sigue?</strong><br>
                Ahora puedes ingresar a tu panel de empresa y comenzar a <strong>publicar ofertas laborales</strong>. Los estudiantes y egresados podrán ver tus ofertas y postularse directamente.
            </div>

            <p style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Ingresar al sistema</a>
            </p>

            <p>Si tienes alguna consulta, no dudes en contactarnos.</p>

            <p>Atentamente,<br>El equipo de Bolsa Laboral</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bolsa Laboral. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
