<?php

namespace Salt\Core\Models;

use Salt\Core\Data\MenuItem;
use Salt\Core\Data\MenuSectionItem;
use Salt\Core\Facades\CurrentUser;

class MenuBuilder
{
    protected array $menu = [];
    protected $user;

    /**
     * @param Array|null $items
     */
    final public function __construct(...$items)
    {
        $this->menu = $items;

        $this->user = CurrentUser::get();
    }

    /**
     * Initializes a new menu.
     * If an array of menu items is passed, it prefills the menu with those items
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
     */
    public function addSection(string $name, MenuSectionItem ...$items): static
    {
        $this->menu[$name] = [];

        if ($items) {
            foreach ($items as $item) {
               
                $this->addItemToSection($item->data, $name, $item->permission);
            }
        };
       
        return $this;
    }

    /**
     * Adds an item to to section with the given section name,
     *
     * If the current user does not have permission to view the menu item,
     * it will not be included
     */
    public function addItem(MenuItem $item, string $sectionName = null, string $permission = null): static
    {
        !is_null($sectionName)
            ? $this->addItemToSection($item, $sectionName, $permission)
            : $this->addItemToMenu($item, $permission);

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

    /**
     * Add the item to the menu
     */
    private function addItemToMenu(MenuItem $item, string $permission = null): void
    {
        if ($permission) {
            if ($this->user->hasPermission($permission)) {
                $this->menu[] = $item;
            }
        } else {
            $this->menu[] = $item;
        }
    }

    /**
     * Add the item to a menu section
     */
    private function addItemToSection(MenuItem $item, string $sectionName, string $permission = null): void
    {
        if ($permission) {
            if ($this->user->hasPermission($permission)) {
                $this->menu[$sectionName][] = $item;
            }
        } else {
            $this->menu[$sectionName][] = $item;
        }
    }

    public function getMenu()
    {
        return $this->menu;
    }
}
