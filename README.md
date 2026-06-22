# ISW811 — Proyecto 1: Laravel From Scratch 2026

| Campo | Valor |
|-------|-------|
| **Estudiante** | Yeison Roberto Hernandez Garita |
| **Curso** | ISW811 — Aplicaciones Web con Software Libre |
| **Docente** | Misael Matamoros Soto |
| **Curso Laracasts** | [Laravel From Scratch (2026 Edition)](https://laracasts.com/series/laravel-from-scratch-2026) |
| **Entregable actual** | 01 — Episodios 1 al 16 |
| **Fecha límite Entregable 01** | 22 de junio de 2026, 23:59 |

---

## Descripción

Proyecto del curso **Laravel From Scratch 2026**. El código Laravel y la documentación técnica (Markdown + capturas) viven en esta misma carpeta, versionados con Git.

Documentación detallada por episodio: **[docs/entregable01.md](docs/entregable01.md)**

---

## Ambiente de desarrollo

| Componente | Detalle |
|------------|---------|
| **Host** | Windows 10 |
| **VM** | Debian 12 (Bookworm) — Vagrant + VirtualBox |
| **Vagrant** | `C:\Users\yeide\isw811\VMs\webserver` |
| **Carpeta compartida (host)** | `C:\Users\yeide\isw811\VMs\webserver\sites` |
| **Carpeta compartida (VM)** | `~/sites` |
| **Proyecto (host)** | `C:\Users\yeide\isw811\VMs\webserver\sites\laravel-from-scratch-2026` |
| **Proyecto (VM)** | `~/sites/laravel-from-scratch-2026` |
| **Base de datos** | `larabase` / usuario `larauser` |
| **Dominio local** | `http://lfts.local` |
| **IP VM** | `192.168.33.10` |

> El Workshop 03 dejó configurado `larasite.local` en la misma VM. No hay conflicto: Apache enruta por `ServerName` (`lfts.local` vs `larasite.local`).

---

## Estructura del proyecto

```
laravel-from-scratch-2026/
├── app/
├── bootstrap/
├── config/
├── database/
├── docs/
│   ├── entregable01.md       # Episodios 1–16
│   ├── entregable02.md
│   ├── entregable03.md
│   └── img/                  # Capturas de pantalla
├── apache-conf/
│   └── lfts.local.conf       # Virtual host Apache
├── public/
├── resources/
│   └── views/
│       ├── welcome.blade.php
│       ├── about.blade.php
│       └── contact.blade.php
├── routes/
│   └── web.php
├── tests/
├── .git/
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

## Instalación y ejecución

Desde la VM (`vagrant ssh`):

```bash
cd ~/sites/laravel-from-scratch-2026

composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configurar `.env`:

```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=larabase
DB_USERNAME=larauser
DB_PASSWORD=secret
```

```bash
php artisan migrate
npm run dev    # terminal 1 — Vite (opcional en desarrollo)
```

### Apache — virtual host `lfts.local`

```bash
sudo cp ~/sites/laravel-from-scratch-2026/apache-conf/lfts.local.conf \
  /etc/apache2/sites-available/lfts.local.conf
sudo a2ensite lfts.local.conf
sudo a2enmod rewrite
sudo apache2ctl configtest
sudo systemctl restart apache2
```

**Archivo `hosts` en Windows** (`C:\Windows\System32\drivers\etc\hosts`):

```
192.168.33.10 lfts.local
```

Acceso: **http://lfts.local**

### Permisos (si aparece error 500)

```bash
cd ~/sites/laravel-from-scratch-2026
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## Avance del curso — Entregable 01

| # | Episodio | Estado |
|---|----------|--------|
| 01 | Welcome Aboard + creación del proyecto | Completado |
| 02 | Set Up Your Development Environment | Completado *(en Ep. 01)* |
| 03 | Routing 101 | Completado |
| 04 | Layout Files | Completado |
| 05–16 | Pendientes | — |

### Episodio 01 — Proyecto Laravel funcional

- Proyecto creado con `laravel new` en `~/sites/laravel-from-scratch-2026`
- Virtual host Apache `lfts.local` configurado
- Primera prueba editando `welcome.blade.php`

![Estructura del proyecto en la VM](docs/img/ep01-estructura-laravel.png)

![Página inicial en lfts.local](docs/img/evidencia-pagina-inicial.png)

![Personalización de welcome.blade.php](docs/img/ep01-welcome-personalizado.png)

### Episodio 03 — Routing 101

Rutas definidas en `routes/web.php` con `Route::get()` y callback que devuelve `view()`:

| Ruta | Vista |
|------|-------|
| `/` | `resources/views/welcome.blade.php` |
| `/about` | `resources/views/about.blade.php` |
| `/contact` | `resources/views/contact.blade.php` |

![Página About](docs/img/ep03-about.png)

![Página Contact](docs/img/ep03-contact.png)

![Evidencia rutas — entregable](docs/img/evidencia-rutas.png)

### Episodio 04 — Layout Files

Componentes reutilizables en `resources/views/components/`:

- **`layout.blade.php`** — `@props(['title' => 'Laracast'])`, nav, `{{ $slot }}`
- **`card.blade.php`** — `{{ $attributes->merge(['class' => 'card']) }}`

Todas las vistas usan `<x-layout>`; `contact` además usa `<x-card class="max-w-400">`.

![Layout y slot](docs/img/ep04-layout-slot.png)

![Props, card y merge de clases](docs/img/ep04-layout-card-merge.png)

---

## Control de versiones

Mínimo **un commit por episodio**. Historial sugerido:

```
episodio-01: creación del proyecto Laravel en Vagrant
episodio-03: rutas GET para welcome, about y contact
```

---

## Empaquetado del entregable

```bash
cd ~/sites
tar cvfz ISW811_Proyecto1_Entregable01_HernandezGaritaYeison.tar.gz \
  --exclude=laravel-from-scratch-2026/node_modules \
  --exclude=laravel-from-scratch-2026/vendor \
  laravel-from-scratch-2026/
```

**No incluir** `vendor/` ni `node_modules/` — deben reinstalarse con `composer install` y `npm install`.

---

## Índice de capturas (`docs/img/`)

| Archivo | Descripción |
|---------|-------------|
| `ep01-estructura-laravel.png` | `ls -la` del proyecto en la VM |
| `ep01-lfts-navegador.png` | Laravel en `http://lfts.local` |
| `ep01-welcome-personalizado.png` | Edición de welcome en VS Code + navegador |
| `evidencia-pagina-inicial.png` | Evidencia obligatoria — página inicial |
| `ep03-about.png` | Ruta `/about` |
| `ep03-contact.png` | Ruta `/contact` |
| `evidencia-rutas.png` | Evidencia obligatoria — rutas creadas |

Más capturas se irán agregando en los episodios siguientes. Ver [docs/img/README.md](docs/img/README.md).
