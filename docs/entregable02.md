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
| 19 | Frontend Asset Bundling with Vite | Pendiente | — |
| 20 | Notifications | Pendiente | — |
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

## Episodio 19: Frontend Asset Bundling with Vite

### Resumen

*[Pendiente: @vite, resources/css/app.css, npm run dev / npm run build.]*

### Comandos utilizados

```bash
npm run dev
npm run build
```

### Archivos modificados o creados

- `vite.config.js`
- `resources/css/app.css`
- `resources/views/components/layout.blade.php`

### Evidencia

![Episodio 19](./img/ep19-vite.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-19: empaquetado de assets con Vite
```

---

## Episodio 20: Notifications

### Resumen

*[Pendiente]*

### Comandos utilizados

```bash
php artisan make:notification IdeaPublished
```

### Archivos modificados o creados

- `app/Notifications/IdeaPublished.php`
- `app/Http/Controllers/IdeaController.php`

### Evidencia

![Episodio 20](./img/ep20-notifications.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-20: notificaciones al publicar idea
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
