<?php

use function PHPUnit\Framework\assertEquals;

use Salt\Core\Models\User;

beforeEach(function () {
    User::factory(20)->create();
});

it('allows for searching columns for results which match a search query', function () {
    $user = User::all()->random();

    $request = ['query' => $user->name];
    assertEquals(User::searchOrSort($request, ['name'])->first(), $user);

    $request = ['query' => $user->email];
    assertEquals(User::searchOrSort($request, ['email'])->first(), $user);
});

it('sorts by the column and direction specified', function () {
    $request = ['column' => 'name', 'direction' => 'asc'];

    assertEquals(User::searchOrSort($request), User::orderBy('name', 'asc'));

    $request = ['column' => 'email', 'direction' => 'desc'];
    assertEquals(User::searchOrSort($request), User::orderBy('email', 'desc'));
});
