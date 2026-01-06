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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Paylabs Integration (Quick Setup)

Add the Paylabs PEM files and env variables as follows:

- Place private key at: `storage/keys/paylabs_private.pem` and public key at `storage/keys/paylabs_public.pem`.
- Ensure PHP can read the files (adjust filesystem permissions).
- Do NOT hardcode the private key value in code or `.env`.

Required `.env` variables (examples already present in project):

- `PAYLABS_MID=010529`
- `PAYLABS_BASE_URL=https://pay.paylabs.co.id`
- `PAYLABS_ENDPOINT=/pembayaran`
- `PAYLABS_CALLBACK_URL=http://localhost:8000/api/pembayaran/callback`
- `PAYLABS_PRIVATE_KEY_FILE=storage/keys/paylabs_private.pem`
- `PAYLABS_PUBLIC_KEY_FILE=storage/keys/paylabs_public.pem`

Test endpoints included for local/dev testing (keep these in place):

- `GET /api/dev/paylabs/test-signature` — generates a signature for sample payload (requires auth).
- `GET /api/dev/paylabs/test-connection` — attempts a test request to Paylabs (requires auth).
- `POST /api/dev/paylabs/simulate-callback` — simulate an incoming Paylabs callback to the webhook (useful for testing).

How it works:

- `App\Services\PaylabsService` builds a sorted payload, signs it with RSA SHA256 using OpenSSL, and base64-encodes the signature.
- The service reads the private/public key from `.env`/config and supports relative paths via `base_path()`/`storage_path()`.
- The public callback route is available at `/api/pembayaran/callback` and handled by `App\Http\Controllers\PembayaranController@webhook`.

Run tests manually by calling the dev routes after authenticating as a developer user.
