<?php

declare(strict_types=1);

namespace Codeat3\BladeFileIcons;

use BladeUI\Icons\Factory;
use Illuminate\Support\ServiceProvider;

final class BladeFileIconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('file-icons', [
                'path' => __DIR__.'/../resources/svg',
                'prefix' => 'fileicon',
            ]);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/svg' => public_path('vendor/blade-fileicon'),
            ], 'blade-fileicon');
        }
    }
}
