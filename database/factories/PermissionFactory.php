<?php

namespace Salt\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Salt\Core\Models\Permission;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'label' => $this->faker->sentence(2)
        ];
    }
}
