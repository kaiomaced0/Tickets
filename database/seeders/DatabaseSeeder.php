<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuários comuns
        $users = [
            User::factory()->create([
                'name' => 'Carlos Silva',
                'email' => 'carlos@example.com',
                'password' => Hash::make('password'),
                'api_token' => 'token-carlos',
                'role' => 'USER',
                'active' => true,
            ]),
            User::factory()->create([
                'name' => 'Caio Fernandes',
                'email' => 'caio@example.com',
                'password' => Hash::make('password'),
                'api_token' => 'token-caio',
                'role' => 'USER',
                'active' => true,
            ]),
            User::factory()->create([
                'name' => 'Paulo Costa',
                'email' => 'paulo@example.com',
                'password' => Hash::make('password'),
                'api_token' => 'token-paulo',
                'role' => 'USER',
                'active' => true,
            ]),
            User::factory()->create([
                'name' => 'Maria Oliveira',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'api_token' => 'token-maria',
                'role' => 'USER',
                'active' => true,
            ]),
            User::factory()->create([
                'name' => 'Roberto Lima',
                'email' => 'roberto@example.com',
                'password' => Hash::make('password'),
                'api_token' => 'token-roberto',
                'role' => 'USER',
                'active' => true,
            ]),
            User::factory()->create([
                'name' => 'Juliana Alves',
                'email' => 'juliana@example.com',
                'password' => Hash::make('password'),
                'api_token' => 'token-juliana',
                'role' => 'USER',
                'active' => true,
            ]),
        ];

        // Criar admins
        $techlead = User::factory()->admin()->create([
            'name' => 'Matheus Mariano',
            'email' => 'matheus@example.com',
            'password' => Hash::make('password'),
            'api_token' => 'token-matheus',
            'role' => 'ADMIN',
            'active' => true,
        ]);

        $admin = User::factory()->admin()->create([
            'name' => 'Any Sayuri',
            'email' => 'anysayuri@example.com',
            'password' => Hash::make('password'),
            'api_token' => 'token-any',
            'role' => 'ADMIN',
            'active' => true,
        ]);

        $admins = [$techlead, $admin];
        $allUsers = array_merge($users, $admins);

        // Templates de tickets de desenvolvimento
        $ticketTemplates = [
            ['titulo' => 'Ajustar validação de formulário no sistema #001', 'descricao' => 'Necessário revisar e corrigir validação de campos obrigatórios no formulário de cadastro.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Implementar autenticação JWT no projeto #012', 'descricao' => 'Migrar autenticação atual para JWT com refresh token.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Corrigir bug no relatório de vendas #045', 'descricao' => 'Relatório apresenta valores incorretos quando filtrado por período.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Desenvolver API de integração para sistema #056', 'descricao' => 'Criar endpoints REST para integração com sistema externo de pagamentos.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Otimizar consultas SQL no dashboard #003', 'descricao' => 'Dashboard está lento devido a queries mal otimizadas. Necessário refatorar.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Implementar cache Redis no sistema #008', 'descricao' => 'Adicionar camada de cache para melhorar performance das consultas frequentes.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Criar documentação técnica do projeto #021', 'descricao' => 'Documentar arquitetura, endpoints e fluxos principais do sistema.', 'prioridade' => 'BAIXA'],
            ['titulo' => 'Revisar segurança do módulo de autenticação #034', 'descricao' => 'Audit de segurança identificou possíveis vulnerabilidades.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Desenvolver módulo de notificações push #019', 'descricao' => 'Implementar sistema de notificações em tempo real usando websockets.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Ajustar layout responsivo da página inicial #002', 'descricao' => 'Página apresenta problemas de layout em telas móveis.', 'prioridade' => 'BAIXA'],
            ['titulo' => 'Migrar banco de dados para PostgreSQL #067', 'descricao' => 'Planejar e executar migração do MySQL para PostgreSQL.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Implementar sistema de logs centralizado #089', 'descricao' => 'Configurar ELK stack para centralização de logs.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Corrigir vazamento de memória no worker #041', 'descricao' => 'Worker de processamento de filas está consumindo muita memória.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Desenvolver painel administrativo #078', 'descricao' => 'Criar interface completa para gerenciamento de usuários e permissões.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Atualizar dependências do projeto #015', 'descricao' => 'Atualizar pacotes desatualizados e resolver breaking changes.', 'prioridade' => 'BAIXA'],
            ['titulo' => 'Implementar testes unitários no módulo de pagamentos #056', 'descricao' => 'Adicionar cobertura de testes para garantir qualidade do código.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Otimizar build do frontend #024', 'descricao' => 'Reduzir tamanho do bundle e melhorar tempo de carregamento.', 'prioridade' => 'BAIXA'],
            ['titulo' => 'Configurar CI/CD no projeto #032', 'descricao' => 'Implementar pipeline de deploy automatizado com GitHub Actions.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Revisar arquitetura do microserviço #091', 'descricao' => 'Avaliar e propor melhorias na arquitetura atual.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Desenvolver sistema de backup automático #005', 'descricao' => 'Implementar rotina de backup diário com retenção de 30 dias.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Corrigir problema de encoding UTF-8 #018', 'descricao' => 'Caracteres especiais não estão sendo salvos corretamente.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Implementar feature de exportação Excel #027', 'descricao' => 'Permitir exportação de relatórios em formato Excel.', 'prioridade' => 'BAIXA'],
            ['titulo' => 'Ajustar performance do upload de arquivos #049', 'descricao' => 'Upload de arquivos grandes está falhando ou muito lento.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Desenvolver API GraphQL para sistema #083', 'descricao' => 'Criar camada GraphQL para substituir APIs REST legadas.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Corrigir problema de timezone #011', 'descricao' => 'Datas estão sendo salvas em timezone incorreto.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Implementar dark mode na aplicação #036', 'descricao' => 'Adicionar suporte a tema escuro com toggle.', 'prioridade' => 'BAIXA'],
            ['titulo' => 'Revisar permissões de acesso #064', 'descricao' => 'Revisar e ajustar controle de permissões por role.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Desenvolver módulo de chat interno #072', 'descricao' => 'Criar sistema de mensagens entre usuários do sistema.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Otimizar imagens do CDN #029', 'descricao' => 'Implementar compressão e lazy loading de imagens.', 'prioridade' => 'BAIXA'],
            ['titulo' => 'Ajustar fluxo de recuperação de senha #007', 'descricao' => 'Usuários relatam não receber email de recuperação.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Implementar auditoria de ações #095', 'descricao' => 'Registrar todas as ações críticas realizadas no sistema.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Desenvolver dashboard de métricas #054', 'descricao' => 'Criar visualizações de KPIs e métricas de negócio.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Corrigir conflito de versão no pacote #016', 'descricao' => 'Dependências conflitantes impedindo instalação.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Implementar paginação no endpoint de listagem #038', 'descricao' => 'Endpoint retorna muitos registros sem paginação.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Ajustar validação de CPF/CNPJ #022', 'descricao' => 'Validação aceita documentos inválidos.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Desenvolver novo sistema de e-commerce #100', 'descricao' => 'Projeto completo de plataforma de e-commerce do zero.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Revisar contratos de APIs de terceiros #047', 'descricao' => 'Verificar compliance com termos de uso das APIs.', 'prioridade' => 'BAIXA'],
            ['titulo' => 'Implementar rate limiting #060', 'descricao' => 'Adicionar controle de taxa de requisições para prevenir abuso.', 'prioridade' => 'MEDIA'],
            ['titulo' => 'Corrigir problema de sessão expirando #013', 'descricao' => 'Usuários sendo deslogados inesperadamente.', 'prioridade' => 'ALTA'],
            ['titulo' => 'Desenvolver aplicativo mobile #088', 'descricao' => 'Criar versão mobile nativa do sistema principal.', 'prioridade' => 'ALTA'],
        ];

        $statuses = ['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO', 'CANCELADO'];
        $startDate = strtotime('2015-09-01');
        $endDate = strtotime('2026-02-25');

        // Criar tickets para usuários comuns (~15 cada)
        foreach ($users as $user) {
            $ticketCount = rand(13, 17); // ~15 tickets
            $usedTemplates = [];

            for ($i = 0; $i < $ticketCount; $i++) {
                // Selecionar template não usado
                do {
                    $templateIndex = array_rand($ticketTemplates);
                } while (in_array($templateIndex, $usedTemplates) && count($usedTemplates) < count($ticketTemplates));

                $usedTemplates[] = $templateIndex;
                $template = $ticketTemplates[$templateIndex];

                $status = $statuses[array_rand($statuses)];
                $randomDate = date('Y-m-d H:i:s', rand($startDate, $endDate));

                $ticket = Ticket::create([
                    'solicitante_id' => $techlead->id, // TechLead cria a maioria
                    'responsavel_id' => $user->id,
                    'titulo' => $template['titulo'],
                    'descricao' => $template['descricao'],
                    'status' => $status,
                    'prioridade' => $template['prioridade'],
                    'resolved_at' => $status === 'RESOLVIDO' ? now() : null,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }

        // Criar tickets para admins (~4 cada)
        foreach ($admins as $adminUser) {
            $ticketCount = rand(3, 5); // ~4 tickets
            $usedTemplates = [];

            for ($i = 0; $i < $ticketCount; $i++) {
                do {
                    $templateIndex = array_rand($ticketTemplates);
                } while (in_array($templateIndex, $usedTemplates) && count($usedTemplates) < count($ticketTemplates));

                $usedTemplates[] = $templateIndex;
                $template = $ticketTemplates[$templateIndex];

                $status = $statuses[array_rand($statuses)];
                $randomDate = date('Y-m-d H:i:s', rand($startDate, $endDate));

                // Admins podem criar para outros ou para eles mesmos
                $solicitante = $adminUser->id === $techlead->id ? $techlead->id : $allUsers[array_rand($allUsers)]->id;

                $ticket = Ticket::create([
                    'solicitante_id' => $solicitante,
                    'responsavel_id' => $adminUser->id,
                    'titulo' => $template['titulo'],
                    'descricao' => $template['descricao'],
                    'status' => $status,
                    'prioridade' => $template['prioridade'],
                    'resolved_at' => $status === 'RESOLVIDO' ? now() : null,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }
    }
}
