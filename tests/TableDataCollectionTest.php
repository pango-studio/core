<?php

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\assertEquals;

use function PHPUnit\Framework\assertTrue;
use Salt\Core\Collections\TableDataCollection;
use Salt\Core\Models\User;

beforeEach(function () {
    $this->items = User::factory()
        ->count(10)
        ->create();
});

it('can paginate the data', function () {
    $collection = (new TableDataCollection($this->items))
        ->paginate();

    assertTrue($collection instanceof LengthAwarePaginator);
});

it('can search through the data', function () {
    $random_item = $this->items->random()->name;

    $request = new FormRequest([
        // Test fuzzy search
        'query' => "$random_item 1",
    ]);

    $search_collection = (new TableDataCollection($this->items))
        ->searchData($request, ['name']);

    assertEquals($random_item, $search_collection->first()['name']);
});

it('can be sorted', function () {
    $sorted_items = $this->items->sortBy('name');

    $request = new FormRequest([
        'column' => 'name',
        'direction' => 'asc',
    ]);

    $sorted_collection = (new TableDataCollection($this->items))
        ->sortTable($request);

    assertEquals($sorted_items->first()->name, $sorted_collection->first()['name']);
});
