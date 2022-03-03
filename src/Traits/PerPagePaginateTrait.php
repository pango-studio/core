<?php

namespace Salt\Core\Traits;

trait PerPagePaginateTrait
{
    public function scopePerPagePaginate($query)
    {
        $per_page = $_GET['perPage'] ?? 10;

        return $query->paginate($per_page);
    }
}
