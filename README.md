# LuxBliss Vogue

Aplikasi ini merupakan tugas kelompok mata kuliah RPL (Rekaya Perangkat Lunak) & Agile Scrum Introduction. Aplikasi ini bertujuan untuk memudahkan penyewaan Kebaya & Jas di sekitar daerah cirebon

## Developer/Pembuat Aplikasi

## Penggunan Aplikasi

buka direktori project di terminal anda lalu masuk ke direktori folder LuxBliss Vogue dan ketikan kode di bawah ini
```php
cp .env.example .env
```

Setelah memasukan code di atas masukan juga kode berikut untuk menginstall library yang di gunakan aplikasi LuxBliss Vogue
```php
composer install
```

Setelah itu masukan juga code di bawah ini untuk mengaktifkan apliasinya
```php
php artisan optimize:clear
```
```php
php artisan key:generate
```
```php
php artisan migrate
```
```php
php artisan db:seed
```

Setelah itu tinggal jalankan server aplikasi dengan cara mengetikan code dibawah ini
``` php
php artisan serve
```

Lalu enjoy dan gunakan aplikasi sebaik-baiknya
