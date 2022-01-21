<?php

use Illuminate\Database\QueryException;
use function Pest\Faker\faker;
use function PHPUnit\Framework\assertEquals;

use function PHPUnit\Framework\assertTrue;
use Salt\Core\Models\Permission;

use Salt\Core\Models\Role;

it('has a name', function () {
    $name = faker()->word;
    $permission = Permission::factory()
        ->create(
            [
                'name' => $name,
            ]
        );

    assertEquals($name, $permission->name);
});

it('must have a unique name', function () {
    $this->expectException(QueryException::class);

    $name = faker()->word;

    Permission::factory()
        ->create(
            [
                'name' => $name,
            ]
        );

    // Duplicated name
    Permission::factory()
        ->create(
            [
                'name' => $name,
            ]
        );
})->throws(QueryException::class);

it('can have related roles', function () {
    $permission = Permission::factory()->create();
    $role = Role::factory()->create();

    $permission->roles()->attach($role);

    assertTrue($permission->roles->count() > 0);
});

it('has an array of permission options', function () {
    $permission = Permission::factory()->create();

    assertTrue(count($permission::options()) > 0);
});
