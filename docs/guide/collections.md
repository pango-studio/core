# Collections

## `TableDataCollection`

This package provides a custom collection, `TableDataCollection`, which exists to make working with data in table formats easier. It inherits `PaginateCollectionTrait`, `SearchCollectionTrait` and `SortCollectionTrait`. More info about those traits can be found in the 'Collection Traits' section of this documentation.

```php
<?php

$items = [
    // This can be just an array of data, or an eloquent query or anything
    // else which can be wrapped in Laravel's `collect()` method
];

$collection = (new TableDataCollection($items))
    ->searchData($request, ['name', 'description'])
    ->sortTable($request)
    ->paginate();
```
