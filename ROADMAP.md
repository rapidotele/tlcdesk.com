# Roadmap de Finalización - TLCDesk

Este documento detalla el plan para llevar TLCDesk v0.5 (MVP) a v1.0 (Producción), basado en la auditoría del código actual.

## Estado Actual (v0.5)
- ✅ **Core Framework:** Router, Database, View, Session, Env custom.
- ✅ **Auth & Multi-tenancy:** Login, Registro, TenantMiddleware.
- ✅ **Driver Portal:** Dashboard básico, Bookkeeping (Ingresos/Gastos).
- ✅ **Fleet Portal:** Dashboard básico, CRUD Vehículos, Listado Drivers.
- ✅ **Infrastructure:** Queue (MySQL 5.7+ compatible), I18n (EN/ES), Installer.
- ❌ **Faltantes Críticos:** Admin Panel real, Gestión completa de usuarios (invitaciones), Alertas, Reportes CSV, Theme Manager UI.

## Fase 1: Consolidación y Gestión de Flotas (Prioridad Alta)
Objetivo: Que los dueños de flota puedan operar realmente (invitar conductores, asignar vehículos).

1.  **Driver Management (Fleet Owner)**
    - [ ] `DriverController`: Implementar lógica de invitación (enviar email con token o crear con password temporal).
    - [ ] `UserRepository`: Asegurar unicidad de email manejable o flujo de "unirse a flota".
    - [ ] Vista `owner/drivers/create.php`: Mejorar UX, no pedir password si es invitación por email (requiere servicio de mail).

2.  **Vehicle Assignment**
    - [ ] Crear `AssignmentController` y `AssignmentRepository`.
    - [ ] UI para asignar Driver <-> Vehicle (con validación de fechas).
    - [ ] Historial de asignaciones en ficha de vehículo y driver.

3.  **Emails & Notifications**
    - [ ] Implementar servicio de Email simple (`mail()` wrapper configurable SMTP).
    - [ ] Job `SendEmailJob`.

## Fase 2: Admin Panel y Gestión del Sistema
Objetivo: Control total para el Superadmin.

1.  **Admin Dashboard Real**
    - [ ] Métricas reales en `Admin\DashboardController` (Total Tenants, Revenue, Jobs Health).
    - [ ] CRUD de Tenants (Organizations): Listar, Suspender, Editar límites.

2.  **Theme Manager & Settings**
    - [ ] UI para listar themes en `/app/Themes/`.
    - [ ] UI para subir .zip de theme (validación estricta de archivos).
    - [ ] Settings globales (tabla `settings`): SMTP config, App Name, Default Locale.

3.  **Logs & Audit**
    - [ ] Visualizador de logs (`storage/logs/app.log`) en Admin Panel.
    - [ ] Tabla `audit_logs`: Registrar acciones críticas (login, delete, update settings).

## Fase 3: Features Avanzadas y Estabilidad
Objetivo: Valor añadido para el usuario final.

1.  **Alerts System**
    - [ ] Cron Job que chequee `driver_profiles` (TLC/DMV expiration) y `vehicles` (Inspection/Registration).
    - [ ] Generar registros en tabla `alerts` y notificaciones email.
    - [ ] Widget de Alertas en Dashboard.

2.  **Reportes y Exportación**
    - [ ] `ExportJob`: Generar CSV de transacciones/vehículos en background.
    - [ ] UI para descargar exportaciones en `/storage/exports/`.

3.  **Hardening de Seguridad**
    - [ ] Implementar Rate Limiting real en `AuthController` (usando tabla `login_attempts`).
    - [ ] Revisión de validaciones de input en todos los formularios.

## Fase 4: Despliegue y Docs
1.  **Documentación Final**
    - [ ] Manual de usuario (PDF/HTML) en `/public/docs`.
    - [ ] Video tutorial de instalación cPanel.

2.  **Final Polish**
    - [ ] Traducciones faltantes (ES).
    - [ ] Error pages (404, 500) personalizadas.
