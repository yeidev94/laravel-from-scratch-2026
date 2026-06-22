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

| # | Episodio | Estado |
|---|----------|--------|
| 17 | Authorization Using Gates | Pendiente |
| 18 | Authorization Using Policies | Pendiente |
| 19 | Frontend Asset Bundling with Vite | Pendiente |
| 20 | Notifications | Pendiente |
| 21 | When to Queue it Up | Pendiente |
| 22 | How to Get Started Testing Your Code | Pendiente |
| 23 | Final Project Setup | Pendiente |
| 24 | Design Your Model Layer | Pendiente |
| 25 | Tailwind Theme Setup And Initial UI | Pendiente |
| 26 | Browser Testing Registration Forms With Pest | Pendiente |
| 27 | Flash Messaging and Interactivity with AlpineJS | Pendiente |
| 28 | Idea Cards | Pendiente |
| 29 | Idea Filtering | Pendiente |
| 30 | Show A Single Idea | Pendiente |

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

## Episodio 17: Authorization Using Gates

### Resumen

*[Pendiente]*

### Comandos utilizados

```bash
# N/A
```

### Archivos modificados o creados

- `app/Providers/AppServiceProvider.php`
- `resources/views/**/*.blade.php`

### Evidencia

![Episodio 17](./img/ep17-gates.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-17: autorización con Gates
```

---

## Episodio 18: Authorization Using Policies

### Resumen

*[Pendiente]*

### Comandos utilizados

```bash
php artisan make:policy IdeaPolicy --model=Idea
```

### Archivos modificados o creados

- `app/Policies/IdeaPolicy.php`
- `app/Http/Controllers/IdeaController.php`

### Evidencia

![Episodio 18](./img/ep18-policies.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-18: IdeaPolicy y autorización en controlador
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
