<?php

namespace Database\Seeders;

use App\Models\Horario;
use App\Models\HorarioDetalle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $horarios = Horario::factory(1)->create();

        foreach ($horarios as $key => $value) {
            HorarioDetalle::factory(1)->create([
                "id_horario"=>$value->id
            ]);
        }

    }
}
