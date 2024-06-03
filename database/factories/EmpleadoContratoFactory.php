<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmpleadoContrato>
 */
class EmpleadoContratoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            "fecha_inicio"=>fake()->date("Y-m-d"),
            "salario"=>1000,
            "costo_hora"=>8,
            "costo_dia"=>8,
            "dias_trabajo"=>10,
            "horas_dia"=>10
        ];
    }
}
