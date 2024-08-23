<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as CommandAlias;

class MakeEnum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * name : The name of the enum
     * --values=* : The enum values
     */
    protected $signature = 'make:enum {name} {--values=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new enum class';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $values = $this->option('values');

        $enumPath = app_path('Enums/' . $name . '.php');

        if (file_exists($enumPath)) {
            $this->error('Enum already exists!');
            return CommandAlias::FAILURE;
        }

        if (is_array($values) && count($values) === 1) {
            $values = explode(',', $values[0]);
        }

        $enumContent = $this->generateEnumContent($name, $values);

        $filesystem = new Filesystem();
        $filesystem->ensureDirectoryExists(app_path('Enums'));
        $filesystem->put($enumPath, $enumContent);

        $this->info('Enum created successfully.');
        return CommandAlias::SUCCESS;
    }

    protected function generateEnumContent(string $name, array $values): string
    {
        $stub = $this->files()->get(resource_path('stubs/enum.stub'));

        $cases = [];
        foreach ($values as $value) {
            $cases[] = "const " . strtoupper($value) . " = '';";
        }

        // Join cases with newlines
        $casesString = implode(PHP_EOL, $cases);


        // Replace placeholders in the stub with actual values
        return str_replace(
            ['{{name}}', '{{cases}}'],
            [$name, $casesString],
            $stub
        );
    }

    protected function files(): Filesystem
    {
        return new Filesystem();
    }
}
