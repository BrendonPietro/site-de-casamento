<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PrepareDeploy extends Command
{
    protected $signature = 'deploy:prepare';
    protected $description = 'Prepare the application for deployment';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Iniciando a preparação para o deploy...');

        // Limpar cache
        $this->cleanCache();

        // Faz o build dos assets
        $this->runNpmBuild('--no-purge');

        // Gera o cache de todas as paginas
        $this->cachePages();

        // Refaz o build e aplica o purgecss
        $this->runNpmBuild();

        // Roda o optimize para gerar cache das rotas
        $this->optimize();

        $this->info('Preparação para o deploy concluída!');
    }

    protected function runNpmBuild($noPurge = false)
    {
        if ($noPurge) $command = 'build-no-purge';
        else $command = 'build';

        $this->info('Executando npm run build...');
        exec('npm run ' . $command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Erro ao executar npm run build: ' . implode("\n", $output));
            return;
        }

        $this->info('npm run build executado com sucesso!');
    }

    protected function cleanCache()
    {
        $this->info('Limpando o cache...');

        // Executar os comandos Artisan para limpar o cache
        Artisan::call('optimize:clear');

        $this->info('Cache limpo com sucesso!');
    }

    protected function cachePages()
    {
        Artisan::call('cache:pages');
    }

    protected function optimize()
    {
        Artisan::call('optimize');
    }
}
