<?php
/**
 * Minimal .env loader (no dependencies).
 * Parses KEY=VALUE lines from a file and exposes them via getenv()/$_ENV.
 * Existing real environment variables always win, so platform config
 * (Heroku, Forge, Docker, cPanel) overrides the file.
 */

declare(strict_types=1);

if (!function_exists('env_load')) {
    function env_load(string $path): void
    {
        if (!is_file($path) || !is_readable($path)) {
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value);

            // Strip matching surrounding quotes.
            if (strlen($value) >= 2) {
                $first = $value[0];
                $last  = $value[strlen($value) - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            // Don't clobber real environment variables.
            if (getenv($key) !== false) {
                continue;
            }
            putenv("{$key}={$value}");
            $_ENV[$key]    = $value;
            $_SERVER[$key] = $value;
        }
    }

    /** Typed env reader with default + bool/null coercion. */
    function env(string $key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        switch (strtolower($value)) {
            case 'true':  case '(true)':  return true;
            case 'false': case '(false)': return false;
            case 'null':  case '(null)':  return null;
            case 'empty': case '(empty)': return '';
        }
        return $value;
    }
}
