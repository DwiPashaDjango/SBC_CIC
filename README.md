![Logo](https://i.pinimg.com/564x/5c/79/d2/5c79d26b179ce004eab414bd449e1d73.jpg)

# SBC CIC

## Penggunan Aplikasi

cloning repository github ini dengan cara 
```php
git clone https://github.com/DwiPashaDjango/SBC_CIC.git
```

jika belum menginstall git bisa menggunakan cara download zip pada tombol dropdown code, lalu ikuti langkah dibawah ini

buka direktori project di terminal anda lalu masuk ke direktori folder SBC_CIC dan ketikan kode di bawah ini
```php
cp .env.example .env
```

Setelah memasukan code di atas masukan juga kode berikut untuk menginstall library yang di gunakan aplikasi SBC_CIC
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

## Developer/Pembuat Sedang Tipes Harap Dimaklumkan

## Tips Cara Menangani Tipes
- Gunakan Selang Inpus
- Minum Dan Makan Teratur
- Tidur Teratur (Opsional)
- Masuk Rs (Wajib)
- Koma 6 Bulan (Opsional)
- Doekter Bowo Subianto Prajowitmiko (Ampuh)
