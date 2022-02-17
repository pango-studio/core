<?php

namespace Salt\Core\Data;

use Spatie\LaravelData\Data;

class MenuSectionItem extends Data
{
    /**
     * @param MenuItem $data The menu item to be added to the section
     * @param string|null $permission The permission that the user needs in order to view the menu item
     */
    public function __construct(
        public MenuItem $data,
        public ?string $permission = null
    ) {
    }
}
