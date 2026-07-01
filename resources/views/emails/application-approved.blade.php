<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de tu postulación</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
        .header { background: #002741; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 600; }
        .badge { display: inline-block; padding: 8px 20px; border-radius: 20px; font-weight: 700; font-size: 14px; margin: 10px 0; }
        .badge-accepted { background: #dcfce7; color: #166534; }
        .badge-other { background: #fef3c7; color: #92400e; }
        .body { padding: 30px; color: #333333; }
        .body p { font-size: 15px; line-height: 1.6; margin: 0 0 16px 0; }
        .details { background: #f8fafc; border-radius: 8px; padding: 20px; margin: 20px 0; border: 1px solid #e2e8f0; }
        .details dt { font-weight: 600; font-size: 13px; color: #64748b; margin-top: 12px; }
        .details dt:first-child { margin-top: 0; }
        .details dd { margin: 4px 0 0 0; font-size: 15px; color: #1e293b; }
        .feedback-box { background: #f0f9ff; border-left: 4px solid #0284c7; padding: 16px; border-radius: 6px; margin: 16px 0; font-style: italic; color: #0c4a6e; }
        .footer { padding: 20px 30px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; padding: 12px 28px; background: #002741; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Estado de tu postulación</h1>
        </div>
        <div class="body">
            <p>Hola, <strong>{{ $studentName }}</strong>:</p>
            <p>El estado de tu postulación ha sido actualizado. A continuación, los detalles:</p>

            <div style="text-align: center;">
                <span class="badge {{ $approvalStatus === 'Aceptada' ? 'badge-accepted' : 'badge-other' }}">
                    {{ $approvalStatus }}
                </span>
            </div>

            <div class="details">
                <dl>
                    <dt>Oferta laboral</dt>
                    <dd>{{ $offerTitle }}</dd>

                    <dt>Empresa</dt>
                    <dd>{{ $companyName }}</dd>
                </dl>
            </div>

            @if($feedback)
                <div class="feedback-box">
                    <strong>Retroalimentación:</strong><br>
                    {{ $feedback }}
                </div>
            @endif

            <p style="text-align: center;">
                <a href="{{ url('/student/dashboard') }}" class="btn">Ir a mi panel</a>
            </p>

            <p>Puedes ingresar al sistema para ver más detalles sobre tus postulaciones.</p>

            <p>Atentamente,<br>El equipo de Bolsa Laboral</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bolsa Laboral. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
