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
| 21 | When to Queue it Up | Completado | [Episodio 21](#episodio-21) |
| 22 | How to Get Started Testing Your Code | Inconcluso | [Episodio 22](#episodio-22) |
| 23 | Final Project Setup | En progreso | [Episodio 23](#episodio-23) |
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

## Episodio 21: When to Queue it Up {#episodio-21}

### Resumen

Se introdujeron **colas (queues)** en Laravel: procesos que corren **fuera del request** del usuario. Se creó el job **`UpdateIdeaStatistics`**, se despachó con Tinker, se procesó con **`queue:work`** y se verificó en la tabla **`jobs`** de BD y en `storage/logs/laravel.log`.

### Conceptos — queue, job, worker

| Término | Qué es |
|---------|--------|
| **Queue** | Pila de trabajo — lista de tareas pendientes (en BD, Redis, etc.) |
| **Job** | La tarea concreta — clase con lógica en `handle()` |
| **Worker** | Proceso que saca jobs de la cola y los ejecuta (`php artisan queue:work`) |

**Cuándo usar colas:** envío de correos, estadísticas, reportes, APIs lentas — cualquier cosa que no deba bloquear la respuesta al usuario.

### Configuración

```env
QUEUE_CONNECTION=database
```

Tabla `jobs` (migración Laravel por defecto o `php artisan queue:table` + `migrate`).

### Crear un Job

```bash
php artisan make:job
# Name: UpdateIdeaStatistics
```

Genera `app/Jobs/UpdateIdeaStatistics.php`:

```php
class UpdateIdeaStatistics implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        logger('The job UpdateIdeaStatistics is being processed');
    }
}
```

`implements ShouldQueue` → el job va a la cola, no corre inline.

### Despachar un Job

**Tinker:**

```php
App\Jobs\UpdateIdeaStatistics::dispatch();
// => Illuminate\Foundation\Bus\PendingDispatch
```

También desde controladores: `UpdateIdeaStatistics::dispatch()` o `dispatch(new UpdateIdeaStatistics())`.

### Procesar la cola — worker

Terminal aparte (debe estar corriendo):

```bash
php artisan queue:work
```

Salida:

```text
INFO  Processing jobs from the [default] queue.
App\Jobs\UpdateIdeaStatistics ........ RUNNING
App\Jobs\UpdateIdeaStatistics ........ DONE
```

Log en `storage/logs/laravel.log`:

```text
local.DEBUG: The job UpdateIdeaStatistics is being processed
```

### Jobs en la base de datos

Tabla **`jobs`** en DBeaver (`larabase`): filas con `queue` = `default`, `payload` (JSON serializado), `attempts` = 0 hasta que el worker las procesa.

Sin `queue:work` activo, los jobs **se acumulan** en `jobs` y no se ejecutan.

### Relación con Ep. 20

`IdeaPublished` puede implementar `ShouldQueue` para encolar el correo en lugar de enviarlo en el mismo request — mismo patrón que `UpdateIdeaStatistics`.

### Comandos utilizados

```bash
php artisan make:job          # UpdateIdeaStatistics
php artisan queue:work
php artisan tinker            # dispatch()
```

### Archivos tocados

`app/Jobs/UpdateIdeaStatistics.php`, `.env` (`QUEUE_CONNECTION=database`), tabla `jobs`

### Evidencia

![make:job UpdateIdeaStatistics](./img/ep21-make-job.png)

![dispatch() y handle() con logger](./img/ep21-dispatch-job.png)

![queue:work RUNNING/DONE y laravel.log](./img/ep21-queue-work.png)

![Tabla jobs en BD + dispatch + queue:work](./img/ep21-jobs-table.png)

### Problemas y soluciones

Si el job no corre: verificar que `queue:work` esté activo y `QUEUE_CONNECTION=database`. Jobs pendientes visibles en tabla `jobs`.

### Comentarios personales

El worker es un proceso de larga duración — en producción se usa Supervisor o similar. Ep. 22 entra en testing con Pest.

### Commit Git

```
episodio-21: job UpdateIdeaStatistics y queue:work
```

---

## Episodio 22: How to Get Started Testing Your Code {#episodio-22}

### Resumen

Se introducen **pruebas automatizadas con Pest** en Laravel. Pest permite escribir tests legibles con sintaxis `it()` / `expect()` y cubre:

| Tipo | Carpeta típica | Qué prueba |
|------|----------------|------------|
| **Unit** | `tests/Unit/` | Una clase o función aislada |
| **Feature** | `tests/Feature/` | HTTP, rutas, controladores, integración |
| **Browser** | `tests/Feature/` *(con plugin)* | UI real con **Playwright** — navegador headless |

En este episodio se usa el plugin **`pest-plugin-browser`**: `visit()` abre una URL, `assertSee()` comprueba texto en pantalla, `fill()` / `press()` simulan formularios. Los browser tests viven en `tests/Feature/` pero ejecutan un navegador real (Chrome por defecto).

### Primer browser test — `ExampleTest.php`

```php
<?php

it('the application returns a successful response', function () {
    visit('/')->assertSee('Welcome');
});
```

Ejecutar:

```bash
php artisan test
# o
./vendor/bin/pest
```

En la VM el test pasó pero tardó **~16.66 s** para una sola aserción — el entorno Vagrant + Playwright es lento comparado con tests PHP puros.

### Depuración con `->debug()`

En cualquier punto de la cadena del browser test se puede llamar `->debug()`:

```php
visit('/register')
    ->fill('email', 'test@mail.com')
    ->debug()   // pausa, abre navegador en modo headed y muestra la página
    ->press('@register-button');
```

También existe configuración global `pest()->browser()->debug()` en `tests/Pest.php` para depurar aserciones.

### Test de registro — `AuthTest.php`

```php
<?php

use App\Models\User;

it('register a user', function () {
    visit('/register')
        ->fill('name', 'Jane Doe')
        ->fill('email', 'janedoe@mail.com')
        ->fill('password', 'secret1234')
        ->press('@register-button')
        ->assertPathIs('/ideas');
});
```

El botón usa `data-test="register-button"` → selector `@register-button` en Pest.

Tras el registro, `RegisteredUserController::store()` hace `Auth::login()` y `redirect('/ideas')`.

### Timeout del navegador (problema principal)

**Error observado:**

```text
Timeout 5000ms exceeded.
A screenshot of the page has been saved to [Tests/Browser/Screenshots/it_register_a_user].
```

**Diagnóstico:** el usuario **sí se crea en la BD** (`larabase.users`), así que el POST `/register` funciona. El fallo ocurre **después**, cuando Pest espera que la URL sea `/ideas` (`assertPathIs`). El timeout por defecto de Playwright es **5000 ms** — insuficiente en la VM (el `ExampleTest` simple ya tarda ~17 s).

**Solución — aumentar timeout global** en `tests/Pest.php`:

```php
pest()->browser()->timeout(60_000); // 60 segundos para toda la suite browser
```

Colocar **antes** del bloque `pest()->extend(...)`.

Alternativa por test (sin cambiar global):

```php
use Pest\Browser\Playwright\Playwright;

Playwright::usingTimeout(60_000, function () {
    visit('/register')
        // ...
        ->assertPathIs('/ideas');
});
```

### Email duplicado en re-ejecuciones

`phpunit.xml` usa la BD real `larabase` y `RefreshDatabase` está comentado en `Pest.php`. Si el test ya corrió una vez, `janedoe@mail.com` existe → validación `unique:users` falla → la página **no** redirige a `/ideas` y `assertPathIs` agota el timeout.

Opciones:

1. Borrar el usuario de prueba en DBeaver antes de re-correr.
2. Email único por ejecución: `'jane' . uniqid() . '@mail.com'`.
3. Descomentar `->use(RefreshDatabase::class)` en `Pest.php` *(migra/limpia BD en cada test — usar con cuidado en `larabase` compartida)*.

### Aserciones alternativas

Si el path tarda pero la página ya cargó:

```php
->assertSee('Your Ideas')   // texto del index de ideas
// o
->assertPathIs('/ideas')    // preferido cuando el redirect ya ocurrió
```

Revisar el screenshot en `tests/Browser/Screenshots/it_register_a_user` para ver en qué URL quedó el navegador al fallar.

### Comandos utilizados

```bash
php artisan test
./vendor/bin/pest
./vendor/bin/pest --filter="register a user"
./vendor/bin/pest tests/Feature/AuthTest.php
```

### Archivos modificados o creados

- `tests/Pest.php` — `pest()->extend(TestCase::class)`, timeout browser
- `tests/Feature/ExampleTest.php` — `visit('/')` + `assertSee('Welcome')`
- `tests/Feature/AuthTest.php` — registro vía browser
- `resources/views/auth/register.blade.php` — `data-test="register-button"`
- `tests/Browser/Screenshots/` — capturas automáticas al fallar

### Evidencia

![ExampleTest PASS — visit / assertSee Welcome (~16.66 s)](./img/ep22-example-test-pass.png)

*(Pendiente captura: AuthTest PASS — no completado por tiempos de ejecución en VM.)*

![AuthTest FAIL — Timeout 120000ms, duración total ~252 s](./img/ep22-auth-test-timeout.png)

### Estado del episodio: **inconcluso**

Se dejó el capítulo sin cerrar por **tiempos de ejecución inaceptables** en la VM con browser tests (Playwright):

| Test | Resultado | Duración |
|------|-----------|----------|
| `ExampleTest` — `assertSee('Welcome')` | PASS | ~16.66 s |
| `AuthTest` — registro completo | FAIL | **~252.45 s** |

Tras subir el timeout a `120_000` ms (`pest()->browser()->timeout(120_000)`), el fallo pasó a:

```text
Timeout 120000ms exceeded.
FAIL at tests/Feature/AuthTest.php:13 → ->press('@register-button')
```

El usuario **sí se crea en BD**, pero el navegador en Vagrant no completa el flujo dentro del límite. Para el entregable se documenta lo aprendido (Pest, browser plugin, `debug()`, timeouts); los browser tests del Ep. 22 y Ep. 26 se retoman solo si el entorno mejora o se usa máquina más rápida / `sqlite :memory:`.

### Problemas y soluciones

| Problema | Causa | Solución |
|----------|-------|----------|
| `Timeout 5000ms exceeded` | VM lenta; default Playwright = 5 s | `pest()->browser()->timeout(120_000)` en `Pest.php` |
| `Timeout 120000ms exceeded` (~252 s total) | VM + Playwright demasiado lentos para browser tests | Ep. 22 dejado inconcluso; continuar con proyecto final |
| Usuario en BD pero test falla | Registro OK; timeout en `press` o `assertPathIs` | Revisar screenshot en `tests/Browser/Screenshots/` |
| Falla en segunda ejecución | Email duplicado | Email único o limpiar BD |
| No encuentra botón | Falta `data-test` | `@register-button` en el `<button>` |

### Comentarios personales

Los browser tests validan el flujo completo pero en Vagrant son impracticables (~252 s y aún fallan). Se prioriza avanzar al **proyecto final Idea** (Ep. 23+). Feature/unit tests PHP puros siguen siendo viables en esta VM.

### Commit Git

```
episodio-22: Pest browser tests (inconcluso — timeout VM)
```

---

## Episodio 23: Final Project Setup {#episodio-23}

### Resumen

A partir del Ep. 23 el curso entra en el **proyecto final: Idea** — una app más completa (ideas con pasos, estados, imágenes, modales Alpine, etc.) que el CRUD de práctica de los episodios 1–22.

Jeffrey Way muestra su **workflow real** de proyecto Laravel moderno:

1. Crear o reinicializar el repositorio del proyecto **Idea**
2. Subir a **GitHub** (commits por episodio)
3. Configurar herramientas de calidad y productividad
4. *(Opcional en el curso)* desplegar con **Laravel Forge**

> **Nota ISW811:** Puedes seguir en el mismo repo `laravel-from-scratch-2026` o crear uno nuevo `idea` en `~/sites/` — lo importante es documentar cada paso y las evidencias.

### Paso 1 — Nuevo proyecto Laravel (si el video lo hace desde cero)

En la VM:

```bash
cd ~/sites
laravel new idea
# o: composer create-project laravel/laravel idea
cd idea
```

Configurar virtual host Apache para el nuevo dominio (p. ej. `idea.local`) igual que `lfts.local`.

Si Jeffrey continúa en el mismo árbol de archivos, simplemente haz `git status` y asegúrate de tener rama limpia antes del Ep. 23.

### Paso 2 — Repositorio Git y GitHub

```bash
git init                    # solo si es proyecto nuevo
git add .
git commit -m "episodio-23: setup inicial proyecto Idea"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/idea.git
git push -u origin main
```

Buenas prácticas del curso: **un commit por episodio** con mensaje `episodio-XX: descripción`.

### Paso 3 — Laravel Pint (formateo de código)

Pint viene con Laravel. Formatea PHP según el estilo del framework:

```bash
./vendor/bin/pint
./vendor/bin/pint --test    # solo verifica, no modifica
```

Opcional: archivo `pint.json` en la raíz para reglas personalizadas.

### Paso 4 — Rector (refactors automáticos PHP)

Herramienta para modernizar código PHP (upgrades de sintaxis, dead code, etc.):

```bash
composer require rector/rector --dev
vendor/bin/rector init      # genera rector.php
vendor/bin/rector process --dry-run
vendor/bin/rector process
```

### Paso 5 — Code Rabbit *(opcional)*

Revisor de PRs con IA — se conecta a GitHub. No es obligatorio para el curso; Jeffrey lo muestra como parte del workflow profesional. Si no tienes cuenta, documenta que lo omitiste.

### Paso 6 — Laravel Boost *(opcional)*

Paquete/MCP de Laracasts para asistencia AI en el proyecto Laravel. Instalación según docs del curso:

```bash
composer require laravel/boost --dev
php artisan boost:install
```

### Paso 7 — Laravel Forge *(opcional / referencia)*

Forge automatiza servidores (DigitalOcean, etc.) + deploy desde GitHub. Para ISW811 basta documentar el concepto; el despliegue real es en episodios finales (~Ep. 40).

### Qué documentar como evidencia (Ep. 23)

| Captura sugerida | Archivo |
|------------------|---------|
| `laravel new` o estructura del proyecto Idea | `ep23-proyecto-nuevo.png` |
| Repo en GitHub con primer push | `ep23-github-repo.png` |
| `./vendor/bin/pint` ejecutado | `ep23-pint.png` |
| `rector` dry-run o `boost:install` *(si aplica)* | `ep23-rector-boost.png` |

### Comandos utilizados

```bash
laravel new idea
git init && git add . && git commit -m "episodio-23: setup inicial"
git remote add origin <url>
git push -u origin main
./vendor/bin/pint
composer require rector/rector --dev
vendor/bin/rector init
composer require laravel/boost --dev
php artisan boost:install
```

### Archivos modificados o creados

- Proyecto `idea/` *(o continuación en `laravel-from-scratch-2026`)*
- `pint.json` *(opcional)*
- `rector.php` *(si instalas Rector)*
- `.github/` o integración Code Rabbit *(opcional)*

### Evidencia

*[Pendiente: capturas al completar el episodio en la VM.]*

### Problemas y soluciones

*[Completar según tu experiencia en la VM.]*

### Comentarios personales

Ep. 22 quedó inconcluso por browser tests lentos; el proyecto final no depende de esos tests hasta el Ep. 26. El Ep. 24 entra directo en **modelos** (`Idea`, `Step`, `IdeaStatus`).

### Commit Git

```
episodio-23: setup proyecto Idea, GitHub y herramientas (Pint, Rector)
```

---

## Episodios 24–30: Proyecto final (primera parte)

Documentar cada episodio del 23 al 30 siguiendo la plantilla de arriba. Temas principales:

- **23:** Setup del repo, GitHub, herramientas (Pint, Rector, Boost) — [ver Ep. 23](#episodio-23)
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
