<?php

namespace Salt\Core\Models;

use Illuminate\Support\Facades\Auth;

class MenuBuilder
{
    protected array $menu = [];

    public function __construct(...$items)
    {
        $this->menu = $items;

        $this->user = Auth::user() ? User::find(Auth::user()->id) : null;
    }

    /**
     * Initializes a new menu.
     * If an array of menu items is passed, it prefills the menu with those items
     *
     * @return static
     */
    public static function new(array $items = null): static
    {
        if ($items) {
            return new static($items);
        } else {
            return new static();
        }
    }

    /**
     * Adds a new section to the menu with the given name as the index
     *
     * If menu items are also passed, it adds each of them to the section,
     * alternatively the section will be an empty array
     *
     * @param string $name
     * @param array ...$items
     * @return static
     */
    public function addSection(string $name,  array ...$items): static
    {
        $this->menu[$name] = [];

        if ($items) {
            foreach ($items as $item) {
                $this->addItem($name, ...$item);
            }
        };

        return $this;
    }

    /**
     * Adds an item to to section with the given section name,
     *
     * If the current user does not have permission to view the menu item,
     * it will not be included
     *
     * @param string $sectionName
     * @param array $item
     * @param Permission|null $permission
     * @return static
     */
    public function addItem(string $sectionName, array $item, string $permission  = null): static
    {
        if ($permission) {
            if ($this->user->hasPermission($permission)) {
                $this->menu[$sectionName][] = $item;
            }
        } else {
            $this->menu[$sectionName][] = $item;
        }

        return $this;
    }

    /**
     * Returns the full menu
     *
     * @return array
     */
    public function build(): array
    {
        return $this->menu;
    }
}
