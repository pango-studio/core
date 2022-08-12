<?php

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertEquals;

use Salt\Core\Models\Permission;
use Salt\Core\Models\Role;
use Salt\Core\Models\User;
use Salt\Core\Exceptions\PermissionDependencyException;

beforeEach(function () {
    $this->testUser = User::factory()->create();

    $this->perm1 = Permission::factory()->create(['name' => 'view-admin-dashboard']);
    $this->perm2 = Permission::factory()->create(['name' => 'edit-settings']);
    $this->perm3 = Permission::factory()->create(['name' => 'manage-users']);

    $this->perm2->dependencies()->attach($this->perm1);
    $this->perm3->dependencies()->attach($this->perm1);
});

it('a permission can have 1 or more dependencies', function () {
    assertEquals(count($this->perm2->dependencies), 1);
    assertEquals(count($this->perm3->dependencies), 1);
});

it('a permission can have 1 or more dependants', function () {
    assertEquals(count($this->perm1->dependants), 2);
});

it('cannot allow a permission to be assigned to the user without the prerequisites', function () {
    $this->testUser->permissions()->attach($this->perm3);
})->throws(PermissionDependencyException::class, 'User requires view-admin-dashboard permission in order to be granted manage-users');
