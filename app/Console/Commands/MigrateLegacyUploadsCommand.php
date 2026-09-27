<?php

namespace App\Console\Commands;

use App\Support\ManagedUpload;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MigrateLegacyUploadsCommand extends Command
{
    protected $signature = 'uploads:migrate-legacy
                            {--execute : Copy files to the configured managed upload disk}
                            {--overwrite : Replace objects that already exist on the target disk}';

    protected $description = 'Copy legacy public/uploads files to the configured durable upload disk';

    public function handle(): int
    {
        $source = Storage::disk('legacy_uploads');
        $target = Storage::disk(ManagedUpload::diskName());
        $files = collect($source->allFiles('uploads'))
            ->filter(fn (string $path): bool => basename($path) !== '.htaccess')
            ->values();

        $pending = $files->filter(
            fn (string $path): bool => $this->option('overwrite') || ! $target->exists($path)
        )->values();

        $this->components->info(sprintf(
            'Legacy files: %d; copy candidates: %d; mode: %s.',
            $files->count(),
            $pending->count(),
            $this->option('execute') ? 'execute' : 'dry-run',
        ));

        if (! $this->option('execute')) {
            $this->components->info('Dry run only; source and target files were not changed.');

            return self::SUCCESS;
        }

        $copied = 0;

        foreach ($pending as $path) {
            $stream = $source->readStream($path);

            if ($stream === null || $stream === false) {
                $this->error("Could not read legacy upload: {$path}");

                return self::FAILURE;
            }

            try {
                if (! $target->put($path, $stream)) {
                    $this->error("Could not write managed upload: {$path}");

                    return self::FAILURE;
                }
            } catch (Throwable $exception) {
                $this->error("Managed upload copy failed: {$path}");

                return self::FAILURE;
            } finally {
                fclose($stream);
            }

            if (! $target->exists($path) || $target->size($path) !== $source->size($path)) {
                $this->error("Managed upload verification failed: {$path}");

                return self::FAILURE;
            }

            $copied++;
        }

        $this->components->info("Copied and size-verified {$copied} file(s). Legacy files were retained.");

        return self::SUCCESS;
    }
}
