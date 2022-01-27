<?php

namespace Salt\Core\Collections;

use Illuminate\Support\Collection;
use Salt\Core\Traits\PaginateCollectionTrait;
use Salt\Core\Traits\SearchCollectionTrait;
use Salt\Core\Traits\SortCollectionTrait;

class CoreCollection extends Collection
{
    use PaginateCollectionTrait, SearchCollectionTrait, SortCollectionTrait;
}
