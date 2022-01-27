<?php

namespace Salt\Core\Traits;

use Fuse\Fuse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait SearchCollectionTrait
{
    /**
     * Search the collection for the specified term within the specified search fields
     * 
     * Fuse docs - https://github.com/loilo/Fuse#usage
     *
     * @param Request $request
     * @param array $search_fields
     * @param float $threshold At what point does the match algorithm give up. 
     * A threshold of 0.0 requires a perfect match (of both letters and location), a threshold of 1.0 would match anything.
     * @return static
     */
    public function searchCollection(Request $request, array $search_fields = [], float $threshold = 0.6): Collection
    {
        $term = strtolower($request->input('query'));
        if ($term) {
            $options = [
                'keys' => $search_fields,
                'includeScore' => true,
                'threshold' => $threshold
            ];
            // Hacky method to turn array of objects into array of arrays
            $values = json_decode(json_encode($this->values()->all()), true);

            $fuse = new Fuse($values, $options);

            // Fuse returns an array of arrays, each with an item index and a reference index
            // We only want the contents of the item index her
            $result = array_column($fuse->search($term), 'item');

            // This actually works?!
            return new $this($result);
        } else {
            return $this;
        }
    }
}
