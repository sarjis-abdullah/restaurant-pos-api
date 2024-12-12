<?php

namespace App\Console\Commands;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\BaseInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository
        {model : The name of the model}
        {--service : Generate a service class}
        {--interface : Generate an interface for the repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository class and optionally related components (service, interface) for a given model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->argument('model');
        $repositoryName = $model . 'Repository';
        $repositoryNamespace = 'App\\Repositories';

        // Repository directory and file path
        $repositoryDirectory = app_path('Repositories');
        $repositoryFilePath = $repositoryDirectory . '/' . $repositoryName . '.php';

        $interfaceName = $model . 'Interface';
        $interfaceNamespace = $repositoryNamespace . '\\Contracts';
        $interfaceDirectory = $repositoryDirectory . '/Contracts';
        $interfaceFilePath = $interfaceDirectory . '/' . $interfaceName . '.php';

        // Generate repository class
        $this->generateFile($repositoryDirectory, $repositoryFilePath, $this->getRepositoryStub($model, $repositoryName, $repositoryNamespace, $interfaceName));
        $this->generateFile($interfaceDirectory, $interfaceFilePath, $this->getInterfaceStub($interfaceName, $interfaceNamespace));

        // Generate interface if --interface option is provided


        // Generate service class if --service option is provided
        if ($this->option('service')) {
            $serviceName = $model . 'Service';
            $serviceNamespace = 'App\\Services';
            $serviceDirectory = app_path('Services');
            $serviceFilePath = $serviceDirectory . '/' . $serviceName . '.php';

            $this->generateFile($serviceDirectory, $serviceFilePath, $this->getServiceStub($model, $serviceName, $serviceNamespace, $repositoryNamespace, $repositoryName));
        }

        $this->info("Repository class {$repositoryName} and related components created successfully!");
    }

    /**
     * Generate a file in the specified directory with the given content.
     */
    protected function generateFile($directory, $filePath, $content)
    {
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($filePath)) {
            $this->error("File {$filePath} already exists!");
            return;
        }

        File::put($filePath, $content);
    }

    /**
     * Get the stub content for the repository class.
     */
    protected function getRepositoryStub($model, $repositoryName, $namespace, $interfaceName)
    {
        return <<<EOD
<?php

namespace {$namespace};
use App\Repositories\Contracts\{$interfaceName};

use App\Models\\{$model};

class {$repositoryName} extends BaseRepository implements {$interfaceName}
{

}
EOD;
    }

    /**
     * Get the stub content for the interface.
     */
    protected function getInterfaceStub($interfaceName, $namespace)
    {
        return <<<EOD
<?php

namespace {$namespace};

interface {$interfaceName} extends BaseInterface
{

}
EOD;
    }


}
