<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PoblarAsignacionesDemo extends Command
{
    protected $signature = 'demo:poblar-asignaciones';
    protected $description = 'Limpia y crea una asignación de prueba para el moderador y proyecto existentes';

    public function handle()
    {
        $moderador = DB::connection('mongodb')->collection('users')->where('rol_id', 'moderador')->first();
        $proyecto = DB::connection('mongodb')->collection('proyectos')->first();
        if (!$moderador || !$proyecto) {
            $this->error('No se encontró moderador o proyecto.');
            return;
        }
        // Limpiar asignaciones del moderador
        DB::connection('mongodb')->collection('asignaciones')->where('moderador_id', (string) $moderador['_id'])->delete();
        // Crear asignación demo
        DB::connection('mongodb')->collection('asignaciones')->insert([
            'proyecto_id' => (string) $proyecto['_id'],
            'moderador_id' => (string) $moderador['_id'],
            'fecha_asignacion' => now(),
        ]);
        $this->info('Asignación demo creada para moderador: ' . $moderador['name'] . ' y proyecto: ' . $proyecto['nombre']);
    }
}
