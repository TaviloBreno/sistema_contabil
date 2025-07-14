<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentoUpload;
use App\Models\User;
use App\Models\Empresa;

class DocumentoSeeder extends Seeder
{
    public function run(): void
    {
        if (!Empresa::exists() || !User::exists()) {
            $this->command->warn('É necessário ter usuários e empresas cadastradas antes de rodar o DocumentoSeeder.');
            return;
        }

        $empresa = Empresa::first();
        $user = User::first();

        DocumentoUpload::create([
            'empresa_id' => $empresa->id,
            'user_id' => $user->id,
            'nome_arquivo' => 'exemplo.pdf',
            'categoria' => 'contrato',
            'caminho' => 'documentos/exemplo.pdf',
            'tamanho' => 102400,
            'protocolo' => 'DOC-' . rand(1000, 9999),
        ]);

        $this->command->info('Documento de exemplo inserido com sucesso.');
    }
}
