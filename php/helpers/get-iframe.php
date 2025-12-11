<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Valida que un iframe provenga de Google Maps embed y que sea un iframe con una URL valida.
 */
function getIframe(?string $iframeUrl): ?string {
    if ($iframeUrl === null) {
        return null;
    }

    $iframeUrl = trim($iframeUrl);

    if ($iframeUrl === '' || !filter_var($iframeUrl, FILTER_VALIDATE_URL)) {
        return null;
    }

    $parts = parse_url($iframeUrl);

    if (!is_array($parts)) {
        return null;
    }

    $scheme = $parts['scheme'] ?? '';
    $host   = $parts['host'] ?? '';
    $path   = $parts['path'] ?? '';

    if ($scheme !== 'https' || $host !== 'www.google.com' || strpos($path, '/maps/embed') !== 0) {
        return null;
    }

    return $iframeUrl;
}
?>