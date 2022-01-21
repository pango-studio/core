<?php

namespace Salt\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Salt\Core\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'label' => $this->faker->sentence(2),
            'name' => $this->faker->sentence(2),
        ];
    }
}
