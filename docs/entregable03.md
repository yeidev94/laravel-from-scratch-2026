# Entregable 03 — Laravel From Scratch 2026

| Campo | Valor |
|-------|-------|
| **Estudiante** | Yeison Roberto Hernandez Garita |
| **Curso** | ISW811 |
| **Entregable** | 03 — Finalización del proyecto, modales, formularios avanzados, archivos, acciones, perfil y despliegue |
| **Fecha límite** | 13 de julio de 2026 |
| **Episodios cubiertos** | 31 al 43 |

---

## Resumen del entregable

En este entregable se completa el proyecto final “Idea”: modales con Alpine.js, formularios avanzados, enlaces múltiples, pasos accionables, carga de imágenes, clases Action, autorización reforzada, edición de perfil y documentación del proceso de despliegue.

> **Código:** `~/sites/idea` · **Documentación y capturas:** `~/sites/laravel-from-scratch-2026/docs/` ([estructura-proyectos.md](./estructura-proyectos.md))

---

## Índice de episodios

| # | Episodio | Estado |
|---|----------|--------|
| 31 | Create A Functional Modal With AlpineJS | Pendiente |
| 32 | Construct The Idea Form | Pendiente |
| 33 | Test The Create Idea Form | Pendiente |
| 34 | Allow For One or Many Links | Pendiente |
| 35 | Actionable Steps | Pendiente |
| 36 | Upload Featured Images To Storage | Pendiente |
| 37 | Action Classes | Pendiente |
| 38 | Authorization Is A Requirement | Pendiente |
| 39 | The Edit Idea Modal | Pendiente |
| 40 | Update Idea Action | Pendiente |
| 41 | Edit Your Profile | Pendiente |
| 42 | Deploy And Then Implement A Feature Request | Pendiente |
| 43 | Where To Go From Here | Pendiente |

---

## Plantilla por episodio

```markdown
## Episodio XX: Nombre del episodio

### Resumen
[Qué se aprendió y qué se implementó.]

### Comandos utilizados
```bash
php artisan storage:link
php artisan test
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

## Episodio 31: Create A Functional Modal With AlpineJS

### Resumen

*[Pendiente]*

### Comandos utilizados

```bash
# N/A
```

### Archivos modificados o creados

- `resources/views/components/modal.blade.php`
- `resources/views/ideas/index.blade.php`

### Evidencia

![Episodio 31](./img/ep31-modal.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-31: modal funcional con Alpine.js
```

---

## Episodio 36: Upload Featured Images To Storage

### Resumen

*[Pendiente: multipart/form-data, storage, storage:link.]*

### Comandos utilizados

```bash
php artisan storage:link
```

### Archivos modificados o creados

- `app/Http/Controllers/IdeaController.php`
- `resources/views/ideas/*.blade.php`

### Evidencia

![Episodio 36](./img/ep36-upload.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-36: carga de imágenes destacadas
```

---

## Episodio 37: Action Classes

### Resumen

*[Pendiente: CreateIdea action, inyección de dependencias.]*

### Comandos utilizados

```bash
# N/A
```

### Archivos modificados o creados

- `app/Actions/CreateIdea.php`
- `app/Http/Controllers/IdeaController.php`

### Evidencia

![Episodio 37](./img/ep37-actions.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-37: clase Action para crear ideas
```

---

## Episodio 41: Edit Your Profile

### Resumen

*[Pendiente: ProfileController, validación de contraseña, notificaciones.]*

### Comandos utilizados

```bash
php artisan make:controller ProfileController
php artisan test
```

### Archivos modificados o creados

- `app/Http/Controllers/ProfileController.php`
- `resources/views/profile/edit.blade.php`

### Evidencia

![Episodio 41](./img/ep41-profile.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente]*

### Commit Git

```
episodio-41: edición de perfil de usuario
```

---

## Episodio 42: Deploy And Then Implement A Feature Request

### Resumen

*[Pendiente: documentar proceso de despliegue — en el curso usa Forge; adaptar a lo disponible en el entorno académico o documentar pasos equivalentes.]*

### Comandos utilizados

```bash
composer run format
php artisan test
npm run build
```

### Archivos modificados o creados

- `app/Models/Idea.php` *(accessor formatted_description)*
- Configuración de despliegue documentada en este archivo

### Evidencia

![Episodio 42](./img/ep42-deploy.png)

### Problemas y soluciones

*[Pendiente]*

### Comentarios personales

*[Pendiente: notas sobre despliegue local vs producción.]*

### Commit Git

```
episodio-42: despliegue y feature de markdown en descripciones
```

---

## Episodios pendientes de documentar (32–35, 38–40, 43)

Completar cada uno con la plantilla estándar al avanzar en el curso.

---

## Checklist de cierre — Entregable 03

- [ ] Episodios 31–43 completados y documentados
- [ ] `php artisan storage:link` ejecutado y evidenciado
- [ ] Pruebas finales (`php artisan test`) con captura
- [ ] Proceso de despliegue documentado (aunque sea en ambiente local/demo)
- [ ] Archivo `entregable03.tar.gz` generado correctamente
