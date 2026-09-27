<?php

namespace App\Console\Commands;

use App\Support\ManagedUpload;
use App\Support\ManagedUploadReferences;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AuditOrphanUploadsCommand extends Command
{
    protected $signature = 'uploads:audit-orphans
                            {--quarantine : Move unreferenced files into dated quarantine}
                            {--purge : Delete quarantined files older than the retention period}
                            {--retention-days=30 : Minimum quarantine age before purge}';

    protected $description = 'Report managed upload orphans and optionally quarantine or purge them safely';

    public function handle(ManagedUploadReferences $references): int
    {
        $disk = Storage::disk(ManagedUpload::diskName());
        $retentionDays = max(1, (int) $this->option('retention-days'));

        try {
            $referenced = array_fill_keys($references->all(), true);
            $managedFiles = collect($disk->allFiles('uploads'))
                ->filter(fn (string $path): bool => ! str_starts_with($path, 'uploads/_quarantine/'))
                ->filter(fn (string $path): bool => basename($path) !== '.htaccess')
                ->values();
        } catch (Throwable $exception) {
            $this->error('Upload inventory failed; no files were changed.');

            return self::FAILURE;
        }

        $orphans = $managedFiles
            ->reject(fn (string $path): bool => isset($referenced[$path]))
            ->values();

        $this->components->info(sprintf(
            'Managed files: %d; referenced: %d; orphan candidates: %d.',
            $managedFiles->count(),
            count($referenced),
            $orphans->count(),
        ));

        if ($orphans->isNotEmpty()) {
            $this->table(['Orphan candidate'], $orphans->map(fn (string $path): array => [$path])->all());
        }

        if ($this->option('quarantine')) {
            $date = now()->format('Ymd');

            foreach ($orphans as $path) {
                if (isset($referenced[$path])) {
                    continue;
                }

                $destination = 'uploads/_quarantine/'.$date.'/'.substr($path, strlen('uploads/'));
                $disk->move($path, $destination);
            }

            $this->components->info(sprintf('Quarantined %d file(s).', $orphans->count()));
        }

        if ($this->option('purge')) {
            $cutoff = now()->subDays($retentionDays)->timestamp;
            $purged = 0;

            foreach ($disk->allFiles('uploads/_quarantine') as $path) {
                if (isset($referenced[$path])) {
                    continue;
                }

                if ($disk->lastModified($path) <= $cutoff) {
                    $disk->delete($path);
                    $purged++;
                }
            }

            $this->components->info("Purged {$purged} quarantined file(s) older than {$retentionDays} day(s).");
        }

        if (! $this->option('quarantine') && ! $this->option('purge')) {
            $this->components->info('Dry run only; no files were changed.');
        }

        return self::SUCCESS;
    }
}
