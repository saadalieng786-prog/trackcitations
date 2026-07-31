<?php

namespace App\Support;

class EnvironmentWriter
{
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
