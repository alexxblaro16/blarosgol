# BlarosGol · Guía rápida del estado

**Proyecto:** Mundial FIFA 2026 — API de predicciones (Back-End I, UDIT)
**Estado al 18/05/2026 11:55:** levantado y funcionando localmente.

---

## Cómo está arrancado

5 contenedores Docker corriendo (`docker compose ps`):

| Servicio | Estado | URL / Puerto |
|---|---|---|
| **mundial_nginx** | Up | http://localhost:8002 |
| **mundial_app** (PHP-FPM 8.3) | Up | interno (9000) |
| **mundial_mysql** (8.4) | Up | localhost:3307 |
| **mundial_redis** (7.2) | Up | localhost:6380 |
| **mundial_mailhog** | Up | http://localhost:8025 |

**App:** http://localhost:8002 → SPA Blade/Alpine.js con paleta verde/amarillo/gris.
**MailHog:** http://localhost:8025 → muestra los emails de bienvenida tras `/api/register`.

---

## Credenciales seed

| Rol | Email | Password |
|---|---|---|
| **Admin** | admin@mundial2026.test | admin1234 |
| Usuario | alejandro@mundial2026.test | alex1234 |
| Usuario | rodrigo@mundial2026.test | rodri1234 |
| Usuario | manuel@mundial2026.test | manu1234 |

---

## Qué hay en la base de datos

- **4 usuarios** (1 admin + 3 normales)
- **7 fases del torneo:** GROUP (actual), R32, R16, QF, SF, TPP, F
- **72 partidos** de la fase de grupos del Mundial FIFA 2026 (sorteo oficial 5/dic/2025)
  - 12 grupos × 6 partidos cada uno
  - Sedes reales de las 16 ciudades USA/Canadá/México
  - **España (Grupo H):** 3 partidos con fechas y sedes OFICIALES
    - 15/jun · España–Cabo Verde · Mercedes-Benz Stadium, Atlanta
    - 21/jun · España–Arabia Saudí · Mercedes-Benz Stadium, Atlanta
    - 26/jun · Uruguay–España · Estadio Akron, Guadalajara
- **1 comunidad demo** ("Quiniela UDIT", creador = Alejandro, Rodrigo aceptado, Manu pendiente)

---

## Endpoints de la API

Todas las respuestas siguen el formato unificado:
```json
{"success": true|false, "message": "...", "data": {...}|null, "errors": {...}|null}
```

### Públicos
| Método | Ruta | Para qué |
|---|---|---|
| POST | `/api/register` | Crea usuario · envía mail bienvenida |
| POST | `/api/login` | Devuelve token Sanctum |

### Autenticados (header `Authorization: Bearer <token>`)
| Método | Ruta | Para qué |
|---|---|---|
| GET | `/api/me` | Datos del usuario + total de puntos |
| POST | `/api/logout` | Invalida el token actual |
| GET | `/api/phases` | Lista las fases del torneo |
| GET | `/api/matches` | Todos los partidos (filtros `?phase=GROUP`, `?played_only=1`) |
| GET | `/api/matches/{id}` | Detalle de un partido |
| GET | `/api/predictions` | Mis predicciones (todas las fases) |
| POST | `/api/predictions` | Crear predicción (solo fase actual) |
| PATCH | `/api/predictions/{id}` | Modificar (hasta el día antes del partido) |
| DELETE | `/api/predictions/{id}` | Borrar |
| GET | `/api/communities` | Comunidades mías (creadas + en las que soy miembro) |
| POST | `/api/communities` | Crear comunidad |
| GET | `/api/communities/{id}` | Ver una comunidad |
| POST | `/api/communities/{code}/join` | Solicitar unirme por código |
| GET | `/api/communities/{id}/requests` | Solicitudes pendientes (solo creador) |
| POST | `/api/communities/{id}/requests/{userId}/accept` | Aceptar (solo creador) |
| DELETE | `/api/communities/{id}/members/{userId}` | Eliminar miembro (solo creador) |
| GET | `/api/communities/{id}/ranking` | Ranking ordenado por puntos |

### Admin (token + `is_admin = true`)
| Método | Ruta | Para qué |
|---|---|---|
| GET | `/api/admin/phases` | Lista fases (igual que público) |
| POST | `/api/admin/phases/{code}/activate` | Marcar fase como actual |
| POST | `/api/admin/matches/import` | Importar fichero CSV/JSON o array de partidos |
| PATCH | `/api/admin/matches/{id}/result` | Actualizar resultado → recalcula puntos (observer) |

---

## Sistema de puntuación

- **3 puntos** · resultado exacto
- **1 punto** · solo el ganador (incluido empate)
- **0 puntos** · fallo

Recálculo automático: cuando el admin mete un resultado, el `MatchObserver` recorre las predicciones de ese partido y guarda sus puntos.

---

## Flujo de prueba en 90 segundos

1. Abrir http://localhost:8002
2. Login como **admin** → ver panel admin → activar fase (ya está GROUP)
3. Logout → login como **alejandro** → ir a Partidos → predecir un España vs Cabo Verde con `2 - 0`
4. Logout → login como **admin** → en panel admin → meter resultado `2 - 0` al partido España vs Cabo Verde
5. Logout → login como **alejandro** → ir a Mis Quinielas → ver **3 pts en verde**
6. Crear comunidad nueva → copiar código → logout → login como **rodrigo** → unirme con código
7. Logout → login como **alejandro** → aceptar solicitud → ver ranking con podium top 3

---

## Comandos útiles

```bash
# Logs en vivo
docker compose logs -f app

# Reset total de la base de datos (vuelve al estado inicial)
docker compose exec app php artisan migrate:fresh --seed

# Parar todo
docker compose down

# Volver a arrancar sin reconstruir
docker compose up -d
```

---

## Repo de GitHub

https://github.com/alexxblaro16/blarosgol
