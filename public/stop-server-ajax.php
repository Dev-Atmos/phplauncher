<?php
$pid = $_POST['pid'] ?? null;

if ($pid) {
    exec("taskkill /PID $pid /F");

    // Clean up JSON
    $logFile = __DIR__ . '/../active_servers.json';
    $servers = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
    $filtered = array_filter($servers, fn($s) => $s['pid'] != $pid);
    file_put_contents($logFile, json_encode(array_values($filtered), JSON_PRETTY_PRINT));

    echo "Server $pid stopped";
} else {
    echo "Invalid PID";
}
