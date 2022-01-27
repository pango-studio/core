# Collection Traits

These collection traits help to extend the functionality of Laravel's [Collection](https://laravel.com/docs/8.x/collections) class in a number of different ways. They are designed to be chainable - you can define a class which extends the base Collection class, use any number of these traits and chain them together to manipulate the data.

## `PaginateCollectionTrait`

The `PaginateCollectionTrait` provides a method `paginate()` which will paginate the data
within the collection. This works effectively the same way as the `paginate()` method when using Laravel's query builder or on an Eloquent query.

```php
/**
 * Paginate the collection data
 *
 * @param int $total Total number of items
 * @param int $page Current page number
 * @param string $pageName Current page name
 * @return LengthAwarePaginator
 */
public function paginate(int $total = null, int $page = null, string $pageName = 'page'): LengthAwarePaginator
```

```php
<?php

$items = [
    // An array of x number of items
];

$collection = (new TableDataCollection($items))
    ->paginate();
/**
 * $collection is assigned to a LengthAwarePaginator which contains an items
 * property that has the first x (10 by default) items on it, alongside perPage,
 * currentPage, path and other properties which can be used when building paginated
 * views
 */


```

## `SearchCollectionTrait`

The `SearchCollectionTrait` provides the `searchData()` method which will search for a specific term within specified keys in the collection data.

```php
/**
 * Search through the data for the specified term within the specified search fields
 *
 * Fuse docs - https://github.com/loilo/Fuse#usage
 *
 * @param Request $request
 * @param array $search_fields
 * @param float $threshold At what point does the match algorithm give up.
 * A threshold of 0.0 requires a perfect match (of both letters and location), a threshold of 1.0 would match anything.
 * @return static
 */
public function searchData(Request $request, array $search_fields = [], float $threshold = 0.6): Collection
```

It looks for a 'query' input item on the provided request which it uses as the search term. You can specify an array of $search_fields which it will look through to find the search term.

This method uses the Fuse library to allow for fuzzy searching. The final argument you can pass to `searchData()` is the threshold for when it should stop finding matches. It defaults to 0.6, which is quite lenient. If you are wanting to to return more precise matches, set it lower

```php
$items = [
    // An array of x number of items
];

$collection = (new TableDataCollection($items))
    ->searchData($request, ['name', 'description'], 0.2);
/**
 * Returns the collection data which is a close match for the
 * query string sent via the request within the name and description fields
 * present on the request
 */
```

## `SortCollectionTrait`

`SortCollectionTrait` provides a `sortTable()` method which allows you to sort the collection data by a specified table column in a specified direction.

```php
/**
 * Sort the table data by the specified column in the specified direction
 *
 * @param Request $request
 * @return static
 */
public function sortTable(Request $request): Collection
```

It looks for a 'column' input item on the request for determining the column to sort by and it looks for a 'direction' input item to determine the direction sort by (either asc or desc).

```php
$items = [
    // An array of x number of items
];

$request = FormRequest([
    'column' => 'name',
    'direction' => 'asc'
]);

$collection = (new TableDataCollection($items))
    ->sortTable($request);
/**
 * Returns the collection data sorted by the 'name' column in ascending order
 */
```
