<?php

namespace Salt\Core\Collections;

use Illuminate\Support\Collection;
use Salt\Core\Traits\PaginateCollectionTrait;
use Salt\Core\Traits\SearchCollectionTrait;
use Salt\Core\Traits\SortCollectionTrait;

class TableDataCollection extends Collection
{
    use PaginateCollectionTrait;
    use SearchCollectionTrait;
    use SortCollectionTrait;
}
