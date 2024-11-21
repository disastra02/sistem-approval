<a id="readme-top"></a>

## Sistem Approval Transaksi Pengeluaran

### Deskripsi
Aplikasi ini adalah REST API untuk mengelola transaksi pengeluaran dengan fitur sistem approval bertahap. Setiap pengeluaran harus melalui proses persetujuan oleh approver yang ditunjuk secara berurutan.
Fitur pembuatan data pengeluaran, sistem approval bertingkat, dan validasi proses approval sesuai aturan yang telah ditentukan.

REST API dirancang dengan pendekatan modern menggunakan Request Validation, Repository Pattern, dan dilengkapi dengan Swagger UI untuk dokumentasi interaktif.

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
5. Generate Dokumentasi Swagger
L5 Swagger memerlukan perintah berikut untuk menghasilkan file dokumentasi:
```sh
    php artisan l5-swagger:generate
```
6. Jalankan Server
```sh
    php artisan serve
```
7. Akses Swagger UI 
```sh
    http://localhost:8000/api/documentation
```

## Menjalankan Unit Test

Pastikan langkah-langkah diatas telah terinstal, setelah itu:

1. Menjalankan Pengujian
```sh
    php artisan test
```
2. Hasil Contoh Pengujian
```sh
    PASS  Tests\Unit\ExampleTest
    ✓ example

    PASS  Tests\Feature\ApprovalStageTest
    ✓ it requires approver id to be not empty or null
    ✓ it requires approver id to be unique when adding approver
    ✓ it can create an approver with unique name
    ✓ update approval stage
    ✓ update approval stage invalid approver

    PASS  Tests\Feature\ApproverTest
    ✓ it requires name to be not empty or null
    ✓ it requires name to be unique when adding approver
    ✓ it can create an approver with unique name

    PASS  Tests\Feature\ExampleTest
    ✓ example

    PASS  Tests\Feature\ExpenseTest
    ✓ expense approval process

    Tests:  11 passed
    Time:   1.34s
```

README ini dirancang agar pengguna baru dapat memahami aplikasi ini. Terimakasih

<p align="right">(<a href="#readme-top">back to top</a>)</p>