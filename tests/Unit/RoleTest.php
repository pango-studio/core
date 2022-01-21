<?php

use Illuminate\Database\QueryException;

use function Pest\Faker\faker;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

use Salt\Core\Models\Permission;
use Salt\Core\Models\Role;

it('has a name', function () {
    $name = faker()->word;
    $role = Role::factory()
        ->create(
            [
                'name' => $name,
            ]
        );

    assertEquals($name, $role->name);
});

it('has a label', function () {
    $label = faker()->word;
    $role = Role::factory()
        ->create(
            [
                'label' => $label,
            ]
        );

    assertEquals($label, $role->label);
});

it('must have a unique name', function () {
    $this->expectException(QueryException::class);

    $name = faker()->word;

    Role::factory()
        ->create(
            [
                'name' => $name,
            ]
        );

    // Duplicated name
    Role::factory()
        ->create(
            [
                'name' => $name,
            ]
        );
})->throws(QueryException::class);

it('must have a unique label', function () {
    $this->expectException(QueryException::class);

    $label = faker()->word;

    Role::factory()
        ->create(
            [
                'label' => $label,
            ]
        );

    // Duplicated label
    Role::factory()
        ->create(
            [
                'label' => $label,
            ]
        );
})->throws(QueryException::class);

it('can have related permissions', function () {
    $permission = Permission::factory()->create();
    $role = Role::factory()->create();

    $role->permissions()->attach($permission);

    assertTrue($role->permissions->count() > 0);
});
