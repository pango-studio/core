<?php

namespace Salt\Core;

use Illuminate\Routing\Router;
use Salt\Core\Models\UserRole;
use Salt\Core\Helpers\GetCurrentUser;
use Spatie\LaravelPackageTools\Package;
use Salt\Core\Observers\UserRoleObserver;
use Salt\Core\Http\Middleware\PermissionChecker;
use Salt\Core\Commands\GenerateOptionsClassCommand;
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

        $this->app->bind('currentuser', function () {
            $userModel = config('core.user');
            return new GetCurrentUser(new $userModel());
        });

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
            ->hasMigrations(
                [
                    'create_permissions_table',
                    'create_roles_table',
                    'create_permission_role_table',
                    'create_permission_user_table',
                    'create_role_user_table',
                    'create_settings_table',
                ]
            )
            ->hasCommand(GenerateOptionsClassCommand::class)
            ->hasTranslations();
    }

}
