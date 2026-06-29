# Carpeta de evidencias — Proyecto 1 ISW811

> **Ruta host:** `C:\Users\yeide\isw811\VMs\webserver\sites\laravel-from-scratch-2026\docs\img`  
> **Ruta VM:** `~/sites/laravel-from-scratch-2026/docs/img`

## Imágenes guardadas (avance actual)

| Archivo | Episodio | Descripción |
|---------|----------|-------------|
| `ep01-estructura-laravel.png` | 01 | `ls -la` — estructura Laravel en la VM |
| `ep01-lfts-navegador.png` | 01 | Página de bienvenida en `http://lfts.local` |
| `ep01-welcome-personalizado.png` | 01 | Edición `welcome.blade.php` + navegador ("Yeison") |
| `evidencia-pagina-inicial.png` | 01 | **Obligatoria** — página inicial del proyecto |
| `ep03-about.png` | 03 | Vista `about.blade.php` en `/about` |
| `ep03-contact.png` | 03 | Vista `contact.blade.php` en `/contact` |
| `evidencia-rutas.png` | 03 | **Obligatoria** — rutas about/contact |
| `ep04-layout-slot.png` | 04 (p.1) | Layout, `$slot` y `<x-layout>` |
| `ep04-layout-card-merge.png` | 04 (p.2) | Props, `x-card` y `$attributes->merge()` |
| `ep05-pass-data-views.png` | 05 | Query string, `{{ }}` vs `{!! !!}`, defaults |
| `ep06-dump-tasks.png` | 06 | `@dump($tasks)` |
| `ep06-unless-empty.png` | 06 | `@unless` con arreglo vacío |
| `ep06-foreach-forelse.png` | 06 | `@if`, `@foreach`, `@forelse` |
| `ep07-form-post-csrf.png` | 07 | Formulario POST y `@csrf` |
| `ep07-session-ideas.png` | 07 | `session()->push` y listado |
| `ep07-session-delete.png` | 07 | `session()->forget('ideas')` |
| `evidencia-formulario.png` | 07 | **Obligatoria** — formulario funcional |
| `ep08-make-migration.png` | 08 | `php artisan make:migration create_ideas_table` |
| `ep08-migrate-dbeaver.png` | 08 | Schema + `php artisan migrate` + tabla en DBeaver |
| `ep08-migrate-refresh.png` | 08 | `php artisan migrate:refresh` (opción 2) |
| `ep08-add-state-migration.png` | 08 | `add_state_to_ideas_table` + `Schema::table()` (opción 3) |
| `ep08-db-collection.png` | 08 | Listado con Query Builder |
| `ep08-eloquent-create.png` | 08 | `Idea::create()` y listado en navegador |
| `ep08-eloquent-when-dd.png` | 08 | `->when()` + `dd($state)` con `?state=pending` |
| `ep08-eloquent-filter-state.png` | 08 | Filtro `?state=active` aplicado |
| `evidencia-listado-bd.png` | 08 | **Obligatoria** — listado desde BD con Eloquent |
| `ep09-show-findorfail.png` | 09 | Show + Route Model Binding |
| `ep09-edit-method-patch.png` | 09 | Edit + `@method('PATCH')` |
| `ep09-update-patch-route.png` | 09 | PATCH update y redirect a show |
| `ep09-delete-destroy.png` | 09 | DELETE destroy + `form="delete-idea-form"` |
| `ep10-create-empty-index.png` | 10 | Vista create, index vacío y rutas create/store |
| `ep10-make-controller.png` | 10 | `php artisan make:controller` (Empty / Resource) |
| `ep10-resource-controller.png` | 10 | `IdeaController` generado como Resource |
| `ep10-routes-controller.png` | 10 | Rutas en `web.php` apuntando al controlador |
| `ep11-validate-store.png` | 11 | `$request->validate()` en `store()` |
| `ep11-errors-feedback.png` | 11 | Error de validación en `/ideas/create` |
| `ep11-error-component.png` | 11 | Componente `<x-forms.error>` con `@error` |
| `ep12-make-request.png` | 12 | `php artisan make:request StoreIdeaRequest` |
| `ep12-rules-controller.png` | 12 | `rules()` + type-hint en `store()` |
| `ep12-messages-custom.png` | 12 | `messages()` y mensaje `:attribute is Required` |
| `ep13-create-daisyui.png` | 13 | Create con `textarea`, `btn` y `textarea-error` |
| `ep13-index-idea-card.png` | 13 | Index con `<x-idea-card>` en grid |
| `ep13-layout-cdn.png` | 13 | CDN DaisyUI + `data-theme="black"` en layout |
| `ep14-register-network.png` | 14 | Register + Network 302→200 |
| `ep14-register-controller.png` | 14 | `RegisteredUserController` + `Hash::make` |
| `ep14-auth-guest-nav.png` | 14 | `@auth` / `@guest` en nav |
| `ep14-logout.png` | 14 | `SessionsController::destroy` + DELETE `/logout` |
| `ep15-routes-middleware.png` | 15 | Grupos `auth`/`guest` + `user_id` en DBeaver |
| `ep15-store-user-id.png` | 15 | `store()` con `Auth::id()` |
| `ep15-tinker-factory.png` | 15 | `User::factory()->create()` en Tinker |
| `ep15-index-filter.png` | 15 | `index()` filtrado por `user_id` |
| `ep16-user-hasMany-tinker.png` | 16 | `User::hasMany` + `$user->ideas` en Tinker |
| `ep16-controller-relationships.png` | 16 | `Auth::user()->ideas` y `ideas()->create()` |

## Entregable 02 — episodios 17+

| Archivo | Episodio | Descripción |
|---------|----------|-------------|
| `ep17-gate-define-nav.png` | 17 | `Gate::define('view-admin')` y `@can` en nav |
| `ep17-route-can.png` | 17 | Ruta `/admin` con `->can('view-admin')` |
| `ep17-gate-authorize-route.png` | 17 | `Gate::authorize()` en closure |
| `ep17-403-unauthorized.png` | 17 | 403 — acción no autorizada |
| `ep17-deny-as-not-found.png` | 17 | `denyAsNotFound()` → 404 |
| `ep18-make-policy.png` | 18 | `php artisan make:policy` → `IdeaPolicy` |
| `ep18-authorize-update-403.png` | 18 | `Gate::authorize('update', $idea)` → 403 |
| `ep18-policy-create-admin.png` | 18 | `create()` admin + `update()` ownership |
| `evidencia-registro.png` | 14 | **Obligatoria** — registro funcional |
| `evidencia-login.png` | 14 | **Obligatoria** — sesión autenticada |
| `evidencia-logout.png` | 14 | **Obligatoria** — logout y nav guest |
| `evidencia-crear.png` | 10 | **Obligatoria** — flujo create (index vacío + formulario) |
| `evidencia-validacion.png` | 11 | **Obligatoria** — mensaje de error de validación |
| `evidencia-editar.png` | 09 | **Obligatoria** — formulario edit |
| `evidencia-actualizar.png` | 09 | **Obligatoria** — PATCH update |
| `evidencia-eliminar.png` | 09 | **Obligatoria** — DELETE destroy |

## Pendientes (evidencias obligatorias del entregable)

*Ninguna — las 12 evidencias obligatorias están completas.*

## Sintaxis en Markdown

Desde `docs/entregable01.md`:

```markdown
![Descripción](./img/ep03-about.png)
```

Desde `README.md` en la raíz:

```markdown
![Descripción](docs/img/ep03-about.png)
```
