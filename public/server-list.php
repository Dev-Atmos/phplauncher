<?php
$logFile = __DIR__ . '/../active_servers.json';
$servers = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

if (empty($servers)) {
    echo "<p>No active servers found.</p>";
} else {
    echo "<ul>";
    foreach ($servers as $server) {
        echo "<li class='serverItem' data-port='{$server['port']}'>";
        echo "Project: {$server['project']} | Port: {$server['port']} | PID: {$server['pid']} | Started: {$server['time']} ";
        echo "<button onclick='stopServer({$server['pid']})'>Stop</button>";
        echo "</li>";
    }
    echo "</ul>";
}
