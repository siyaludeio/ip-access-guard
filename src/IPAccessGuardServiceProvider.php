<?php

namespace Siyaludeio\IPAccessGuard;

use Illuminate\Support\ServiceProvider;
use Siyaludeio\IPAccessGuard\Middleware\IPAccessMiddleware;
use Illuminate\Contracts\Http\Kernel as HttpKernel;

class IPAccessGuardServiceProvider extends ServiceProvider
{
    protected string $vendorName = 'siyaludeio';
    protected string $packageName = 'ip-access-guard';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {

        // Load routes from the routes.php file
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        $kernel = app(HttpKernel::class);
        $kernel->pushMiddleware(IPAccessMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {

        // Merge package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/' . $this->packageName . '.php', $this->packageName);
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        //echo config_path($this->packageName.'.php'); exit;
        //echo __DIR__.'/../config/'.$this->packageName.'.php'; exit;

        // Publishing the configuration file
        $this->publishes([
            __DIR__ . '/../config/' . $this->packageName . '.php' => config_path($this->packageName . '.php'),
        ], $this->vendorName . '-' . $this->packageName);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [$this->packageName];
    }
}
