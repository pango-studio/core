<?php

use function PHPUnit\Framework\assertEquals;
use Salt\Core\Models\User;

beforeEach(function () {
    User::factory(20)->create();
});

it('paginates with 10 items per page by default', function () {

    assertEquals(User::perPagePaginate(), User::paginate(10));
});

it('changes the number of items per page according to the perPage GET variable', function () {
    $_GET['perPage'] = 20;
    assertEquals(User::perPagePaginate(), User::paginate(20));

    $_GET['perPage'] = 30;
    assertEquals(User::perPagePaginate(), User::paginate(30));
});
