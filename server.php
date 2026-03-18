<?php

// Custom router for `php artisan serve` on Windows.
// The built-in PHP server may return 403 for files served via junction/symlink
// (e.g. public/storage -> storage/app/public). We stream `/storage/*` files
// directly from storage/app/public to avoid that limitation.

$publicPath = getcwd(); // Laravel ServeCommand sets cwd to public_path()
$basePath = __DIR__;

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');

if (str_starts_with($uri, '/storage/')) {
    $storageRoot = realpath($basePath . '/storage/app/public');

    if ($storageRoot !== false) {
        $relative = ltrim(substr($uri, strlen('/storage/')), '/');
        $relative = str_replace(['\\', "\0"], ['/', ''], $relative);

        $candidate = $storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
        $real = realpath($candidate);

        if ($real !== false && str_starts_with($real, $storageRoot . DIRECTORY_SEPARATOR) && is_file($real)) {
            $mime = @mime_content_type($real) ?: 'application/octet-stream';
            header('Content-Type: ' . $mime);
            header('Content-Length: ' . filesize($real));
            readfile($real);
            return true;
        }
    }

    http_response_code(404);
    echo 'Not Found';
    return true;
}

// Default Laravel dev-server behavior: serve existing public files directly.
if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

$formattedDateTime = date('D M j H:i:s Y');
$requestMethod = $_SERVER['REQUEST_METHOD'];
$remoteAddress = $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'];

file_put_contents('php://stdout', "[$formattedDateTime] $remoteAddress [$requestMethod] URI: $uri\n");

require_once $publicPath . '/index.php';
