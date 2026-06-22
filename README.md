# ISW811 — Proyecto 1: Laravel From Scratch 2026

| Campo | Valor |
|-------|-------|
| **Estudiante** | Yeison Roberto Hernandez Garita |
| **Curso** | ISW811 — Aplicaciones Web con Software Libre |
| **Docente** | Misael Matamoros Soto |

## Estructura del proyecto

Código Laravel y documentación del curso están en **la misma carpeta**:

| Sistema | Ruta |
|---------|------|
| **Windows (host)** | `C:\Users\yeide\isw811\VMs\webserver\sites\laravel-from-scratch-2026` |
| **Debian (VM)** | `~/sites/laravel-from-scratch-2026` |

```
laravel-from-scratch-2026/
├── app/, routes/, resources/, ...   ← código Laravel
├── docs/
│   ├── entregable01.md              ← episodios 1–16
│   ├── entregable02.md
│   ├── entregable03.md
│   └── img/                         ← capturas de pantalla
├── .git/
├── composer.json
└── README.md                        ← este archivo
```

Documentación detallada: [docs/README.md](docs/README.md)

## Instalación local (después de descomprimir)

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

Ejecutar:

```bash
php artisan migrate
npm run dev          # terminal 1 — assets con Vite
```

## Publicación con Apache (virtual host)

Dominio local del proyecto: **`lfts.local`**

Archivo de configuración incluido en el repositorio:

```
apache-conf/lfts.local.conf
```

### En la VM (Debian)

```bash
# Copiar el virtual host
sudo cp ~/sites/laravel-from-scratch-2026/apache-conf/lfts.local.conf \
  /etc/apache2/sites-available/lfts.local.conf

# Habilitar sitio y módulo rewrite
sudo a2ensite lfts.local.conf
sudo a2enmod rewrite

# Verificar sintaxis y reiniciar Apache
sudo apache2ctl configtest
sudo systemctl restart apache2
sudo systemctl status apache2

# Probar desde la VM
curl -I -H "Host: lfts.local" http://127.0.0.1
```

> **Ruta DocumentRoot:** `/vagrant/sites/laravel-from-scratch-2026/public` — equivalente a `~/sites/laravel-from-scratch-2026/public` en la carpeta compartida.

### En Windows (archivo hosts)

Agregar en `C:\Windows\System32\drivers\etc\hosts` (como administrador):

```
192.168.33.10 lfts.local
```

IP de la VM según `Vagrantfile`: `192.168.33.10`

### Acceso desde el navegador

```
http://lfts.local
```

### Permisos Laravel (si aparece error 500)

```bash
cd ~/sites/laravel-from-scratch-2026
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## Empaquetado del entregable

```bash
cd ~/sites
tar cvfz ISW811_Proyecto1_Entregable01_HernandezGaritaYeison.tar.gz \
  --exclude=laravel-from-scratch-2026/node_modules \
  --exclude=laravel-from-scratch-2026/vendor \
  laravel-from-scratch-2026/
```

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
