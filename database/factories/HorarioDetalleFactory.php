<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HorarioDetalle>
 */
class HorarioDetalleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "hora_inicio"=>"08:00",
            "hora_fin"=>"13:00",
            "dias"=>"1,2,3,4,5"
        ];
    }
}
