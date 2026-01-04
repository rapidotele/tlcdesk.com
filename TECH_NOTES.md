# Notas Técnicas de Auditoría

Fecha: 2024-10-26
Branch Audited: `main`

## 1. Estructura de Directorios
El proyecto sigue una estructura MVC custom bien definida:
- `/public` es el webroot. `index.php` carga `../app/bootstrap.php`.
- `/app` contiene la lógica. `Core` es el mini-framework.
- `/storage` requiere permisos de escritura (755/777 dependiendo del handler PHP).

**Hallazgo:** La carpeta `public/uploads` tiene `.htaccess` de seguridad, pero no se está usando activamente en el código actual (no hay subida de avatares ni docs).

## 2. Base de Datos
- Schema definido en `database/migrations/001_init.sql`.
- **Riesgo:** `users.email` tiene `UNIQUE KEY unique_email_org (organization_id, email)`. Esto permite el mismo email en diferentes organizaciones. El login actual (`UserRepository::findByEmail`) hace `SELECT * FROM users WHERE email = ?` y `stmt->fetch()`, lo cual devolverá solo el *primer* usuario encontrado.
    - **Acción Requerida:** Si se permite multi-tenancy real con el mismo email, el login debe pedir "Organization ID" o el usuario debe tener un panel de selección de cuenta post-login. Para MVP, se asume unicidad global de facto o el login es ambiguo.

## 3. Seguridad
- **CSRF:** Implementado en `Session::verifyCsrf` y usado en `AuthController`, `BookkeepingController`, `VehicleController`, `DriverController`. ✅
- **SQL Injection:** Uso consistente de PDO Prepared Statements. ✅
- **XSS:** Helpers `e()` usados en las vistas revisadas. ✅
- **Rate Limiting:** Tabla `login_attempts` existe en SQL, pero **NO** hay lógica en `AuthController` que la use. Es una vulnerabilidad de fuerza bruta pendiente.

## 4. Frontend & Themes
- Sistema de themes simple: `App\Core\View` busca en `app/Themes/{theme}/views`.
- Dependencia externa: Tailwind CSS via CDN (`cdn.tailwindcss.com`).
    - **Nota:** Para producción "enterprise", se recomienda descargar el CSS o tener un fallback local por si la CDN falla o hay restricciones de red interna (raro en shared hosting público, pero posible).
- JS: Vanilla JS + Alpine (no visto explicitamente pero recomendado) o scripts inline. `maps/index.php` usa Leaflet CDN.

## 5. Queue System
- `bin/console queue:work` usa un loop infinito con `sleep`.
- **Compatibilidad:** Usa estrategia `UPDATE ... locked_at=NOW() ... LIMIT 1` (Optimistic Locking) en lugar de `SKIP LOCKED`. Compatible con MySQL 5.7. Correcto.
- **Riesgo:** En hosting compartido, los procesos de larga duración (`loop infinito`) a menudo son matados por el "Process Killer" del cPanel (LVE limits).
    - **Recomendación:** Modificar `bin/console` para aceptar un flag `--run-once` o `--max-time` para ser ejecutado por Cron cada minuto en lugar de ser un daemon persistente, si el hosting mata procesos.

## 6. Configuración
- `.env` se carga via `App\Core\Env`.
- `config/config.php` existe pero no parece ser usado intensivamente en el código (se usa `Env::get` directo mayormente). Unificar acceso a través de config mejoraría mantenibilidad.

## 7. Puntos de Extensión
- `BaseRepository` filtra automáticamente por `organization_id` si se setea. Esto es robusto, pero requiere disciplina del desarrollador en cada Controller (`$repo->setOrganizationId(...)`).
- **Middleware:** `TenantMiddleware` verifica integridad de sesión, lo cual es bueno, pero no inyecta el ID en los repositorios automáticamente (ya que no hay Container DI).
