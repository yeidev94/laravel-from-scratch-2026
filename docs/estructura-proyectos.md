# Estructura de proyectos — ISW811 / Laravel From Scratch 2026

A partir del **Episodio 23** hay **dos carpetas Laravel** en `~/sites`. La documentación del curso **permanece** en `laravel-from-scratch-2026/docs/`.

---

## Resumen (setup real ISW811)

| Rol | Carpeta | Episodios | Notas |
|-----|---------|-----------|-------|
| **Proyecto final Idea + documentación** | `laravel-from-scratch-2026` | 23–43 (código) · 1–43 (docs) | Laravel nuevo con Pest, Rector, Pint |
| **App de práctica (archivo)** | `laravel-from-scratch-2026-old` | 1–22 (código congelado) | CRUD, auth, gates, colas, browser tests |

> En el curso Laracasts el proyecto se llama `idea`; aquí se reutilizó el nombre `laravel-from-scratch-2026` para el repo nuevo y se movió el código anterior a `-old`.

---

## Rutas

| Sistema | Proyecto Idea (activo) | Archivo práctica | Documentación |
|---------|------------------------|------------------|---------------|
| **Windows** | `...\sites\laravel-from-scratch-2026` | `...\sites\laravel-from-scratch-2026-old` | `...\laravel-from-scratch-2026\docs` |
| **VM** | `~/sites/laravel-from-scratch-2026` | `~/sites/laravel-from-scratch-2026-old` | `~/sites/laravel-from-scratch-2026/docs` |

---

## Qué contiene cada carpeta

### `laravel-from-scratch-2026/` — proyecto Idea (activo)

```
laravel-from-scratch-2026/
├── docs/                    ← TODA la documentación ISW811 (eps. 1–43)
│   ├── entregable01.md
│   ├── entregable02.md
│   ├── entregable03.md
│   └── img/
├── app/                     ← Laravel fresco (Ep. 23+)
├── rector.php               ← Config Rector + rector-laravel
├── composer.json            ← script "format", Rector, Pint, Pest
└── README.md
```

### `laravel-from-scratch-2026-old/` — práctica Ep. 1–22 (referencia)

```
laravel-from-scratch-2026-old/
├── app/Http/Controllers/IdeaController.php
├── apache-conf/lfts.local.conf
├── docs/                    ← (si quedó copia; la oficial está en -2026/docs)
└── tests/Feature/           ← browser tests Ep. 22
```

**No borrar** `-old`: es evidencia del Entregable 01 y referencia del CRUD de práctica.

---

## Apache / dominio

El virtual host `lfts.local` en `-old` apuntaba al proyecto de práctica. Tras el Ep. 23, **actualizar** `DocumentRoot` al proyecto activo si quieres seguir usando `http://lfts.local`:

```apache
DocumentRoot /vagrant/sites/laravel-from-scratch-2026/public
```

O crear `idea.local` apuntando al mismo `public/` del proyecto nuevo.

---

## Flujo Ep. 23+ (código + docs en la misma carpeta activa)

1. Implementar en **`~/sites/laravel-from-scratch-2026`**
2. Documentar en **`docs/entregable02.md`** o **`entregable03.md`**
3. Capturas en **`docs/img/epXX-*.png`**
4. Commit: `episodio-XX: descripción`

---

## Empaquetado entregable

Incluir **ambas** carpetas (sin `vendor/` ni `node_modules/`):

```bash
cd ~/sites
tar cvfz ISW811_Entregable02_HernandezGaritaYeison.tar.gz \
  --exclude=laravel-from-scratch-2026/node_modules \
  --exclude=laravel-from-scratch-2026/vendor \
  --exclude=laravel-from-scratch-2026-old/node_modules \
  --exclude=laravel-from-scratch-2026-old/vendor \
  laravel-from-scratch-2026/ laravel-from-scratch-2026-old/
```

---

## Mapa episodio → carpeta de código

| Episodios | Código |
|-----------|--------|
| 01–22 | `laravel-from-scratch-2026-old` |
| 23–43 | `laravel-from-scratch-2026` |
| 01–43 (documentación) | `laravel-from-scratch-2026/docs` |
