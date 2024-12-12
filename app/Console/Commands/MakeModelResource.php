<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;
class MakeModelResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model-resource {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate requests and repository for a given model';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $model = $this->argument('model');

        // Run make:model-requests command
        $this->info("Generating requests for model: {$model}");
        $process1 = new Process(['php', 'artisan', 'make:model-requests', $model]);
        $process1->run();

        if (!$process1->isSuccessful()) {
            $this->error("Failed to generate requests: " . $process1->getErrorOutput());
            return;
        }
        $this->info($process1->getOutput());

        // Run make:repository command
        $this->info("Generating repository for model: {$model}");
        $process2 = new Process(['php', 'artisan', 'make:repository', $model]);
        $process2->run();

        if (!$process2->isSuccessful()) {
            $this->error("Failed to generate repository: " . $process2->getErrorOutput());
            return;
        }
        $this->info($process2->getOutput());
        $this->generateResource($model);
        $this->generateResourceCollection($model);
        $this->info("Requests and repository for {$model} created successfully!");
    }

    /**
     * Generate a resource for the model.
     */
    protected function generateResource(string $model): void
    {
        $path = app_path("Http/Resources/{$model}Resource.php");

        if (File::exists($path)) {
            $this->warn("Resource for {$model} already exists.");
            return;
        }

        $content = <<<EOT
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {$model}Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request \$request): array
    {
        return [
            'id' => \$this->id,
            'created_at' => \$this->created_at,
            'updated_at' => \$this->updated_at,
        ];
    }
}
EOT;

        File::put($path, $content);
        $this->info("Resource for {$model} created at: {$path}");
    }

    /**
     * Generate a resource collection for the model.
     */
    protected function generateResourceCollection(string $model): void
    {
        $path = app_path("Http/Resources/{$model}ResourceCollection.php");

        if (File::exists($path)) {
            $this->warn("Resource collection for {$model} already exists.");
            return;
        }

        $content = <<<EOT
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class {$model}ResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request \$request): array
    {
        return [

        ];
    }
}
EOT;

        File::put($path, $content);
        $this->info("Resource collection for {$model} created at: {$path}");
    }
}

