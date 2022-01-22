<?php

namespace Salt\Core;

use Illuminate\Routing\Router;
use Salt\Core\Commands\CoreCommand;
use Salt\Core\Commands\GenerateOptionsClassCommand;
use Salt\Core\Middleware\PermissionChecker;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CoreServiceProvider extends PackageServiceProvider
{
    public function bootingPackage()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('hasPermission', PermissionChecker::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateOptionsClassCommand::class,
            ]);
        }
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('core')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations(
                [
                    'create_permissions_table',
                    'create_roles_table',
                    'create_permission_roles_table',
                    'create_role_users_table',
                    'create_settings_table',
                ]
            )->hasCommand(CoreCommand::class);
    }
}
