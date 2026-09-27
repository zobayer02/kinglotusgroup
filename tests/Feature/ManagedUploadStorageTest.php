<?php

namespace Tests\Feature;

use App\Models\SiteNotice;
use App\Support\ManagedUpload;
use App\Support\PublicWebpUploader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class ManagedUploadStorageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(ManagedUpload::diskName());
    }

    public function test_replacement_keeps_old_file_until_database_save_is_committed(): void
    {
        $oldPath = 'uploads/test/old.webp';
        Storage::disk(ManagedUpload::diskName())->put($oldPath, 'old-image');

        $uploader = new PublicWebpUploader;
        $newPath = $uploader->store(
            UploadedFile::fake()->image('replacement.jpg', 120, 80),
            'uploads/test',
            $oldPath,
        );

        Storage::disk(ManagedUpload::diskName())->assertExists([$oldPath, $newPath]);

        $uploader->commit();

        Storage::disk(ManagedUpload::diskName())->assertMissing($oldPath);
        Storage::disk(ManagedUpload::diskName())->assertExists($newPath);
    }

    public function test_rollback_removes_new_file_and_preserves_existing_file(): void
    {
        $oldPath = 'uploads/test/existing.webp';
        Storage::disk(ManagedUpload::diskName())->put($oldPath, 'existing-image');

        $uploader = new PublicWebpUploader;
        $newPath = $uploader->store(
            UploadedFile::fake()->image('replacement.png', 80, 80),
            'uploads/test',
            $oldPath,
        );

        $uploader->rollback();

        Storage::disk(ManagedUpload::diskName())->assertExists($oldPath);
        Storage::disk(ManagedUpload::diskName())->assertMissing($newPath);
    }

    public function test_removed_nested_paths_are_deleted_only_after_commit(): void
    {
        $removed = 'uploads/gallery/removed.webp';
        $retained = 'uploads/gallery/retained.webp';
        Storage::disk(ManagedUpload::diskName())->put($removed, 'removed');
        Storage::disk(ManagedUpload::diskName())->put($retained, 'retained');

        $uploader = new PublicWebpUploader;
        $uploader->queueRemovedPaths(
            [['image_path' => $removed], ['image_path' => $retained]],
            [['image_path' => $retained]],
        );

        Storage::disk(ManagedUpload::diskName())->assertExists([$removed, $retained]);
        $uploader->commit();
        Storage::disk(ManagedUpload::diskName())->assertMissing($removed);
        Storage::disk(ManagedUpload::diskName())->assertExists($retained);
    }

    public function test_uploader_rejects_paths_outside_managed_uploads(): void
    {
        $uploader = new PublicWebpUploader;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Upload directory is not allowed.');

        $uploader->store(
            UploadedFile::fake()->image('unsafe.jpg', 50, 50),
            '../outside',
        );
    }

    public function test_uploader_rejects_a_file_when_the_configured_storage_quota_would_be_exceeded(): void
    {
        config()->set('filesystems.uploads_max_total_bytes', 1);

        $uploader = new PublicWebpUploader;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Upload storage quota would be exceeded.');

        $uploader->store(
            UploadedFile::fake()->image('quota.jpg', 50, 50),
            'uploads/test',
        );
    }

    public function test_managed_url_uses_the_configured_upload_disk(): void
    {
        $url = ManagedUpload::url('uploads/gallery/example.webp');

        $this->assertNotNull($url);
        $this->assertStringContainsString('uploads/gallery/example.webp', $url);
        $this->assertNull(ManagedUpload::url('../secret.txt'));
    }

    public function test_orphan_audit_defaults_to_dry_run_and_preserves_referenced_files(): void
    {
        $referenced = 'uploads/hero/referenced.webp';
        $orphan = 'uploads/hero/orphan.webp';

        SiteNotice::query()->create([
            'message' => 'Upload reference test',
            'is_active' => true,
            'hero_background_path' => $referenced,
        ]);

        Storage::disk(ManagedUpload::diskName())->put($referenced, 'referenced');
        Storage::disk(ManagedUpload::diskName())->put($orphan, 'orphan');

        $this->artisan('uploads:audit-orphans')
            ->expectsOutputToContain('orphan candidates: 1')
            ->expectsOutputToContain('Dry run only; no files were changed.')
            ->assertSuccessful();

        Storage::disk(ManagedUpload::diskName())->assertExists([$referenced, $orphan]);
    }

    public function test_orphan_quarantine_never_moves_referenced_files(): void
    {
        $referenced = 'uploads/hero/referenced.webp';
        $orphan = 'uploads/hero/orphan.webp';

        SiteNotice::query()->create([
            'message' => 'Upload quarantine test',
            'is_active' => true,
            'hero_background_path' => $referenced,
        ]);

        Storage::disk(ManagedUpload::diskName())->put($referenced, 'referenced');
        Storage::disk(ManagedUpload::diskName())->put($orphan, 'orphan');

        $this->artisan('uploads:audit-orphans', ['--quarantine' => true])
            ->expectsOutputToContain('Quarantined 1 file(s).')
            ->assertSuccessful();

        Storage::disk(ManagedUpload::diskName())->assertExists($referenced);
        Storage::disk(ManagedUpload::diskName())->assertMissing($orphan);
        $this->assertCount(
            1,
            Storage::disk(ManagedUpload::diskName())->allFiles('uploads/_quarantine')
        );
    }

    public function test_quarantine_purge_never_deletes_a_referenced_object(): void
    {
        $referenced = 'uploads/_quarantine/20260920/referenced.webp';
        $orphan = 'uploads/_quarantine/20260920/orphan.webp';

        SiteNotice::query()->create([
            'message' => 'Upload purge protection test',
            'is_active' => true,
            'hero_background_path' => $referenced,
        ]);

        $disk = Storage::disk(ManagedUpload::diskName());
        $disk->put($referenced, 'referenced');
        $disk->put($orphan, 'orphan');
        $oldTimestamp = now()->subDays(2)->timestamp;
        touch($disk->path($referenced), $oldTimestamp);
        touch($disk->path($orphan), $oldTimestamp);

        $this->artisan('uploads:audit-orphans', [
            '--purge' => true,
            '--retention-days' => 1,
        ])->expectsOutputToContain('Purged 1 quarantined file(s)')
            ->assertSuccessful();

        $disk->assertExists($referenced);
        $disk->assertMissing($orphan);
    }

    public function test_legacy_migration_is_dry_run_by_default_and_preserves_source(): void
    {
        Storage::fake('legacy_uploads');
        Storage::disk('legacy_uploads')->put('uploads/gallery/legacy.webp', 'legacy-image');

        $this->artisan('uploads:migrate-legacy')
            ->expectsOutputToContain('mode: dry-run')
            ->expectsOutputToContain('source and target files were not changed')
            ->assertSuccessful();

        Storage::disk('legacy_uploads')->assertExists('uploads/gallery/legacy.webp');
        Storage::disk(ManagedUpload::diskName())->assertMissing('uploads/gallery/legacy.webp');
    }

    public function test_legacy_migration_copies_and_verifies_without_deleting_source(): void
    {
        Storage::fake('legacy_uploads');
        Storage::disk('legacy_uploads')->put('uploads/gallery/legacy.webp', 'legacy-image');

        $this->artisan('uploads:migrate-legacy', ['--execute' => true])
            ->expectsOutputToContain('Copied and size-verified 1 file(s)')
            ->assertSuccessful();

        Storage::disk('legacy_uploads')->assertExists('uploads/gallery/legacy.webp');
        Storage::disk(ManagedUpload::diskName())->assertExists('uploads/gallery/legacy.webp');
    }
}
