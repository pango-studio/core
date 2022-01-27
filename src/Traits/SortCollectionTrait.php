<?php

namespace Salt\Core\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait SortCollectionTrait
{
    /**
     * Sort the table data by the specified column in the specified direction
     *
     * @param Request $request
     * @return static
     */
    public function sortTable(Request $request): Collection
    {
        $sort_column = $request->input('column') ?? 'id';
        $sort_direction = $request->input('direction') ?? 'desc';

        if ($sort_column) {
            switch ($sort_direction) {
                case 'asc':
                    return $this->sortBy($sort_column)->values();
                case 'desc':
                    return $this->sortByDesc($sort_column)->values();
                default:
                    break;
            }
        }

        return $this;
    }
}
