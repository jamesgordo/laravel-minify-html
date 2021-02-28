<?php

namespace JamesGordo\LaravelMinifyHtml\App\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelMinifyHtmlProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->generateUpdatedKernel();
        $this->generateUpdatedRouteServiceProvider();

        // export updated kernel file.
        $this->publishes(
            [__DIR__ . '/../Http/Kernel.php' => app_path('Http/Kernel.php')],
            'update-app-kernel'
        );

        // export updated route provider file
        $this->publishes(
            [__DIR__ . '/../Providers/RouteServiceProvider.php' => app_path('Providers/RouteServiceProvider.php')],
            'update-route-provider'
        );
    }

    /**
     * Generates an updated Kernel.php file which
     * includes this package's middleware in
     * the registered route middlewares
     *
     * @return void
     */
    protected function generateUpdatedKernel()
    {
        // get the existing app kernel file
        $kernel = file_get_contents(app_path('Http/Kernel.php'));

        $replace = [
            "        'minify.html' => \JamesGordo\LaravelMinifyHtml\App\Http\Middleware\MinifyHtml::class,\n" => "",
            "'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class," => "'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,\n        'minify.html' => \JamesGordo\LaravelMinifyHtml\App\Http\Middleware\MinifyHtml::class,",
        ];

        // generate updated kernel file to be copied
        file_put_contents(
            __DIR__ . '/../Http/Kernel.php',
            str_replace(
                array_keys($replace),
                array_values($replace),
                $kernel
            )
        );
    }

    /**
     * Generates an updated RouteServiceProvider.php file
     * that enables the 'minify.html' middleware
     * across all registered web routes.
     *
     * @return void
     */
    protected function generateUpdatedRouteServiceProvider()
    {
        // get the existing app route service provider file
        $provider = file_get_contents(app_path('Providers/RouteServiceProvider.php'));

        $replace = [
            "            \$middleware = ( in_array(config('app.env'), ['staging', 'production']) ) ? ['web', 'minify.html'] : 'web';\n" => "",
            "            Route::middleware(\$middleware)" => "            Route::middleware('web')",
            "            Route::middleware('web')" => "            \$middleware = ( in_array(config('app.env'), ['staging', 'production']) ) ? ['web', 'minify.html'] : 'web';\n            Route::middleware(\$middleware)",
        ];

        file_put_contents(
            __DIR__ . '/../Providers/RouteServiceProvider.php',
            str_replace(
                array_keys($replace),
                array_values($replace),
                $provider
            )
        );
    }
}
