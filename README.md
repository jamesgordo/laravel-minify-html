# Laravel Minify HTML
A minimal Laravel package that minifies the HTML output of all your Laravel web routes for production and staging environment.

## Usage
Install the package by running this composer command.
```console
composer require jamesgordo/laravel-minify-html
```
Add the package service provider to your `config/app.php`
```php
    ...
    ...

    /*
     * Application Service Providers...
     */
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    // App\Providers\BroadcastServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,

    // ADD THIS LINE MANUALLY
    JamesGordo\LaravelMinifyHtml\App\Providers\LaravelMinifyHtmlProvider::class,

    ...
    ...
```
Register the package middleware to the current app route middlewares by running this command
```console
php artisan vendor:publish --tag=update-app-kernel --force

# Expected Output
Copied File [\packages\jamesgordo\laravelminifyhtml\src\app\Http\Kernel.php] To [\app\Http\Kernel.php]
Publishing complete.
```
The command will automatically add this line to your `app/Http/Kernel.php` file
```php
    ...
    ...
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

    // THIS LINE IS AUTOMATICALLY ADDED BY RUNNING THE COMMAND
    'minify.html' => \JamesGordo\LaravelMinifyHtml\App\Http\Middleware\MinifyHtml::class,
```
Enable the middleware to all the web routes by running this command
```console
php artisan vendor:publish --tag=update-route-provider --force

# Expected Output
Copied File [\packages\jamesgordo\laravelminifyhtml\src\app\Providers\RouteServiceProvider.php] To [\app\Providers\RouteServiceProvider.php]
Publishing complete.
```
That command will automatically add this line to your `app/Providers/RouteServiceProvider.php` file
```php
    ...
    ...

    // THIS LINE IS AUTOMATICALLY ADDED BY RUNNING THE COMMAND
    $middleware = ( in_array(config('app.env'), ['staging', 'production']) ) ? ['web', 'minify.html'] : 'web';
    Route::middleware($middleware) // THIS LINE IS UPDATED
        ->namespace($this->namespace)
        ->group(base_path('routes/web.php'));

    ...
    ...
```
