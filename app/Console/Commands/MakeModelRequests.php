<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModelRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model-requests {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate IndexRequest, StoreRequest, and UpdateRequest for a given model';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $model = $this->argument('model');
        $basePath = app_path("Http/Requests/{$model}");

        // Ensure the directory exists
        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0755, true);
        }

        // List of requests to generate
        $requests = ['IndexRequest', 'StoreRequest', 'UpdateRequest'];

        foreach ($requests as $request) {
            $this->createRequest($basePath, $model, $request);
        }

        $this->info("Requests for {$model} created successfully!");
    }

    /**
     * Create a specific request file.
     */
    protected function createRequest($basePath, $model, $request): void
    {
        $filePath = "{$basePath}/{$request}.php";

        if (File::exists($filePath)) {
            $this->warn("{$request} already exists for model {$model}.");
            return;
        }

        // Request stub content
        $content = <<<EOT
<?php

namespace App\Http\Requests\\{$model};

use App\Http\Requests\Request;

class {$request} extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Add validation rules here
        ];
    }
}
EOT;

        // Create the file
        File::put($filePath, $content);
    }
}

