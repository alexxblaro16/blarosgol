<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido al Mundial FIFA 2026</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background:#2f3437; padding:32px; color:#1f1f1f;">
    <div style="max-width:560px; margin:0 auto; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 6px 24px rgba(0,0,0,.18);">
        <div style="background:linear-gradient(135deg,#1b8a3a,#f7c948); padding:28px 32px;">
            <h1 style="color:#fff; margin:0; font-size:24px; letter-spacing:.5px;">¡Bienvenido al Mundial FIFA 2026!</h1>
            <p style="color:#fff; margin:8px 0 0; opacity:.9;">Sistema de predicciones</p>
        </div>
        <div style="padding:28px 32px;">
            <p>Hola <strong>{{ $name }}</strong>,</p>
            <p>Tu cuenta se ha creado correctamente. Estas son tus credenciales de acceso:</p>
            <table style="width:100%; border-collapse:collapse; margin:18px 0;">
                <tr>
                    <td style="padding:10px 14px; background:#f1f1f1; border-radius:6px 0 0 6px;">Email</td>
                    <td style="padding:10px 14px; background:#f7c948; border-radius:0 6px 6px 0; font-weight:600;">{{ $email }}</td>
                </tr>
                <tr><td colspan="2" style="height:6px;"></td></tr>
                <tr>
                    <td style="padding:10px 14px; background:#f1f1f1; border-radius:6px 0 0 6px;">Contraseña</td>
                    <td style="padding:10px 14px; background:#1b8a3a; color:#fff; border-radius:0 6px 6px 0; font-weight:600;">{{ $password }}</td>
                </tr>
            </table>
            <p style="font-size:13px; color:#666;">Guarda esta contraseña en un lugar seguro. Puedes cambiarla cuando quieras desde tu perfil.</p>
            <a href="{{ config('app.url') }}" style="display:inline-block; margin-top:12px; padding:12px 22px; background:#1b8a3a; color:#fff; text-decoration:none; border-radius:8px; font-weight:600;">Entrar al panel</a>
        </div>
        <div style="background:#2f3437; color:#cbd2d3; padding:16px 32px; font-size:12px; text-align:center;">
            Mundial FIFA 2026 — Sistema de predicciones · UDIT Back-End I
        </div>
    </div>
</body>
</html>
