<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existem usuários antes de criar
        if (User::count() > 0) {
            return; // Já existem usuários, não criar novos
        }

        User::create([
            'name' => 'Admin Master',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Gerente Silva',
            'email' => 'gerente@empresa.com',
            'password' => Hash::make('12345678'),
            'role' => 'gerente',
        ]);

        User::create([
            'name' => 'Operador João',
            'email' => 'joao@empresa.com',
            'password' => Hash::make('12345678'),
            'role' => 'operador',
        ]);

        User::create([
            'name' => 'Operadora Ana',
            'email' => 'ana@empresa.com',
            'password' => Hash::make('12345678'),
            'role' => 'operador',
        ]);

        User::create([
            'name' => 'Operador Pedro',
            'email' => 'pedro@empresa.com',
            'password' => Hash::make('12345678'),
            'role' => 'operador',
        ]);
    }
}
