<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeMVC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * name : The name of the model
     * --param= : The options for the command, ex: "-msf"
     * --route= : The path to the route file, ex: "web/filename"
     */
    protected $signature = 'make:mvc {name} {--param=} {--route=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a full set of CRUD components';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $param = $this->option('param');
        $route = $this->option('route');

        $this->makeModel($name, $param);
        $this->makeController($name);
        $this->makeRepository($name);
    }

    /*
     * Make model, migration, seeder & factory
     */
    protected function makeModel($name, $param)
    {
        Artisan::call(sprintf('make:model %s %s', $name, $param));

        $infoMessages = [];
        $infoMessages[] = 'Model created successfully.';

        if (Str::contains($param, 's')) {
            $infoMessages[] = 'Seeder created successfully.';
        }

        if (Str::contains($param, 'f')) {
            $infoMessages[] = 'Factory created successfully.';
        }

        if (Str::contains($param, 'm')) {
            $infoMessages[] = 'Migration created successfully.';
        }

        $this->info(implode("\n", $infoMessages));
    }

    /*
     * Make controller & request
     */
    protected function makeController($name)
    {
        Artisan::call(sprintf('make:controller %sController -r', $name));
        Artisan::call(sprintf('make:request %sRequest', $name));

        $this->info('Controller created successfully.');
        $this->info('Request created successfully.');
    }

    /*
     * Make repository & contract
     */
    protected function makeRepository($name)
    {
        Artisan::call(sprintf('make:repository %s --base', $name));

        $this->info('Repository and Contract created successfully.');
    }
}
