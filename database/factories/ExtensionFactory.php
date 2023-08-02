<?php

namespace Database\Factories;

use App\Models\ExtensionModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtensionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExtensionModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_extension' => $this->faker->unique()->word,
        ];
    }
}
