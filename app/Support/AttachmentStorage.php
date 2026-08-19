<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AttachmentStorage
{
    public static function ticketDisk(): string
    {
        return (string) config('filesystems.ticket_attachments_disk', 'public');
    }

    public static function messageDisk(): string
    {
        return (string) config('filesystems.message_attachments_disk', self::ticketDisk());
    }

    public static function isLocalDisk(?string $disk = null): bool
    {
        $disk ??= self::ticketDisk();

        return config("filesystems.disks.{$disk}.driver") === 'local';
    }

    public static function storeTicketUpload(UploadedFile $file, string $directory = 'documents'): array
    {
        return self::storeUploadedFile($file, $directory, self::ticketDisk());
    }

    public static function storeMessageUpload(UploadedFile $file, string $directory, string $filename): array
    {
        return self::storeUploadedFileAs($file, $directory, $filename, self::messageDisk());
    }

    public static function storeSalesforceContents(string $relativePath, string $contents): array
    {
        $disk = self::ticketDisk();
        Storage::disk($disk)->put($relativePath, $contents, [
            'visibility' => self::isLocalDisk($disk) ? 'public' : 'private',
        ]);

        return [
            'disk' => $disk,
            'path' => $relativePath,
            'url' => Storage::disk($disk)->url($relativePath),
        ];
    }

    public static function storeSalesforceFromLocalFile(string $relativePath, string $localFilePath): array
    {
        $disk = self::ticketDisk();
        $stream = fopen($localFilePath, 'r');
        if ($stream === false) {
            throw new \RuntimeException('Unable to read downloaded Salesforce file at '.$localFilePath);
        }

        try {
            $wrote = Storage::disk($disk)->put($relativePath, $stream, [
                'visibility' => self::isLocalDisk($disk) ? 'public' : 'private',
            ]);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        if (! $wrote) {
            throw new \RuntimeException('Failed to store Salesforce attachment on disk ['.$disk.'] path ['.$relativePath.'].');
        }

        return [
            'disk' => $disk,
            'path' => $relativePath,
            'url' => Storage::disk($disk)->url($relativePath),
        ];
    }

    public static function testDiskWrite(string $disk, string $directory = 'health-checks'): array
    {
        $filename = $directory.'/codex-storage-test-'.now()->format('YmdHis').'-'.bin2hex(random_bytes(4)).'.txt';
        $contents = 'Track Citations storage health check generated at '.now()->toDateTimeString();

        Storage::disk($disk)->put($filename, $contents);

        $exists = Storage::disk($disk)->exists($filename);
        $url = null;

        try {
            $url = Storage::disk($disk)->url($filename);
        } catch (\Throwable) {
            $url = null;
        }

        Storage::disk($disk)->delete($filename);

        return [
            'disk' => $disk,
            'path' => $filename,
            'exists_after_write' => $exists,
            'url' => $url,
        ];
    }

    public static function relativePathFromStoredPath(?string $storedPath): ?string
    {
        if (blank($storedPath)) {
            return null;
        }

        $parsedPath = parse_url($storedPath, PHP_URL_PATH);
        $path = $parsedPath ?: $storedPath;
        $path = ltrim((string) $path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        $bucket = (string) config('filesystems.disks.s3.bucket');
        if ($bucket !== '' && str_starts_with($path, $bucket.'/')) {
            $path = substr($path, strlen($bucket) + 1);
        }

        return $path !== '' ? $path : null;
    }

    protected static function storeUploadedFile(UploadedFile $file, string $directory, string $disk): array
    {
        $path = Storage::disk($disk)->put($directory, $file);

        return [
            'disk' => $disk,
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
        ];
    }

    protected static function storeUploadedFileAs(UploadedFile $file, string $directory, string $filename, string $disk): array
    {
        $safeName = preg_replace('/[^A-Za-z0-9._-]+/', '-', $filename) ?: ('file-'.time());
        $safeName = trim($safeName, '.-') ?: ('file-'.time());

        $realPath = $file->getRealPath();
        if (! $realPath || ! is_readable($realPath)) {
            throw new \RuntimeException('Uploaded temp file is missing or unreadable.');
        }

        $path = trim($directory.'/'.$safeName, '/');

        // Explicit private visibility avoids public-read ACL failures on private Spaces.
        $wrote = Storage::disk($disk)->put($path, fopen($realPath, 'r'), [
            'visibility' => 'private',
        ]);

        if (! $wrote) {
            throw new \RuntimeException('Failed to write attachment to disk ['.$disk.'] path ['.$path.']. Check Spaces credentials and bucket permissions.');
        }

        // Some Spaces/S3 key policies allow PutObject but make HeadObject noisy.
        // Only fail when we can positively confirm the object is missing.
        try {
            if (! Storage::disk($disk)->exists($path)) {
                throw new \RuntimeException('Attachment write reported success but object is missing on disk ['.$disk.'] path ['.$path.'].');
            }
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);
        }

        $url = null;
        try {
            $url = Storage::disk($disk)->url($path);
        } catch (\Throwable) {
            $url = null;
        }

        return [
            'disk' => $disk,
            'path' => $path,
            'url' => $url,
        ];
    }
}
