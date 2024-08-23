<?php
    
    namespace App\Console\Commands;
    
    use Illuminate\Console\Command;
    
    class MakeRepository extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'make:repository {name}';
        
        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Create a new Repository class';
        
        /**
         * Create a new command instance.
         *
         * @return void
         */
        public function __construct()
        {
            parent::__construct();
        }
        
        protected function getStub($type): string
        {
            return file_get_contents(base_path(sprintf('stubs/%s.stub', $type)));
        }
        
        protected function generate(string $name, string $type, string $typeFolder)
        {
            $typeCap = ucfirst($type);
            $explode = explode('/', $name);
            $directory = implode('\\', array_slice($explode, 0, -1));
            $className = end($explode);
            
            $namespace = sprintf('%s%s', $typeFolder, $directory ? sprintf('\%s', $directory) : '');
            $namespaceContract = sprintf('%s\%s', 'Contracts', $directory ? sprintf('%s\%s', $directory, $className) : $className);
            $namespaceModel = sprintf('%s\%s', 'Models', $directory ? sprintf('%s\%s', $directory, $className) : $className);
            
            $fullDirectory = app_path($namespace);
            $fileName = sprintf('%s%s.php', $className, $typeCap);
            $fullPath = sprintf('%s/%s', $fullDirectory, $fileName);
            
            $template = str_replace(
                ['{{name}}', '{{namespace}}', '{{namespaceContract}}', '{{namespaceModel}}'],
                [$className, $namespace, $namespaceContract, $namespaceModel],
                $this->getStub($type)
            );
            
            $fullDirectory = str_replace('\\', '/', $fullDirectory);
            $fullPath = str_replace('\\', '/', $fullPath);
            
            if (!file_exists($fullDirectory)) {
                mkdir($fullDirectory, 0777, true);
            }
            
            if (!file_exists($fullPath)) {
                file_put_contents($fullPath, $template);
                $this->info(sprintf('%s created successfully.', $typeCap));
            }
            else {
                $this->error(sprintf('The given %s already exists', $typeCap));
            }
        }
        
        public function handle()
        {
            $name = $this->argument('name');
            
            $this->generate($name, 'contract', 'Contracts');
            $this->generate($name, 'repository', 'Repositories');
        }
    }
