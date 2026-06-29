# Entregable 02 — Laravel From Scratch 2026

| Campo | Valor |
|-------|-------|
| **Estudiante** | Yeison Roberto Hernandez Garita |
| **Curso** | ISW811 |
| **Entregable** | 02 — Autorización, Vite, notificaciones, colas, pruebas y primera parte del proyecto final |
| **Fecha límite** | 29 de junio de 2026 |
| **Episodios cubiertos** | 17 al 30 *(aprox.)* |

---

## Resumen del entregable

En este entregable se documenta la autorización con Gates y Policies, el empaquetado de assets con Vite, notificaciones por correo, trabajos en cola, pruebas automatizadas con Pest y el inicio del proyecto final “Idea”.

---

## Índice de episodios

| # | Episodio | Estado | Enlace |
|---|----------|--------|--------|
| 17 | Authorization Using Gates | Completado | [Episodio 17](#episodio-17) |
| 18 | Authorization Using Policies | Completado | [Episodio 18](#episodio-18) |
| 19 | Frontend Asset Bundling with Vite | Completado | [Episodio 19](#episodio-19) |
| 20 | Notifications | Completado | [Episodio 20](#episodio-20) |
| 21 | When to Queue it Up | Pendiente | — |
| 22 | How to Get Started Testing Your Code | Pendiente | — |
| 23 | Final Project Setup | Pendiente | — |
| 24 | Design Your Model Layer | Pendiente | — |
| 25 | Tailwind Theme Setup And Initial UI | Pendiente | — |
| 26 | Browser Testing Registration Forms With Pest | Pendiente | — |
| 27 | Flash Messaging and Interactivity with AlpineJS | Pendiente | — |
| 28 | Idea Cards | Pendiente | — |
| 29 | Idea Filtering | Pendiente | — |
| 30 | Show A Single Idea | Pendiente | — |

---

## Plantilla por episodio

> Copiar el bloque siguiente por cada episodio y completarlo al avanzar en el curso.

```markdown
## Episodio XX: Nombre del episodio

### Resumen
[Qué se aprendió y qué se implementó.]

### Comandos utilizados
```bash
# comandos relevantes
```

### Archivos modificados o creados
- ruta/archivo.php

### Evidencia
![Episodio XX](./img/epXX-descripcion.png)

### Problemas y soluciones
[Si hubo errores, cómo se resolvieron.]

### Comentarios personales
[Apuntes técnicos.]

### Commit Git
episodio-XX: descripción breve del cambio
```

---

## Episodio 17: Authorization Using Gates {#episodio-17}

### Resumen

Se agregó **autorización con Gates** para controlar acceso tipo rol (p. ej. área admin). Un **Gate** es una regla de autorización definida en `AppServiceProvider` y reutilizable en rutas, controladores y Blade.

### Definir el Gate — `AppServiceProvider`

```php
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Models\User;

public function boot(): void
{
    Gate::define('view-admin', function (User $user) {
        if ($user->id == 1) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    });
}
```

La lógica puede ser cualquier condición sobre `$user` (id, email, rol en BD, etc.).

### Usar el Gate en la UI — `@can`

**`nav.blade.php`**

```blade
@can('view-admin')
    <li><a href="/admin">Admin</a></li>
@endcan
```

Solo usuarios autorizados ven el enlace Admin.

### Proteger la ruta — tres formas

**1. Método `->can()` en la ruta**

```php
Route::get('/admin', function () {
    return 'private admin area';
})->can('view-admin');
```

**2. `Gate::authorize()` dentro del closure**

```php
use Illuminate\Support\Facades\Gate;

Route::get('/admin', function () {
    Gate::authorize('view-admin');
    return 'private admin area';
});
```

Si el usuario no pasa el gate → **403 Forbidden** (*This action is unauthorized*).

**3. Enmascarar 403 como 404**

En la definición del Gate, en lugar de denegar con 403:

```php
return Response::denyAsNotFound();
```

El usuario no autorizado ve **404 Not Found** en `/admin` — no revela que la ruta existe.

| Respuesta | Cuándo usarla |
|-----------|----------------|
| `Response::allow()` | Usuario autorizado |
| Denegación normal | 403 explícito |
| `Response::denyAsNotFound()` | Ocultar recurso (admin, APIs internas) |

### Archivos tocados

`AppServiceProvider.php`, `routes/web.php`, `nav.blade.php`

### Evidencia

![Gate::define y @can en nav](./img/ep17-gate-define-nav.png)

![Ruta protegida con ->can()](./img/ep17-route-can.png)

![Ruta con Gate::authorize()](./img/ep17-gate-authorize-route.png)

![403 — usuario no autorizado](./img/ep17-403-unauthorized.png)

![404 con denyAsNotFound()](./img/ep17-deny-as-not-found.png)

### Problemas y soluciones

Sin errores de implementación. Se probó acceso a `/admin` con usuario sin permiso: primero 403, luego 404 al usar `denyAsNotFound()`.

### Comentarios personales

Gates sirven para reglas globales simples; en el Ep. 18 se pasará a **Policies** para autorización por modelo (`Idea`).

### Commit Git

```
episodio-17: autorización con Gates y view-admin
```

---

## Episodio 18: Authorization Using Policies {#episodio-18}

### Resumen

Una **Policy** centraliza reglas de autorización **por modelo** (`Idea`). Laravel la genera con Artisan y la aplica en controladores con `Gate::authorize()`, `Auth::user()->can()` o `@can` en Blade. Se protegió **show** para que solo el dueño vea su idea y **create** para exigir admin.

### ¿Qué es una Policy?

| Concepto | Gate (Ep. 17) | Policy (Ep. 18) |
|----------|-----------------|-----------------|
| Alcance | Reglas globales (`view-admin`) | Reglas por modelo (`Idea`) |
| Ubicación | `AppServiceProvider` | `app/Policies/IdeaPolicy.php` |
| Métodos | Un closure por ability | `view`, `update`, `create`, `delete`, etc. |

Laravel mapea automáticamente `Idea` → `IdeaPolicy`.

### Crear la Policy

```bash
php artisan make:policy
# Name: IdeaPolicy
# Model: Idea
```

Genera `app/Policies/IdeaPolicy.php` con stubs: `viewAny`, `view`, `create`, `update`, `delete`…

### Reglas en `IdeaPolicy`

**Solo el dueño puede ver/editar su idea:**

```php
public function update(User $user, Idea $idea): bool
{
    return $user->is($idea->user);
    // equivalente: $user->id === $idea->user_id
}
```

**Solo admin puede crear ideas:**

```php
public function create(User $user): bool
{
    return $user->isAdmin();
}
```

(`isAdmin()` en el modelo `User` — p. ej. `$user->id === 1` o columna `role`.)

### Usar en el controlador

**Opción 1 — `Gate::authorize()`** (en `show`):

```php
public function show(Idea $idea)
{
    Gate::authorize('update', $idea);

    return view('ideas.show', ['idea' => $idea]);
}
```

Si el usuario no es dueño → **403 This action is unauthorized**.

**Opción 2 — `Auth::user()->can()`**

```php
if (Auth::user()->can('update', $idea)) {
    // permitido
}
```

Laravel resuelve la Policy según el tipo del segundo argumento (`$idea`).

### Resultados probados

| Escenario | Resultado |
|-----------|-----------|
| Usuario accede a idea ajena (`/ideas/4`) | 403 |
| No-admin visita `/ideas/create` | 404 (si se enmascara con gate/policy + `denyAsNotFound`) |

### Archivos tocados

`IdeaPolicy.php`, `IdeaController.php` (`show`), `User.php` (`isAdmin()` si aplica)

### Evidencia

![make:policy IdeaPolicy generado](./img/ep18-make-policy.png)

![Gate::authorize update y 403 en idea ajena](./img/ep18-authorize-update-403.png)

![Policy create admin + update ownership](./img/ep18-policy-create-admin.png)

### Problemas y soluciones

No se reportaron errores. La policy bloquea correctamente acceso a ideas de otros usuarios.

### Comentarios personales

Policies escalan mejor que Gates cuando la autorización depende del **recurso** (modelo + usuario). El Ep. 19 pasa a Vite para assets frontend.

### Commit Git

```
episodio-18: IdeaPolicy ownership y create solo admin
```

---

## Episodio 19: Frontend Asset Bundling with Vite {#episodio-19}

### Resumen

Se migró el frontend del **CDN** (Tailwind browser + DaisyUI por `<link>`) al **empaquetado local con Vite**. Tailwind CSS 4, DaisyUI 5 y la fuente Instrument Sans se compilan en `resources/css/app.css` y se cargan en el layout con la directiva `@vite`.

El acceso a la app sigue siendo por **Apache** (`http://lfts.local`), no por `php artisan serve` ni por el dev server de Vite. El flujo de desarrollo usa `npm run build` / `npm run watch` y **refresh manual** en el navegador (F5).

### Dependencias — `package.json`

```json
"devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "daisyui": "^5.6.3",
    "laravel-vite-plugin": "^3.1",
    "tailwindcss": "^4.0.0",
    "vite": "^8.0.0"
}
```

Scripts npm:

| Comando | Uso |
|---------|-----|
| `npm run build` | Compila assets a `public/build/` (producción o primera vez) |
| `npm run watch` | Recompila al guardar cambios en CSS/JS (desarrollo con Apache) |
| `npm run dev` | Dev server de Vite (no requerido con Apache) |

### CSS — `resources/css/app.css`

```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@plugin "daisyui" {
    themes: black --default;
}

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, ...;
}
```

- `@import 'tailwindcss'` — Tailwind CSS 4 vía Vite.
- `@source` — indica a Tailwind qué archivos escanear para clases (Blade, JS).
- `@plugin "daisyui"` — integra DaisyUI; tema `black` como predeterminado.
- `@theme` — fuente personalizada (Instrument Sans vía `laravel-vite-plugin/fonts`).

**Temas personalizados:** DaisyUI 5 permite definir temas propios con `@plugin "daisyui/theme" { name: "mitema"; --color-primary: ...; }` en el mismo archivo CSS.

### Layout — `resources/views/components/layout.blade.php`

**Antes (Ep. 13 — CDN):**

```html
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" />
<style>/* CSS manual .card, .max-w-400 */</style>
```

**Después (Ep. 19 — Vite):**

```blade
<html lang="en" data-theme="black">
<head>
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-nav />
    <main class="max-w-3xl mx-auto mt-6">{{ $slot }}</main>
</body>
```

Se eliminaron los CDN, el `<style>` manual que pisaba las clases `.card` de DaisyUI, y se mantuvo `data-theme="black"`.

### Vite — `vite.config.js`

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                'resources/views/**',
                'routes/**',
                'app/View/Components/**',
            ],
            fonts: [
                bunny('Instrument Sans', { weights: [400, 500, 600] }),
            ],
        }),
        tailwindcss(),
    ],
});
```

`refresh` vigila vistas y rutas (útil si se usa `npm run dev`). Con Apache se prioriza `npm run watch` + F5.

### Flujo de trabajo con Apache

```bash
cd ~/sites/laravel-from-scratch-2026

npm install          # primera vez
npm run build        # compilar assets
npm run watch        # terminal abierta mientras desarrollas
```

1. Apache sirve la app en `http://lfts.local`.
2. `@vite` lee el manifiesto en `public/build/manifest.json`.
3. Tras cambiar CSS/Blade, `watch` recompila; refrescas el navegador manualmente.
4. Si existe `public/hot` (de un `npm run dev` anterior), eliminarlo para forzar el uso del build.

### Comparación Ep. 13 → Ep. 19

| Aspecto | Ep. 13 (CDN) | Ep. 19 (Vite) |
|---------|--------------|---------------|
| Tailwind | CDN `@tailwindcss/browser` | `@import 'tailwindcss'` + Vite |
| DaisyUI | CDN `<link>` | `@plugin "daisyui"` en CSS |
| Carga en layout | `<script>` + `<link>` | `@vite([...])` |
| Tema | `data-theme="black"` | Igual + `themes: black --default` |
| Desarrollo | Sin build | `npm run watch` + F5 |

### Comandos utilizados

```bash
npm install
npm run build
npm run watch
```

### Archivos modificados o creados

- `package.json` — dependencia `daisyui`, script `watch`
- `package-lock.json`
- `vite.config.js` — Tailwind, DaisyUI, refresh, fuente Instrument Sans
- `resources/css/app.css` — Tailwind 4 + DaisyUI + `@theme`
- `resources/js/app.js` — entry point de Vite
- `resources/views/components/layout.blade.php` — `@vite`, sin CDN
- `.env.example` — `APP_URL=http://lfts.local`
- `docs/entregable02.md` — documentación del episodio
- `docs/img/ep19-vite.png` — evidencia

### Evidencia

![Vite bundling: package.json, app.css, layout sin CDN y login con DaisyUI en lfts.local](./img/ep19-vite.png)

### Problemas y soluciones

| Problema | Causa | Solución |
|----------|-------|----------|
| Estilos DaisyUI no cargan | CDN removido pero sin `npm run build` | Ejecutar `npm run build` o `npm run watch` |
| Sigue buscando dev server | Archivo `public/hot` presente | Borrar `public/hot`; no usar `VITE_DEV_SERVER_URL` con Apache |
| `.card` sin estilo DaisyUI | `<style>` manual en layout pisaba componentes | Eliminar bloque `<style>` del layout |
| Error JSON recursion al editar | `request('idea')` tomaba el modelo de la ruta `{idea}` | Usar `request('description')` en el PATCH (corregido en Ep. 9–10) |

### Comentarios personales

Vite centraliza Tailwind y DaisyUI en un solo pipeline. Con **Apache en Vagrant** no hace falta `npm run dev`: `npm run watch` recompila y el refresh en el navegador es suficiente. Los temas DaisyUI se configuran en CSS (`@plugin "daisyui/theme"`), lo que facilita personalizar colores sin tocar cada vista Blade.

### Commit Git

```
episodio-19: empaquetado de assets con Vite
```

---

## Episodio 20: Notifications {#episodio-20}

### Resumen

Sistema de **notificaciones** completo: tabla `notifications`, `Notifiable` en `User`, clase **`IdeaPublished`** (canal `mail`), **Mailpit** en desarrollo y envío automático al **publicar una idea** desde `IdeaController::store()`.

### Tabla de notificaciones

```bash
php artisan make:notifications-table
php artisan migrate
```

Crea la migración `create_notifications_table` y la tabla en BD para notificaciones **database** (opcional) además del canal mail.

### Colección `$user->notifications` — Tinker

El modelo `User` usa `Notifiable`. En Tinker:

```bash
php artisan tinker
>>> App\Models\User::first()->notifications;
```

Retorna `DatabaseNotificationCollection` — vacía (`all: []`) hasta que se registren notificaciones en canal `database`. Confirma que la relación existe.

### Clase `IdeaPublished`

```bash
php artisan make:notification IdeaPublished
```

Canal **`mail`** vía `via()` → `toMail()` con `MailMessage` (greeting, líneas, action *Read It*).

### Enviar notificación — Tinker

```php
$john = App\Models\User::first();
$john->notify(new App\Notifications\IdeaPublished(App\Models\Idea::latest()->first()));
```

### Mail en desarrollo — `.env` + Mailpit

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_FROM_ADDRESS="admin@lfts.local"
MAIL_FROM_NAME="${APP_NAME}"
```

Mailpit escucha SMTP en **1025**; UI web en **`http://192.168.33.10:8025`**. El correo *Idea Published* llega al email del usuario con asunto y cuerpo de Laravel.

### Integración en la app — `IdeaController::store()`

Tras crear la idea, se notifica al usuario autenticado:

```php
$idea = Auth::user()->ideas()->create([
    'description' => $request->description,
    'state' => 'pending',
    'user_id' => Auth::id(),
]);

Auth::user()->notify(new IdeaPublished($idea));

return redirect('/ideas');
```

Flujo: formulario create → `store()` → idea en BD → correo en Mailpit.

| Componente | Rol |
|------------|-----|
| `IdeaPublished` | Define qué se envía |
| `$user->notify()` | Dispara la notificación (Tinker o controller) |
| Mailpit | Servidor de correo local — captura mail en dev |
| `store()` | Envío automático al publicar idea |

### Comandos utilizados

```bash
php artisan make:notifications-table
php artisan migrate
php artisan make:notification IdeaPublished
php artisan tinker
```

### Archivos tocados

Migración `create_notifications_table`, `User.php` (`Notifiable`), `IdeaPublished.php`, `IdeaController.php` (`store`), `.env` (mail)

### Evidencia

![make:notifications-table y migrate](./img/ep20-notifications-migrate.png)

![Tinker — User::first()->notifications](./img/ep20-tinker-notifications.png)

![Mailpit — IdeaPublished por correo](./img/ep20-mailpit-ideapublished.png)

![store() — notify al crear idea](./img/ep20-controller-notify.png)

### Problemas y soluciones

En Tinker, salir con `exit` o Ctrl+D — no usar `q!` (provoca parse error en PsySH). Mailpit debe estar corriendo en la VM para ver correos en `:8025`.

### Comentarios personales

Episodio cerrado con flujo end-to-end: app + servidor de correos local. En el Ep. 21 se encolarán notificaciones con `ShouldQueue` y `queue:work`.

### Commit Git

```
episodio-20: notificaciones IdeaPublished y Mailpit
```

---

## Episodio 21: When to Queue it Up

### Resumen

*[Pendiente: ShouldQueue, php artisan queue:work, tabla jobs.]*

### Comandos utilizados

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

### Archivos modificados o creados

- `app/Notifications/IdeaPublished.php`
- `.env` *(QUEUE_CONNECTION)*

### Evidencia

![Episodio 21](./img/ep21-queues.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-21: notificaciones en cola con queue worker
```

---

## Episodio 22: How to Get Started Testing Your Code

### Resumen

*[Pendiente: Pest, Playwright, php artisan test, browser tests.]*

### Comandos utilizados

```bash
php artisan test
./vendor/bin/pest
```

### Archivos modificados o creados

- `tests/Feature/*.php`
- `tests/Browser/*.php` *(si aplica)*

### Evidencia

![Episodio 22](./img/ep22-testing.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-22: pruebas automatizadas con Pest
```

---

## Episodios 23–30: Proyecto final (primera parte)

Documentar cada episodio del 23 al 30 siguiendo la plantilla de arriba. Temas principales:

- **23:** Setup del repo, GitHub, herramientas (Pint, etc.)
- **24:** Modelos Idea, Step, IdeaStatus, factories y tests
- **25:** Tema Tailwind, componentes UI, registro/login
- **26:** Browser tests de registro
- **27:** Flash messages con Alpine.js
- **28:** Tarjetas de ideas y componentes x-card
- **29:** Filtrado por estado con scopes Eloquent
- **30:** Vista show de una idea individual

---

## Checklist de cierre — Entregable 02

- [ ] Episodios 17–30 completados y documentados
- [ ] `php artisan test` ejecutado con evidencia
- [ ] `php artisan queue:work` documentado cuando aplique
- [ ] `npm run dev` / `npm run build` documentados
- [ ] Archivo `entregable02.tar.gz` generado correctamente
