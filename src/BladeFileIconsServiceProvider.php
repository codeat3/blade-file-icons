<?php

declare(strict_types=1);

namespace Codeat3\BladeFileIcons;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class BladeFileIconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('blade-file-icons', []);

            $factory->add('file-icons', array_merge(['path' => __DIR__ . '/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-file-icons.php', 'blade-file-icons');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/svg' => public_path('vendor/blade-file-icons'),
            ], 'blade-fileicon'); // TODO: change this alias to `blade-file-icons` in next major release

            $this->publishes([
                __DIR__ . '/../config/blade-file-icons.php' => $this->app->configPath('blade-file-icons.php'),
            ], 'blade-file-icons-config');
        }
    }
}
