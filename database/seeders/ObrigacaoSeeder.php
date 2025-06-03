<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Obrigacao;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ObrigacaoSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = Empresa::all();

        if ($empresas->isEmpty()) {
            $this->command->warn('Nenhuma empresa encontrada. Execute o seeder de empresas primeiro.');
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $empresa = $empresas->random();

            $inicio = Carbon::now()->subDays(rand(10, 100));
            $vencimento = (clone $inicio)->addDays(rand(10, 30));

            Obrigacao::create([
                'empresa_id'       => $empresa->id,
                'tipo'             => 'Obrigação ' . strtoupper(Str::random(5)),
                'frequencia'       => collect(['mensal', 'trimestral', 'anual'])->random(),
                'data_inicio'      => $inicio->toDateString(),
                'data_vencimento'  => $vencimento->toDateString(),
                'data_conclusao'   => rand(0, 1) ? $vencimento->copy()->addDays(rand(1, 10)) : null,
                'status'           => collect(['pendente', 'em andamento', 'concluida'])->random(),
                'observacoes'      => fake()->sentence(),
            ]);
        }

        $this->command->info('10 obrigações foram criadas com sucesso.');
    }
}
