<?php

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertEquals;

use Salt\Core\Models\Permission;
use Salt\Core\Models\Role;
use Salt\Core\Models\User;
use Salt\Core\Exceptions\PermissionDependencyException;

beforeEach(function () {
    $this->test_user = User::factory()->create();

    $this->perm1 = Permission::factory()->create(['name' => 'view-admin-dashboard']);
    $this->perm2 = Permission::factory()->create(['name' => 'edit-settings']);
    $this->perm3 = Permission::factory()->create(['name' => 'manage-users']);

    $this->perm2->dependencies()->attach($this->perm1);
    $this->perm3->dependencies()->attach($this->perm1);
});

it('a permission can have 1 or more dependencies', function () {
    assertEquals(count($this->perm2->dependencies()->get()), 1);
    assertEquals(count($this->perm3->dependencies()->get()), 1);
});

it('a permission can have 1 or more dependants', function () {
    assertEquals(count($this->perm1->dependants()->get()), 2);
});

it('cannot allow a permission to be assigned to the user without the prerequisites', function () {
    $this->test_user->permissions()->attach($this->perm3);
})->throws(PermissionDependencyException::class, 'User requires view-admin-dashboard permission in order to be granted manage-users');

it('allows the dependency permission to be deleted and removes dependent relationship', function () {
    $this->perm1->delete();
    assertEquals(count($this->perm2->dependencies()->get()), 0);
    assertEquals(count($this->perm3->dependencies()->get()), 0);
});

it('allows the dependent permission to be deleted and removes dependent relationship', function () {
    $this->perm3->delete();
    assertEquals(count($this->perm1->dependants()->get()), 1);

    $this->perm2->delete();
    assertEquals(count($this->perm1->dependants()->get()), 0);
});
