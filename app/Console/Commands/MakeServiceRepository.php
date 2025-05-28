<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeServiceRepository extends Command
{
    protected $signature = 'make:service-repository {name : The name of the service/repository (e.g., ModuleName/Name)} {--model : Create a model}';
    protected $description = 'Create a new service, repository, and their interfaces with custom directory structure';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        
        // Tách ModuleName và ClassName từ input
        [$module, $className] = $this->parseName($name);

        // Tạo Model (nếu có option --model)
        if ($this->option('model')) {
            $this->createModel($className);
        }

        // Tạo Repository Interface và Repository
        $this->createRepositoryInterface($module, $className);
        $this->createRepository($module, $className);

        // Tạo Service Interface và Service
        $this->createServiceInterface($module, $className);
        $this->createService($module, $className);

        $this->info("Service, Repository, and Interfaces for [{$name}] created successfully!");
    }

    protected function parseName($name)
    {
        $parts = explode('/', trim($name, '/'));
        if (count($parts) > 1) {
            $className = array_pop($parts);
            $module = implode('/', $parts);
        } else {
            $className = $parts[0];
            $module = '';
        }

        return [$module, $className];
    }

    protected function createModel($className)
    {
        $this->call('make:model', ['name' => $className]);
        $this->info("Model [{$className}] created successfully!");
    }

    protected function createRepository($module, $className)
    {
        $repositoryPath = app_path("Repositories/{$module}/{$className}Repository.php");
        $stub = $this->getRepositoryStub();
        $stub = str_replace('{{class}}', $className, $stub);
        $stub = str_replace('{{model}}', Str::studly($className), $stub);
        $stub = str_replace('{{namespace}}', 'App\\Repositories' . ($module ? '\\' . str_replace('/', '\\', $module) : ''), $stub);

        $this->ensureDirectoryExists(dirname($repositoryPath));
        $this->files->put($repositoryPath, $stub);

        $this->info("Repository [{$module}/{$className}Repository] created successfully!");
    }

    protected function createService($module, $className)
    {
        $servicePath = app_path("Services/{$module}/{$className}Service.php");
        $stub = $this->getServiceStub();
        $stub = str_replace('{{class}}', $className, $stub);
        $stub = str_replace('{{repository}}', $className . 'Repository', $stub);
        $stub = str_replace('{{repositoryInterface}}', $className . 'RepositoryInterface', $stub);
        $stub = str_replace('{{namespace}}', 'App\\Services' . ($module ? '\\' . str_replace('/', '\\', $module) : ''), $stub);
        $stub = str_replace('{{repositoryNamespace}}', 'App\\Repositories' . ($module ? '\\' . str_replace('/', '\\', $module) : ''), $stub);

        $this->ensureDirectoryExists(dirname($servicePath));
        $this->files->put($servicePath, $stub);

        $this->info("Service [{$module}/{$className}Service] created successfully!");
    }

    protected function createRepositoryInterface($module, $className)
    {
        $interfacePath = app_path("Repositories/{$module}/{$className}RepositoryInterface.php");
        $stub = $this->getRepositoryInterfaceStub();
        $stub = str_replace('{{class}}', $className, $stub);
        $stub = str_replace('{{namespace}}', 'App\\Repositories' . ($module ? '\\' . str_replace('/', '\\', $module) : ''), $stub);

        $this->ensureDirectoryExists(dirname($interfacePath));
        $this->files->put($interfacePath, $stub);

        $this->info("Repository Interface [{$module}/{$className}RepositoryInterface] created successfully!");
    }

    protected function createServiceInterface($module, $className)
    {
        $interfacePath = app_path("Services/{$module}/{$className}ServiceInterface.php");
        $stub = $this->getServiceInterfaceStub();
        $stub = str_replace('{{class}}', $className, $stub);
        $stub = str_replace('{{namespace}}', 'App\\Services' . ($module ? '\\' . str_replace('/', '\\', $module) : ''), $stub);

        $this->ensureDirectoryExists(dirname($interfacePath));
        $this->files->put($interfacePath, $stub);

        $this->info("Service Interface [{$module}/{$className}ServiceInterface] created successfully!");
    }

    protected function getRepositoryStub()
    {
        return <<<EOT
<?php

namespace {{namespace}};

use App\Models\{{model}};
use {{namespace}}\{{class}}RepositoryInterface;

class {{class}}Repository extends BaseRepository implements {{class}}RepositoryInterface
{
    protected \$model;

    public function __construct({{model}} \$model)
    {
        \$this->model = \$model;
    }

    public function find(\$id)
    {
        return \$this->model->find(\$id);
    }
}
EOT;
    }

    protected function getServiceStub()
    {
        return <<<EOT
<?php

namespace {{namespace}};

use {{repositoryNamespace}}\{{repositoryInterface}};
use {{namespace}}\{{class}}ServiceInterface;

class {{class}}Service extends BaseService implements {{class}}ServiceInterface
{
    protected \$repository;

    public function __construct({{repositoryInterface}} \$repository)
    {
        \$this->repository = \$repository;
    }

    public function getById(\$id)
    {
        return \$this->repository->find(\$id);
    }
}
EOT;
    }

    protected function getRepositoryInterfaceStub()
    {
        return <<<EOT
<?php

namespace {{namespace}};

interface {{class}}RepositoryInterface extends BaseRepositoryInterface
{
    public function find(\$id);
}
EOT;
    }

    protected function getServiceInterfaceStub()
    {
        return <<<EOT
<?php

namespace {{namespace}};

interface {{class}}ServiceInterface exntends BaseServiceInterface
{
    public function getById(\$id);
}
EOT;
    }

    protected function ensureDirectoryExists($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true);
        }
    }
}