<?php
declare(strict_types=1);

namespace Lumina;

/**
 * Lightweight, file-based fixed-window rate limiter (per IP + bucket).
 * No external store needed — keeps small JSON counters under data/ratelimit/.
 * Good enough to blunt form spam on shared hosting; swap for Redis at scale.
 */
final class RateLimiter
{
    private static string $dir = '';

    public static function configure(string $storageDir): void
    {
        self::$dir = rtrim($storageDir, '/');
    }

    /**
     * @return bool true if the request is allowed, false if the limit is hit.
     */
    public static function allow(string $bucket, string $key, int $max, int $window): bool
    {
        if (self::$dir === '' || $max <= 0) {
            return true;
        }
        if (!is_dir(self::$dir)) {
            @mkdir(self::$dir, 0775, true);
        }

        $file = self::$dir . '/' . preg_replace('/[^a-z0-9_]/i', '_', $bucket . '_' . $key) . '.json';
        $now  = time();

        $fp = @fopen($file, 'c+');
        if (!$fp) {
            return true; // fail open — never block a real customer on FS issues
        }
        flock($fp, LOCK_EX);

        $raw  = stream_get_contents($fp) ?: '';
        $data = json_decode($raw, true);
        if (!is_array($data) || ($data['reset'] ?? 0) <= $now) {
            $data = ['count' => 0, 'reset' => $now + $window];
        }
        $data['count']++;

        $allowed = $data['count'] <= $max;

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        return $allowed;
    }

    /** Best-effort client IP detection behind common proxies. */
    public static function clientIp(): string
    {
        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'] as $h) {
            if (!empty($_SERVER[$h])) {
                $ip = trim(explode(',', $_SERVER[$h])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return '0.0.0.0';
    }
}
