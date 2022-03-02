<?php

namespace Salt\Core\Traits;

trait SearchOrSortTrait
{
    public function scopeSearchOrSort($query, $request, $sort_columns = [])
    {
        $sort_column = isset($request['column']) && !empty($request['column']) ? $request['column'] : null;
        $sort_direction = isset($request['direction']) ? $request['direction'] : 'asc';
        $term = isset($request['query']) ? $request['query'] : '';

        if ($sort_columns && isset($sort_columns[0])) {
            $query->where($sort_columns[0], 'LIKE', '%' . $term . '%');
        }
        foreach ($sort_columns as $key => $column) {
            if ($key == 0) {
                continue;
            }
            $query->orWhere($column, 'LIKE', '%' . $term . '%');
        };
        if ($sort_column) {
            $query->orderBy($sort_column, $sort_direction);
        }

        return $query;
    }
}
