<?php

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

use Salt\Core\Models\Permission;
use Salt\Core\Models\Role;
use Salt\Core\Models\User;

beforeEach(function () {
    $this->testUser = User::factory()->create();

    $permission = Permission::factory()->create(['name' => 'view-admin-dashboard']);
    $permission2 = Permission::factory()->create(['name' => 'edit-settings']);
    $permission3 = Permission::factory()->create(['name' => 'manage-users']);
    $this->role = Role::factory()->create(['name' => 'admin']);
    $this->role->permissions()->attach($permission2);
    $this->role->permissions()->attach($permission);
});

it('can check if role has a specific permission', function () {
    assertTrue($this->role->hasPermission('view-admin-dashboard'));
    assertTrue($this->role->hasPermission('edit-settings'));
    assertFalse($this->role->hasPermission('manage-users'));
});

it('can get the correct role permissions', function () {
    assertTrue(count($this->role->permissions) === 2);
});
