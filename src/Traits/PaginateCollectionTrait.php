<?php

namespace Salt\Core\Traits;

use App\Helpers\PaginationHelper;
use Illuminate\Pagination\LengthAwarePaginator;

trait PaginateCollectionTrait
{
    /**
     * Paginate the collection
     *
     * @param integer $total Total number of items
     * @param integer $page Current page number
     * @param string $pageName Current page name
     * @return LengthAwarePaginator
     */
    public function paginateCollection(int $total = null, int $page = null, string $pageName = 'page'): LengthAwarePaginator
    {
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
        $per_page = $this->determineItemsPerPage($this->count());

        return new LengthAwarePaginator(
            $this->forPage($page, $per_page),
            $total ?: $this->count(),
            $per_page,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }

    /**
     * Determine the number of items to show per paginated table page
     *
     * @param integer $count the total number of items
     * @return integer 
     */
    private function determineItemsPerPage(int $count): int
    {
        $default = 10;

        if (isset($_GET['perPage']) && $_GET['perPage'] === 'All') {
            // Show all items on one page
            $per_page = $count;
        } else {
            $per_page = $_GET['perPage'] ?? $default;
        }

        return $per_page;
    }
}
