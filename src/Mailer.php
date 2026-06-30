<?php
declare(strict_types=1);

namespace Lumina;

/**
 * Dependency-free mailer.
 *
 * Drivers:
 *   - log  : append the message to data/notifications.log (default, always works)
 *   - mail : PHP's built-in mail()
 *   - smtp : minimal SMTP client (SSL on 465 / STARTTLS on 587 / plain), AUTH LOGIN
 *
 * Sending never throws — failures are logged and the method returns false so a
 * form submission is always saved even if the inbox is unreachable.
 */
final class Mailer
{
    private static array $cfg = [];
    private static string $logFile = '';

    public static function configure(array $mailConfig, string $logFile): void
    {
        self::$cfg     = $mailConfig;
        self::$logFile = $logFile;
    }

    public static function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): bool {
        $driver = self::$cfg['driver'] ?? 'log';

        try {
            switch ($driver) {
                case 'smtp':
                    return self::sendSmtp($toEmail, $toName, $subject, $htmlBody, $replyToEmail, $replyToName);
                case 'mail':
                    return self::sendMail($toEmail, $subject, $htmlBody, $replyToEmail, $replyToName);
                case 'log':
                default:
                    return self::logMessage($toEmail, $subject, $htmlBody);
            }
        } catch (\Throwable $e) {
            self::logMessage($toEmail, '[FAILED:' . $driver . '] ' . $subject, $htmlBody . "\n\nERROR: " . $e->getMessage());
            return false;
        }
    }

    private static function fromHeader(): string
    {
        $fe = self::$cfg['from_email'] ?? 'no-reply@example.com';
        $fn = self::$cfg['from_name'] ?? '';
        return $fn !== '' ? sprintf('%s <%s>', self::encodeHeader($fn), $fe) : $fe;
    }

    private static function encodeHeader(string $text): string
    {
        return preg_match('/[^\x20-\x7E]/', $text)
            ? '=?UTF-8?B?' . base64_encode($text) . '?='
            : $text;
    }

    private static function headers(?string $replyToEmail, ?string $replyToName): array
    {
        $h = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . self::fromHeader(),
        ];
        if ($replyToEmail) {
            $h[] = 'Reply-To: ' . ($replyToName
                ? sprintf('%s <%s>', self::encodeHeader($replyToName), $replyToEmail)
                : $replyToEmail);
        }
        return $h;
    }

    private static function sendMail(string $to, string $subject, string $body, ?string $rt, ?string $rtn): bool
    {
        $headers = implode("\r\n", self::headers($rt, $rtn));
        $ok = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);
        self::logMessage($to, ($ok ? '[mail OK] ' : '[mail FAIL] ') . $subject, $body);
        return $ok;
    }

    private static function logMessage(string $to, string $subject, string $body): bool
    {
        if (self::$logFile === '') {
            return false;
        }
        $dir = dirname(self::$logFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $entry = sprintf(
            "[%s] TO: %s\nSUBJECT: %s\n%s\n%s\n\n",
            date('Y-m-d H:i:s'),
            $to,
            $subject,
            str_repeat('-', 60),
            trim(strip_tags($body))
        );
        return (bool) @file_put_contents(self::$logFile, $entry, FILE_APPEND | LOCK_EX);
    }

    // ---- Minimal SMTP -----------------------------------------------------
    private static function sendSmtp(string $to, string $toName, string $subject, string $body, ?string $rt, ?string $rtn): bool
    {
        $smtp   = self::$cfg['smtp'] ?? [];
        $host   = $smtp['host'] ?? '';
        $port   = (int) ($smtp['port'] ?? 587);
        $secure = strtolower((string) ($smtp['secure'] ?? 'tls'));
        $user   = $smtp['user'] ?? '';
        $pass   = $smtp['pass'] ?? '';

        if ($host === '') {
            throw new \RuntimeException('SMTP host not configured');
        }

        $transport = $secure === 'ssl' ? "ssl://{$host}" : $host;
        $ctx = stream_context_create(['ssl' => ['verify_peer' => true, 'verify_peer_name' => true]]);
        $conn = @stream_socket_client(
            "{$transport}:{$port}",
            $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $ctx
        );
        if (!$conn) {
            throw new \RuntimeException("SMTP connect failed: {$errstr} ({$errno})");
        }
        stream_set_timeout($conn, 15);

        $read = static function () use ($conn): string {
            $data = '';
            while (($line = fgets($conn, 515)) !== false) {
                $data .= $line;
                if (isset($line[3]) && $line[3] === ' ') {
                    break;
                }
            }
            return $data;
        };
        $cmd = static function (string $c) use ($conn, $read): string {
            fwrite($conn, $c . "\r\n");
            return $read();
        };
        $expect = static function (string $resp, string $code, string $stage): void {
            if (strncmp($resp, $code, strlen($code)) !== 0) {
                throw new \RuntimeException("SMTP {$stage} failed: " . trim($resp));
            }
        };

        $expect($read(), '220', 'greeting');
        $host_name = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $expect($cmd("EHLO {$host_name}"), '250', 'EHLO');

        if ($secure === 'tls') {
            $expect($cmd('STARTTLS'), '220', 'STARTTLS');
            if (!stream_socket_enable_crypto($conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT)) {
                throw new \RuntimeException('STARTTLS negotiation failed');
            }
            $expect($cmd("EHLO {$host_name}"), '250', 'EHLO(TLS)');
        }

        if ($user !== '') {
            $expect($cmd('AUTH LOGIN'), '334', 'AUTH');
            $expect($cmd(base64_encode($user)), '334', 'AUTH user');
            $expect($cmd(base64_encode($pass)), '235', 'AUTH pass');
        }

        $from = self::$cfg['from_email'] ?? 'no-reply@example.com';
        $expect($cmd("MAIL FROM:<{$from}>"), '250', 'MAIL FROM');
        $expect($cmd("RCPT TO:<{$to}>"), '25', 'RCPT TO'); // 250 / 251
        $expect($cmd('DATA'), '354', 'DATA');

        $headers = self::headers($rt, $rtn);
        $headers[] = 'To: ' . ($toName ? sprintf('%s <%s>', self::encodeHeader($toName), $to) : $to);
        $headers[] = 'Subject: =?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers[] = 'Date: ' . date('r');

        $message = implode("\r\n", $headers) . "\r\n\r\n" . self::dotStuff($body) . "\r\n.";
        $expect($cmd($message), '250', 'send body');
        @fwrite($conn, "QUIT\r\n");
        fclose($conn);

        self::logMessage($to, '[smtp OK] ' . $subject, $body);
        return true;
    }

    private static function dotStuff(string $body): string
    {
        $body = str_replace(["\r\n", "\r", "\n"], "\r\n", $body);
        return preg_replace('/^\./m', '..', $body);
    }
}
