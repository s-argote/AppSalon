<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tablas (opcional, si quieres empezar de cero)
        DB::table('services')->truncate();
        DB::table('users')->truncate();

        // Crear usuarios
        $admin = \App\Models\User::create([
            'nombre' => 'Administrador',
            'apellido' => 'Admin',
            'email' => 'admin@salonflow.com',
            'password' => Hash::make('12345678'),
            'telefono' => null,
            'admin' => true,
            'confirmado' => true,
            'token' => Str::random(30),
        ]);

        $cliente = \App\Models\User::create([
            'nombre' => 'Cliente',
            'apellido' => 'Demo',
            'email' => 'cliente@salonflow.com',
            'password' => Hash::make('12345678'),
            'telefono' => '3133633545',
            'admin' => false,
            'confirmado' => true,
            'token' => Str::random(30),
        ]);

        // Crear servicios
        $services = [
            [
                'nombre' => 'Corte de Cabello Hombre',
                'precio' => 20000.00,
                'duracion' => 30,
                'descripcion' => 'Corte moderno o clásico según preferencia, incluye lavado rápido.'
            ],
            [
                'nombre' => 'Corte de Cabello Dama',
                'precio' => 50000.00,
                'duracion' => 60,
                'descripcion' => 'Corte personalizado para dama, incluye lavado y secado básico.'
            ],
            [
                'nombre' => 'Manicure y Pedicure',
                'precio' => 30000.00,
                'duracion' => 45,
                'descripcion' => 'Servicio completo de cuidado de uñas, hidratación y esmaltado.'
            ],
            [
                'nombre' => 'Tinte Capilar Completo',
                'precio' => 70000.00,
                'duracion' => 120,
                'descripcion' => 'Aplicación de color completo, lavado y secado. Incluye asesoría de tono.'
            ],
            [
                'nombre' => 'Tratamiento Capilar Hidratante',
                'precio' => 50000.00,
                'duracion' => 90,
                'descripcion' => 'Tratamiento profundo para restaurar brillo y suavidad del cabello.'
            ],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }

        // Opcional: mostrar mensajes en consola
        $this->command->info('Usuarios y servicios creados correctamente.');
    }
}
