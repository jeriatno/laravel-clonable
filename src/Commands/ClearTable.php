<?php

    namespace App\Console\Commands;

    use Illuminate\Console\Command;

    class ClearTable extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'clear:table {modelOrTableName}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Clear table or model with truncate or delete data';

        /**
         * Execute the console command.
         *
         * @throws \ReflectionException
         */
        public function handle(): void
        {
            $modelOrTableName = $this->argument('modelOrTableName');
            if ($this->areParametersMissing($modelOrTableName)) {
                return;
            }

            $mode = $this->choice('Select mode', ['truncate', 'delete'], 0);

            clearTable($modelOrTableName, $mode);

            $this->info("The table or model '$modelOrTableName' has been successfully {$mode}d.");
        }

        private function areParametersMissing($modelOrTableName): bool
        {
            if (is_null($modelOrTableName)) {
                $this->error("\nPlease provide the model or table name.");
                return true;
            }

            return false;
        }
    }
