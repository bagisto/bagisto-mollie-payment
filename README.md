# Introduction

Mollie helps businesses of all sizes to sell and build more efficiently with a solid but easy-to-use payment solution. Start growing your business today with effortless payments.

- Hassle free payment.
- During the payment you have option to pay through various payment method like Paypal, Credit card etc.
- Admin can set the API key and payment description.

## Requirements:

- **Bagisto**: v1.3.2

- Up-to-date OpenSSL (or other SSL/TLS toolkit)

## Installation with composer:
- Run the following command
```
composer require bagisto/bagisto-mollie-payment
```

- Goto config/concord.php file and add following line under 'modules'
```php
\Webkul\Mollie\Providers\ModuleServiceProvider::class
```

- Run these commands below to complete the setup
```
composer dump-autoload
```

```
composer require mollie/laravel-mollie:^2.11
php artisan route:cache
php artisan optimize
```

## Installation without composer:

- Unzip the respective extension zip and then merge "packages" folder into project root directory.
- Goto config/app.php file and add following line under 'providers'

```
Webkul\Mollie\Providers\MollieServiceProvider::class
```

- Goto composer.json file and add following line under 'psr-4'

```
"Webkul\\Mollie\\": "packages/Webkul/Mollie/src"
```

- Run these commands below to complete the setup

```
composer dump-autoload
```
```
composer require mollie/laravel-mollie:^2.11
```
```
php artisan route:cache
```
```
php artisan optimize
```

> That's it, now just execute the project on your specified domain.
