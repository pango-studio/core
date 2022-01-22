<?php

namespace Salt\Core\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Salt\Core\CoreServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

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

    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->softDeletes();
        });

        $this->testUser = User::create(['email' => 'test@user.com']);
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
    }
}
