<?php

namespace App\Integrations\Salesforce;

use Illuminate\Support\Facades\File;

class SalesforceSyncLogger
{
    public const LOG_FILE = 'salesforce-sync.log';

    public static function path(): string
    {
        return storage_path('logs/'.self::LOG_FILE);
    }

    public static function clear(): void
    {
        File::ensureDirectoryExists(dirname(self::path()));
        File::put(self::path(), '');
    }

    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    public static function read(?int $maxBytes = 200000): string
    {
        $path = self::path();
        if (! File::exists($path)) {
            return "No Salesforce sync log found yet. Run a sync first.\n";
        }

        $size = File::size($path);
        if ($size <= $maxBytes) {
            return File::get($path);
        }

        $fh = fopen($path, 'rb');
        fseek($fh, -$maxBytes, SEEK_END);
        $content = stream_get_contents($fh) ?: '';
        fclose($fh);

        return "...(showing last {$maxBytes} bytes)...\n".$content;
    }

    protected static function write(string $level, string $message, array $context = []): void
    {
        File::ensureDirectoryExists(dirname(self::path()));

        $line = '['.now()->toDateTimeString()."] [{$level}] {$message}";
        if ($context !== []) {
            $line .= ' '.json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        File::append(self::path(), $line.PHP_EOL);
    }
}
