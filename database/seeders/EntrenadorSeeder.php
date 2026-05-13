<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrenadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Entrenador Juan',
            'email' => 'entrenador@masqueperros.com',
            'password' => bcrypt('password123'),
            'rol' => 'entrenador',
        ]);
    }
}
