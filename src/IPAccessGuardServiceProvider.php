<?php

namespace Siyaludeio\IPAccessGuard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Siyaludeio\IPAccessGuard\Http\Middleware\IPAccessMiddleware;

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
        $this->app->make('files')->ensureDirectoryExists(config('ip-access-guard.file_path'), 0755, true);

        $filePath = config('ip-access-guard.file_path') . '/' . config('ip-access-guard.file_name');
        $this->app->make('files')->put($filePath, encrypt(json_encode([])));

        // Publishing the configuration file
        $this->publishes([
            __DIR__ . '/../config/' . $this->packageName . '.php' => config_path($this->packageName . '.php'),
        ], $this->vendorName . '-' . $this->packageName);
    }
}
