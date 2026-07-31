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
        Storage::disk($disk)->put($relativePath, $contents);

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
            return substr($path, strlen('storage/'));
        }

        return $path;
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
        $path = Storage::disk($disk)->putFileAs($directory, $file, $filename);

        return [
            'disk' => $disk,
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
        ];
    }
}
