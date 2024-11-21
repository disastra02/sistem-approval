## Sistem Approval Transaksi Pengeluaran

### Deskripsi
Aplikasi ini adalah REST API untuk mengelola transaksi pengeluaran dengan fitur sistem approval bertahap. Setiap pengeluaran harus melalui proses persetujuan oleh approver yang ditunjuk secara berurutan.
Fitur pembuatan data pengeluaran, sistem approval bertingkat, dan validasi proses approval sesuai aturan yang telah ditentukan.

### Teknologi
- Framework: Laravel 8 (PHP 7.4)
- Database: MySQL
- Dokumentasi: Swagger L5
- Testing: PHPUnit

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal aplikasi ini.

1. Clone Repository
```sh
    git clone https://github.com/disastra02/sistem-approval.git
```
2. Install Dependensi
```sh
    composer install
```
3. Konfigurasi
Salin file .env.example menjadi .env dan atur konfigurasi database:
```sh
    cp .env.example .env
    php artisan key:generate
```
4. Migrasi dan Seeder
Jalankan migrasi database dan seeder untuk data awal:
```sh
    php artisan migrate --seed
```
5. Jalankan Server
```sh
    php artisan serve
```


### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
