# Mundial FIFA 2026 — API de predicciones

Proyecto final de **Desarrollo Web: Back-End I** (UDIT). Sistema de predicciones del Mundial FIFA 2026 con autenticación, comunidades, ranking, importación de partidos y sistema de puntuación.

**Autor:** Alejandro Blanco
**Stack:** Laravel 12 · PHP 8.3 · MySQL 8.4 · Sanctum · Docker · Alpine.js

---

## Arranque rápido

```bash
docker compose up -d --build
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

Aplicación: **http://localhost:8002**
MailHog (capturador de emails): **http://localhost:8025**

### Credenciales de prueba

| Rol     | Email                          | Password   |
|---------|--------------------------------|------------|
| Admin   | admin@mundial2026.test         | admin1234  |
| Usuario | alejandro@mundial2026.test     | alex1234   |
| Usuario | rodrigo@mundial2026.test       | rodri1234  |
| Usuario | manuel@mundial2026.test        | manu1234   |

---

## Cumplimiento del enunciado

### Usuarios
- [x] Registro con envío de email de bienvenida (`WelcomeMail`, plantilla en `resources/views/emails/welcome.blade.php`).
- [x] Sin acceso a datos ajenos — `CommunityPolicy` y `PredictionPolicy` + middleware `auth:sanctum`.
- [x] Usuario `Admin` con permisos especiales — middleware `EnsureUserIsAdmin`.

### Comunidades
- [x] `name` + `code` único (8 chars, auto-generado).
- [x] Un usuario puede crear N comunidades.
- [x] Un usuario puede estar en N comunidades.
- [x] Restricción: un usuario no puede crear 2 comunidades con el mismo nombre — `UNIQUE(creator_id, name)`.
- [x] Solo el creador puede aceptar solicitudes y eliminar miembros — `CommunityPolicy::manage`.

### Predicciones
- [x] Solo se permite predecir en la fase actual — chequeo en `PredictionController::store`.
- [x] Las predicciones aparecen cuando hay partidos importados de esa fase.
- [x] El usuario ve todas sus predicciones de cualquier fase — `GET /api/predictions`.
- [x] Modificable hasta el día anterior al partido — `FootballMatch::isLockedForPredictions()`.

### Partidos
- [x] El usuario ve todos los partidos importados — `GET /api/matches`.
- [x] Admin importa fichero (CSV o JSON), uno o varios — `POST /api/admin/matches/import`.
- [x] Admin actualiza resultados — `PATCH /api/admin/matches/{id}/result`.

### Sistema de puntuación
- [x] **3 puntos** resultado exacto.
- [x] **1 punto** ganador acertado (incluido empate).
- [x] **0 puntos** fallo.
- [x] Recálculo automático: `MatchObserver` se dispara al actualizar resultado.

### Ranking
- [x] `GET /api/communities/{id}/ranking` — solo miembros aceptados.

### Transversales
- [x] Estructura JSON unificada para éxito y error (trait `ApiResponse`).
- [x] Códigos HTTP correctos (`201`, `401`, `403`, `404`, `409`, `422`...).
- [x] Código separado por capas: Models · Requests · Policies · Controllers · Services · Observers.

---

## Endpoints API

Todas las respuestas siguen el mismo formato:
```json
{
  "success": true|false,
  "message": "...",
  "data": { ... } | null,
  "errors": { ... } | null
}
```

### Públicos
- `POST /api/register` — crear cuenta + envío de email con credenciales.
- `POST /api/login` — devuelve token Sanctum.

### Autenticados (Bearer token)
- `GET  /api/me`
- `POST /api/logout`
- `GET  /api/matches` — todos los importados (filtros: `?phase=GROUP`, `?played_only=1`).
- `GET  /api/matches/{id}`
- `GET  /api/predictions` — mías, todas las fases.
- `POST /api/predictions` — `{match_id, home_score, away_score}` solo fase actual.
- `PATCH /api/predictions/{id}` — hasta día anterior al partido.
- `DELETE /api/predictions/{id}`
- `GET  /api/communities` — mías (creadas + en las que soy miembro).
- `POST /api/communities` — crear.
- `GET  /api/communities/{id}`
- `POST /api/communities/{code}/join` — solicitar unirse por código.
- `GET  /api/communities/{id}/requests` — solo creador.
- `POST /api/communities/{id}/requests/{userId}/accept` — solo creador.
- `DELETE /api/communities/{id}/members/{userId}` — solo creador.
- `GET  /api/communities/{id}/ranking`

### Admin (Bearer token + `is_admin = true`)
- `GET  /api/admin/phases`
- `POST /api/admin/phases/{code}/activate`
- `POST /api/admin/matches/import` — `file` (CSV/JSON) o `matches` (array).
- `PATCH /api/admin/matches/{id}/result` — `{home_score, away_score}` → recalcula puntos.

---

## Formato del fichero de partidos

**CSV** (`storage/app/imports/partidos-ejemplo.csv`):
```csv
phase_code,home_team,away_team,home_code,away_code,kick_off_at,venue,home_score,away_score
GROUP,España,Polonia,ESP,POL,2026-06-13 18:00:00,"MetLife Stadium",,
```

**JSON** (`storage/app/imports/partidos-ejemplo.json`):
```json
{"matches": [{"phase_code":"GROUP","home_team":"España","away_team":"Polonia","kick_off_at":"2026-06-13 18:00:00"}]}
```

Códigos de fase: `GROUP`, `R32`, `R16`, `QF`, `SF`, `TPP`, `F`.

---

## Frontend

SPA ligero en `resources/views/app.blade.php` con **Alpine.js** y CSS inline. Paleta verde / amarillo / gris (estilo Mundial FIFA). Funcionalidades:

- Login / registro
- Dashboard con próximos partidos, mis comunidades y normas
- Listado de partidos con predicción inline
- Mis predicciones con puntos
- Crear / unirse a comunidades
- Ranking modal por comunidad
- Aceptar solicitudes (vista creador)
- Panel admin: activar fases, importar fichero, actualizar resultados

---

## Decisiones técnicas

1. **Auth: Laravel Sanctum** (tokens). Más ligero que Passport y suficiente para una API + SPA Blade.
2. **Mail por defecto: log driver** (sale en `storage/logs/laravel.log`). En docker hay un MailHog en `:8025` para capturar mails en UI.
3. **Recálculo de puntos: Observer** sobre el modelo `FootballMatch`. Al cambiar `home_score`/`away_score`, recorre `predictions` y guarda `points` con `saveQuietly()` para no disparar otros eventos.
4. **Model "Match" → `FootballMatch`**: `match` es palabra reservada en PHP 8. Tabla sigue siendo `matches`.
5. **Envío de credenciales por email**: el enunciado lo pide explícitamente. En producción real NO se haría (queda en el buzón en claro). Documentado como decisión consciente.
6. **Estructura JSON unificada**: trait `ApiResponse` + render personalizado de excepciones en `bootstrap/app.php`. Todas las rutas `/api/*` devuelven el mismo formato.

---

## Estructura del proyecto

```
mundial-fifa-2026/
├── app/
│   ├── Http/Controllers/Api/    # Auth, Community, Prediction, Match, Admin, Ranking
│   ├── Http/Requests/           # FormRequests por endpoint
│   ├── Http/Middleware/         # EnsureUserIsAdmin
│   ├── Mail/                    # WelcomeMail
│   ├── Models/                  # User, Community, FootballMatch, etc.
│   ├── Observers/               # MatchObserver (recálculo)
│   ├── Policies/                # CommunityPolicy, PredictionPolicy
│   ├── Services/                # MatchImporter (CSV/JSON parser)
│   └── Traits/                  # ApiResponse
├── database/
│   ├── migrations/              # 9 migraciones
│   └── seeders/                 # Admin, 4 users, 7 fases, 16 partidos, comunidad demo
├── resources/views/
│   ├── app.blade.php            # SPA Alpine.js
│   └── emails/welcome.blade.php # Mail de bienvenida
├── routes/
│   ├── api.php                  # Todos los endpoints
│   └── web.php                  # Catch-all SPA
├── storage/app/imports/         # Ficheros de ejemplo
└── docker-compose.yml           # PHP-FPM, Nginx, MySQL, Redis, MailHog
```

