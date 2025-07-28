<?php
$pid = $_POST['pid'] ?? null;

if ($pid) {
    exec("taskkill /PID $pid /F");

    // Remove from JSON log
    $logFile = __DIR__ . '/../active_servers.json';
    $servers = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
    $filtered = array_filter($servers, fn($s) => $s['pid'] != $pid);
    file_put_contents($logFile, json_encode(array_values($filtered), JSON_PRETTY_PRINT));
}

echo "<p>Server with PID $pid stopped.</p>";
echo "<a href='server-manager.php'>Back to Server Manager</a>";
