<?php

use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertFalse;

use Salt\Core\Models\Permission;
use Salt\Core\Models\Role;
use Salt\Core\Models\User;

beforeEach(function () {
    $this->testUser = User::factory()->create();
});

it('can check if user has a specific role', function () {
    assertFalse($this->testUser->hasRole('user'));

    $role = Role::create(['name' => 'user', 'label' => 'User']);
    $this->testUser->roles()->sync($role);

    assertTrue($this->testUser->hasRole('user'));
});

it('can add a role', function () {
    Role::create(['name' => 'user', 'label' => 'User']);
    $this->testUser->addRole('user');

    assertTrue($this->testUser->hasRole('user'));
});

it('can check if user has a specific permission', function () {
    assertFalse($this->testUser->hasPermission('view-admin-dashboard'));

    $permission = Permission::factory()->create(['name' => 'view-admin-dashboard']);

    $role = Role::factory()->create(['name' => 'admin']);
    $role->permissions()->attach($permission);

    $this->testUser->addRole('admin');

    assertTrue($this->testUser->hasPermission('view-admin-dashboard'));
});

it('can fetch all roles that the user belongs to', function () {
    $role = Role::factory()->create(['name' => 'admin']);
    $role2 = Role::factory()->create(['name' => 'user']);

    $this->testUser->roles()->syncWithoutDetaching($role);
    $this->testUser->roles()->syncWithoutDetaching($role2);

    assertTrue($this->testUser->roles->contains($role));
});

it('can fetch all permissions that the user has', function () {
    $permission = Permission::factory()->create(['name' => 'view-admin-dashboard']);
    $permission2 = Permission::factory()->create(['name' => 'manage-users']);

    $role = Role::factory()->create(['name' => 'admin']);
    $role->permissions()->attach($permission);
    $role->permissions()->attach($permission2);

    $this->testUser->roles()->syncWithoutDetaching($role);

    assertTrue($this->testUser->permissions()->contains('name', $permission->name));
    assertTrue($this->testUser->permissions()->contains('name', $permission2->name));
});
