# Laravel Minify HTML
A minimal Laravel package that minifies the HTML output of all your Laravel web routes for production and staging environment.

## Usage
Install the package by running this composer command.
```console
composer require jamesgordo/laravel-minify-html
```
Add the package middleware to **$middlewareGroups['web']** array of the `app/Http/Kernel.php` file
```php
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            // ADD THIS LINE
            \JamesGordo\LaravelMinifyHtml\Http\Middleware\MinifyHtml::class,
        ],

```
