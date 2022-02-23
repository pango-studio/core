<?php

use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertEquals;
use Salt\Core\Facades\CurrentUser;

use Salt\Core\Models\Role;
use Salt\Core\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $role = Role::factory()->create(['name' => 'admin']);
    $this->admin->roles()->syncWithoutDetaching($role);

    $this->user = User::factory()->create();
});

it('returns the user model for the currently authenticated user', function () {
    actingAs($this->admin);

    assertEquals($this->admin->id, CurrentUser::get()->id);
});

it('returns the user model of the impersonated user during an active impersonation session', function () {
    actingAs($this->admin);
    User::startImpersonating($this->user->id);

    assertEquals($this->user->id, CurrentUser::get()->id);
});

it('returns the actual user model if impersonation is disabled', function () {
    actingAs($this->admin);
    User::startImpersonating($this->user->id);

    assertEquals($this->admin->id, CurrentUser::disableImpersonation()->get()->id);
});
