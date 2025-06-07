<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class GenerateCrudFilesCommand extends Command
{
    protected $signature = 'generate:crud {name}';
    
    protected $description = 'Generate Controller, Repository, and Vue files for CRUD operations';

    public function handle()
    {
        $name = $this->argument('name');
        $folder = Str::plural(ucfirst(strtolower($name))); // First letter capitalized, rest lowercase, pluralized

        // Generate Controller
        $this->generateController($name);

        // Generate Repository
        $this->generateRepository($name);

        // Generate Vue Files
        $this->generateVueFiles($name, $folder);

        $this->info("CRUD files for {$name} generated successfully!");
    }

    private function generateController($name)
    {
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");
        $repositoryVar = strtolower($name) . 'Repository';
        $stub = <<<EOT
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\\{$name}Repository;

class {$name}Controller extends Controller
{
    public function __construct({$name}Repository \${$repositoryVar})
    {
        \$this->handleRepository = \${$repositoryVar};
    }
}
EOT;

        File::put($controllerPath, $stub);
        $this->info("Created {$name}Controller.php");
    }

    private function generateRepository($name)
    {
        $repositoryPath = app_path("Repositories/{$name}Repository.php");
        $repositoryDir = app_path("Repositories");

        if (!File::exists($repositoryDir)) {
            File::makeDirectory($repositoryDir, 0755, true);
        }

        $stub = <<<EOT
<?php

namespace App\Repositories;

use App\Models\\{$name};
use App\Repositories\BaseRepository;

class {$name}Repository extends BaseRepository
{
    public function __construct({$name} \$model)
    {
        \$this->handleModel = \$model;
    }
}
EOT;

        File::put($repositoryPath, $stub);
        $this->info("Created {$name}Repository.php");
    }

    private function generateVueFiles($name, $folder)
    {
        $viewPath = resource_path("js/pages/admin/{$folder}");
        if (!File::exists($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }

        // Index.vue
        $indexStub = <<<EOT
<template>
    <AppLayout>
     
    </AppLayout>
</template>

<script setup>

</script>

<style lang="css" scoped>

</style>
EOT;
        File::put("{$viewPath}/Index.vue", $indexStub);
        $this->info("Created Index.vue");

        // Create<Name>.vue
        $createStub = <<<EOT
<template>
    <AppLayout>
     
    </AppLayout>
</template>

<script setup>

</script>

<style lang="css" scoped>

</style>
EOT;
        File::put("{$viewPath}/Create{$name}.vue", $createStub);
        $this->info("Created Create{$name}.vue");

        // Edit<Name>.vue
        $editStub = <<<EOT
<template>
    <AppLayout>
     
    </AppLayout>
</template>

<script setup>

</script>

<style lang="css" scoped>

</style>
EOT;
        File::put("{$viewPath}/Edit{$name}.vue", $editStub);
        $this->info("Created Edit{$name}.vue");
    }
}