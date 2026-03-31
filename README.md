## About Project

Persiapan device:

- Text editor seperti [Visual Studio Code](https://code.visualstudio.com/) atau yang lainnya.
- Web server seperti [Laragon](https://laragon.org/) atau yang lainnya.
- [Composer](https://getcomposer.org/).
- [Git](https://git-scm.com/).
- [Node js](https://nodejs.org/id/download).

Persiapan run project:
- Run web server
- Siapkan `.env` dengan contoh dari `.env.example`
```cp .env.example .env```
- `composer install`
- `php artisan key:generate`
- `php artisan migrate –seed`
- `php artisan serve`
- `npm run dev` dijalankan pada tab yang lain
