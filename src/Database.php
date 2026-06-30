<?php
declare(strict_types=1);

namespace Lumina;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Thin PDO wrapper with a single shared connection.
 * Supports SQLite (default, self-contained) and MySQL.
 */
final class Database
{
    private static ?PDO $instance = null;
    private static array $config = [];

    public static function configure(array $dbConfig): void
    {
        self::$config = $dbConfig;
    }

    public static function connection(): PDO
    {
        if (self::$instance instanceof PDO) {
            return self::$instance;
        }

        $cfg    = self::$config;
        $driver = $cfg['driver'] ?? 'sqlite';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            if ($driver === 'mysql') {
                $m   = $cfg['mysql'];
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    $m['host'], $m['port'], $m['database'], $m['charset']
                );
                self::$instance = new PDO($dsn, $m['username'], $m['password'], $options);
            } else {
                $path = $cfg['sqlite']['path'];
                $dir  = dirname($path);
                if (!is_dir($dir)) {
                    mkdir($dir, 0775, true);
                }
                self::$instance = new PDO('sqlite:' . $path, null, null, $options);
                self::$instance->exec('PRAGMA foreign_keys = ON;');
                self::$instance->exec('PRAGMA journal_mode = WAL;');
            }
        } catch (PDOException $e) {
            throw new RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
        }

        return self::$instance;
    }

    public static function driver(): string
    {
        return self::$config['driver'] ?? 'sqlite';
    }
}
