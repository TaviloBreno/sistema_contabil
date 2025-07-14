<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\ConfiguracoesSistemaSeeder;
use Database\Seeders\EmpresaSeeder;
use Database\Seeders\NotaFiscalSeeder;
use Database\Seeders\ObrigacaoSeeder;
use Database\Seeders\DocumentoSeeder;
use Database\Seeders\RoleSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ConfiguracoesSistemaSeeder::class,
            EmpresaSeeder::class,
            ObrigacaoSeeder::class,
            NotaFiscalSeeder::class,
            DocumentoSeeder::class,
        ]);
    }
}
