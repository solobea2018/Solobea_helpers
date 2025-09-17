<?php


namespace Solobea\Helpers\visitor;

class VisitorData
{
    private static string $blockFile =  __DIR__.'/../../ips/blocked_fingerprints.json';

    public static function getIPAddress(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]; // handle proxy chains
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function getUserAgent(): string {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    }

    public static function getFingerprint(): string {
        return hash('sha256', self::getIPAddress() . self::getUserAgent());
    }

    public static function BlockedHtml($ip): string {
        return '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Blocked</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #fff8f8;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #f44336;
            background-color: #ffebee;
            border-radius: 8px;
        }
        h2 {
            color: #d32f2f;
        }
        ul {
            color: #c62828;
        }
        small {
            color: #b71c1c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Access Blocked</h2>
        <p>Your access to this website has been <strong>temporarily restricted</strong> due to one or more of the following reasons:</p>
        <ul>
            <li>Unusual or suspicious activity from your device</li>
            <li>Attempting to submit spam or inappropriate content</li>
            <li>Too many failed login attempts</li>
            <li>Violation of website usage policies</li>
        </ul>
        <p>If you believe this was a mistake, please contact the website administrator for assistance.</p>
        <hr>
        <small>IP Address: ' . htmlspecialchars($ip) . '</small>
    </div>
</body>
</html>';
    }

    // Block a fingerprint (IP + User Agent)
    public static function blockCurrentVisitor() {
        $fp = self::getFingerprint();
        $blocked = self::getBlockedFingerprints();

        if (!in_array($fp, $blocked)) {
            $blocked[] = $fp;
            file_put_contents(self::$blockFile, json_encode($blocked, JSON_PRETTY_PRINT));
        }
    }

    // Check if current fingerprint is blocked
    public static function isBlocked(): bool {
        return in_array(self::getFingerprint(), self::getBlockedFingerprints());
    }

    // Get all blocked fingerprints
    private static function getBlockedFingerprints(): array {
        if (!file_exists(self::$blockFile)) {
            return [];
        }
        $json = file_get_contents(self::$blockFile);
        return json_decode($json, true) ?: [];
    }
}


