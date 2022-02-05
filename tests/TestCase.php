<?php

namespace Salt\Core\Tests;

use Salt\Core\Models\User;
use Salt\Core\CoreServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Salt\\Core\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            CoreServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $this->runMigrations();
    }

    protected function runMigrations()
    {
        $permissionsTable = include __DIR__ . '/../database/migrations/create_permissions_table.php.stub';
        $permissionsTable->up();

        $rolesTable = include __DIR__ . '/../database/migrations/create_roles_table.php.stub';
        $rolesTable->up();

        $permissionRolesTable = include  __DIR__ . '/../database/migrations/create_permission_roles_table.php.stub';
        $permissionRolesTable->up();

        $roleUsersTable = include  __DIR__ . '/../database/migrations/create_role_users_table.php.stub';
        $roleUsersTable->up();

        $settingsTable = include  __DIR__ . '/../database/migrations/create_settings_table.php.stub';
        $settingsTable->up();

        $usersTable = include  __DIR__ . '/../database/migrations/create_users_table.php.stub';
        $usersTable->up();
    }
}
