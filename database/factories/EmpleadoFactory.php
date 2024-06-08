<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empleado>
 */
class EmpleadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id_tipo_documento"=>fake()->randomElement(["D"]),
            "numero_documento"=>fake()->numerify("########"),
            "apellido_paterno"=>fake()->firstName(),
            "apellido_materno"=>fake()->firstName(),
            "nombres"=>fake()->firstName(),
            "codigo_unico"=>fake()->lexify("???"),
            "distrito_ubigeo"=>"150101",
            "pais"=>"PE",
            "fecha_nacimiento"=>fake()->date('Y-m-d')
        ];
    }
}
