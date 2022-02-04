<?php

namespace Salt\Core\Models;

use Exception;
use Illuminate\Support\Facades\Auth;

class MenuBuilder
{
    protected array $menu = [];

    public function __construct()
    {
        $this->user = Auth::user() ? User::find(Auth::user()->id) : null;
    }


    public static function new(): static
    {
        return new static();
    }

    public function addSection(string $name,  array ...$items): static
    {
        $this->menu[$name] = [];

        if ($items) {
            foreach ($items as $item) {
                $this->addLink($name, ...$item);
            }
        };

        return $this;
    }

    public function addLink(string $sectionName, array $items, Permission $permission = null): static
    {
        if ($permission) {
            if ($this->user->hasPermission($permission->name)) {
                $this->menu[$sectionName][] = $items;
            }
        } else {
            $this->menu[$sectionName][] = $items;
        }

        return $this;
    }

    public function build()
    {
        return $this->menu;
    }
}
