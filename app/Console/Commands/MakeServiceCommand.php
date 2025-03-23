<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a service';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Define the folder and file paths
        $basePath = app_path("Services");
        $fileName = "{$name}.php";
        $filePath = "{$basePath}/{$fileName}";

        // Ensure the feature directory exists
        if (!File::isDirectory($basePath)) {
            File::makeDirectory($basePath, 0755, true);
            $this->info("Created directory for Services: {$basePath}");
        }
        // Check if the file already exists
        if (File::exists($filePath)) {
            $this->error("Service {$name} already exists!");
            return Command::FAILURE;
        }

        // Generate the migration-seeder template
        $template = $this->getServiceTemplate($name);

        // Write the file
        File::put($filePath, $template);

        $this->newLine();
        $this->line("<bg=blue> INFO </> Service [<options=bold>{$filePath}</>] created succesfully");
        $this->newLine();

        return Command::SUCCESS;
    }

    /**
     * Get the stub/template for the migration-seeder.
     *
     * @param string $className
     * @return string
     */
    protected function getServiceTemplate(string $className): string
    {
        $className = Str::studly($className);

        return <<<PHP
        <?php

        namespace App\Services;
        use Illuminate\Support\Facades\Auth;

        class {$className}{

        }
        PHP;
    }
}


