<?php

namespace App\Support;

class EnvironmentWriter
{
    public static function readMany(string $path, array $keys): array
    {
        $values = array_fill_keys($keys, '');

        if (! is_readable($path)) {
            return $values;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES) ?: [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (! str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            if (! array_key_exists($key, $values)) {
                continue;
            }

            $value = trim($value);
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"'))
                || (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            $values[$key] = stripcslashes($value);
        }

        return $values;
    }

    public static function updateMany(string $path, array $values): void
    {
        $contents = file_exists($path) ? file_get_contents($path) : '';

        foreach ($values as $key => $value) {
            $escaped = self::formatValue($value);
            $pattern = "/^{$key}=.*$/m";

            if (preg_match($pattern, $contents)) {
                // Preserve dollar signs and backslashes in secrets literally.
                $contents = preg_replace_callback(
                    $pattern,
                    static fn (): string => "{$key}={$escaped}",
                    $contents
                );
            } else {
                $contents = rtrim($contents) . PHP_EOL . "{$key}={$escaped}" . PHP_EOL;
            }
        }

        file_put_contents($path, $contents);
    }

    protected static function formatValue(mixed $value): string
    {
        $value = (string) ($value ?? '');

        if ($value === '') {
            return '';
        }

        if ($value === 'true' || $value === 'false' || is_numeric($value)) {
            return $value;
        }

        return '"' . str_replace(
            ['\\', '"'],
            ['\\\\', '\"'],
            $value
        ) . '"';
    }
}
