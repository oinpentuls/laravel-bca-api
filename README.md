# BCA API
Package untuk development api bca.

## Daftar isi
- [Prerequisites](#prerequisites)  
- [Installation](#installation)  
- [Usage](#usage)
  - [completed](#completed)
  - [on development](#on-development)

## Prerequisites <a name="prerequisites" />

- Package ini dapat digunakan untuk laravel 8 atau untuk versi yang lebih tinggi, versi lebih lama dari 8 belum di tes.
- Penggunaan package ini membutuhkan kredensial dari BCA, untuk mendapatkannya anda bisa mengunjungi
  - [Halaman utama](https://developer.bca.co.id/)  
  - [Signup](https://developer.bca.co.id/component/apiportal/registration)
  - [Dokumentasi](https://developer.bca.co.id/documentation/)

## Installation <a name="installation" />

1. Install package via composer

```
composer require oinpentuls/laravel-bca-api
```

2. Opsional: Service provider otomatis terdaftar pada Laravel. Anda juga dapat menambahkan secara manual pada file `config/app.php`.
```php
'providers' => [
    // ...
    Oinpentuls\BcaApi\BCAServiceProvider::class,
];
```

3. Publish config: package ini akan mempublish file `bca.php`, jika anda mempunyai nama file yang sama, silakan di rename terlebih dahulu.
```
php artisan vendor:publish --provider="Oinpentuls\BcaApi\BCAServiceProvider"
```

## Usage <a name="usage" />

### Completed <a name="completed" />
#### Get Balance
```php
...
use Oinpentuls\BcaApi\BCA;


BCA::getBalance();
```

#### Get Statements
```php
$startDate = now()->format('Y-m-d');
$endDate = now()->format('Y-m-d');

BCA::getStatements(string $startDate, string $endDate);
```

### On Development <a name="on-development" />
#### Fund Transfer

###### Transfer sesama akun BCA
```php
$receiver = '020123131';
$amount = '1000.0';

BCA::fundTransfer(string $receiver, string $amount);
```
