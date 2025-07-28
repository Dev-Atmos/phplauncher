<?php

function getBaseUrl(): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST']; // includes port
    $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // e.g., "/public"

    return $protocol . $host . $scriptDir . '/';
}
