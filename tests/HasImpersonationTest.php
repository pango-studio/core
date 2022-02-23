<?php

use Illuminate\Support\Facades\Auth;
use Salt\Core\Models\Role;
use Salt\Core\Models\User;
use Illuminate\Support\Facades\Session;
use Salt\Core\Facades\CurrentUser;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $this->admin->roles()->syncWithoutDetaching($role);

    $this->user = User::factory()->create();
});

test('An impersonation session can be started', function () {
    actingAs($this->admin);

    assertFalse(Session::has('impersonation'));

    User::startImpersonating($this->user->id);
    assertEquals(Session::get('impersonation'), $this->user->id);
});

test('An impersonation session can be stopped', function () {
    actingAs($this->admin);

    User::startImpersonating($this->user->id);
    assertTrue(Session::has('impersonation'));

    User::stopImpersonating();
    assertFalse(Session::has('impersonation'));
});

test('It can check if there is an active impersonation session', function () {
    actingAs($this->admin);

    User::startImpersonating($this->user->id);
    assertTrue(User::isImpersonating());
});

test('It can get the user model for the impersonated user', function () {
    actingAs($this->admin);

    User::startImpersonating($this->user->id);

    $impersonatedUser = User::getImpersonatedUser();
    assertEquals($impersonatedUser->id, $this->user->id);
});
