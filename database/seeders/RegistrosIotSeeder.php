<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrosIotSeeder extends Seeder
{
    public function run(): void
    {
        $registros = [];
        $totalUsuarios = 500; // Total de usuarios en la tabla tb_usuarios
        $usuariosPorRegistro = 200 / $totalUsuarios;

        for ($i = 0; $i < 200; $i++) {
            $idUsuario = (($i % $totalUsuarios) + 1); // Distribución equitativa de usuarios

            $registros[] = [
                'flujo_agua' => rand(1, 10) + rand(0, 99) / 10,
                'nivel_agua' => rand(1, 10) + rand(0, 99) / 10,
                'temp' => rand(15, 30) + rand(0, 99) / 100,
                'energia' => rand(0, 1) ? 'solar' : 'electricidad',
                'id_usuario' => $idUsuario,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tb_registros_iot')->insert($registros);
    }
}
