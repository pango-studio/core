<?php

namespace Salt\Core\Data;

use Spatie\LaravelData\Data;

class MenuItem extends Data
{
    /**
     * @param string $label The label of the menu item
     * @param string $route The name of the Laravel route which the menu item links to
     * @param array|null $routeGroup The group of routes which this menu item belongs to. Used to trigger active link classes
     * @param string|null $icon The name of the icon to be shown next to the label
     * @param string $viewBox The viewbox of the icon component
     * @param MenuItem[]|null $subItems
     */
    public function __construct(
        public string $label,
        public string $route,
        public string|null $icon = null,
        public string $viewBox = "0 0 32 32",
        public array|null $subMenu = null
    ) {
    }

    /**
     * Add each MenuItem passed to this item's sub menu
     *
     * @param MenuItem[] ...$items
     */
    public function addSubMenu(...$items): void
    {
        foreach ($items as $item) {
            $this->subMenu[] = $item;
        }
    }
}
