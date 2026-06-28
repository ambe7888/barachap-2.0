<?php

namespace Modules\CountryManage\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\CountryManage\app\Models\AreaFactory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

