<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SetupDatabase extends Command
{
    protected $signature = 'db:setup';
    protected $description = 'Create stored procedures and execute schema setup';

    public function handle()
    {
        $path = database_path('stored_procedures');

        // 1. Load all SQL files (individual stored-procedures) and run them
        $allFiles = File::allFiles($path);

        // Filter out the master executor so we can create it last
        $procFiles = array_filter($allFiles, function ($file) {
            return $file->getFilename() !== '99_sp_setup_schema.sql';
        });

        // Sort by full path to respect folder structure
        usort($procFiles, fn($a, $b) => strcmp($a->getPathname(), $b->getPathname()));

        // Execute each SQL file (split on GO batch separators)
        foreach ($procFiles as $file) {
            $sql = File::get($file->getPathname());

            $batches = preg_split('/\bGO\b/i', $sql);

            foreach ($batches as $batch) {
                if (trim($batch)) {
                    DB::unprepared($batch);
                }
            }

            $this->info("Loaded: " . $file->getFilename());
        }

        // 2. Load the master setup file (creates sp_setup_schema) and create it
        $masterFile = $path . DIRECTORY_SEPARATOR . '99_sp_setup_schema.sql';

        if (File::exists($masterFile)) {
            $masterSql = File::get($masterFile);
            $masterBatches = preg_split('/\bGO\b/i', $masterSql);

            foreach ($masterBatches as $batch) {
                if (trim($batch)) {
                    DB::unprepared($batch);
                }
            }

            $this->info('Loaded: 99_sp_setup_schema.sql');

            // 3. Execute the master stored procedure to create all tables
            try {
                DB::statement('EXEC sp_setup_schema');
                $this->info('✔ All tables created via sp_setup_schema');
            } catch (\Throwable $e) {
                $this->error('Failed executing sp_setup_schema: ' . $e->getMessage());
                return 1;
            }
        } else {
            $this->error('Master setup file not found: ' . $masterFile);
            return 1;
        }
    }
}
