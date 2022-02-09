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
     */
    public function __construct(
        public string $label,
        public string $route,
        public array|null $routeGroup,
        public string|null $icon,
        public string $viewBox = "0 0 32 32",
    ) {
    }
}
