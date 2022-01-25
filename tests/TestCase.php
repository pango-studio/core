<?php

namespace Salt\Core\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Salt\Core\CoreServiceProvider;

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

        // Auth0 API testing variables
        config()->set('core.auth0.app.client_id', 'YVotV35t67Byfx520GsAxtYD1V2i10aQ');
        config()->set('core.auth0.app.client_secret', 'IqprMJa-vlNNMkrFpZ44mb2AKmJtThTJPCgB0_eiwOEv5CW8SxUezfsidRC3AtXx');
        config()->set('core.auth0.app.db_connection', 'Username-Password-Authentication');

        config()->set('core.auth0.api.audience', 'https://alt-testing.eu.auth0.com/api/v2/');
        config()->set('core.auth0.api.client_id', 'CNBXTWofY5O3XmQOjvRhR7WoLya7JjF7');
        config()->set('core.auth0.api.client_secret', 'LHW6qIm9GjYRAMTbFYTG1dxj3P-0BQk166LZfieBTQkEmfJCQ9kKncKnwBKF-KKq');
        config()->set('core.auth0.api.domain', 'alt-testing.eu.auth0.com');


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
