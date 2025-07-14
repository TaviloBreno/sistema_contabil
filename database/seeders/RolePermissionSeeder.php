<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Criar permissões
        $permissions = [
            // Empresas
            'empresas.view',
            'empresas.create',
            'empresas.edit',
            'empresas.delete',

            // Usuários
            'usuarios.view',
            'usuarios.create',
            'usuarios.edit',
            'usuarios.delete',

            // Notas Fiscais
            'notas_fiscais.view',
            'notas_fiscais.create',
            'notas_fiscais.edit',
            'notas_fiscais.delete',
            'notas_fiscais.autorizar',
            'notas_fiscais.cancelar',

            // Documentos
            'documentos.view',
            'documentos.create',
            'documentos.edit',
            'documentos.delete',
            'documentos.download',

            // Obrigações
            'obrigacoes.view',
            'obrigacoes.create',
            'obrigacoes.edit',
            'obrigacoes.delete',

            // Relatórios
            'relatorios.view',
            'relatorios.export',

            // Configurações
            'configuracoes.view',
            'configuracoes.edit',

            // Dashboard
            'dashboard.view',
            'dashboard.stats',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $contadorRole = Role::firstOrCreate(['name' => 'contador']);
        $empresaRole = Role::firstOrCreate(['name' => 'empresa']);
        $funcionarioRole = Role::firstOrCreate(['name' => 'funcionario']);

        // Permissões para Admin (todas)
        $adminRole->givePermissionTo(Permission::all());

        // Permissões para Contador
        $contadorRole->givePermissionTo([
            'empresas.view',
            'empresas.create',
            'empresas.edit',
            'notas_fiscais.view',
            'notas_fiscais.create',
            'notas_fiscais.edit',
            'notas_fiscais.autorizar',
            'notas_fiscais.cancelar',
            'documentos.view',
            'documentos.create',
            'documentos.edit',
            'documentos.download',
            'obrigacoes.view',
            'obrigacoes.create',
            'obrigacoes.edit',
            'relatorios.view',
            'relatorios.export',
            'dashboard.view',
            'dashboard.stats',
        ]);

        // Permissões para Empresa Cliente
        $empresaRole->givePermissionTo([
            'empresas.view',
            'empresas.edit', // Apenas a própria empresa
            'notas_fiscais.view',
            'notas_fiscais.create',
            'documentos.view',
            'documentos.create',
            'documentos.download',
            'obrigacoes.view',
            'relatorios.view',
            'dashboard.view',
        ]);

        // Permissões para Funcionário
        $funcionarioRole->givePermissionTo([
            'empresas.view',
            'notas_fiscais.view',
            'documentos.view',
            'documentos.download',
            'obrigacoes.view',
            'dashboard.view',
        ]);
    }
}
