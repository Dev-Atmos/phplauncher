<?php
file_put_contents('debug.txt', "Request received\n", FILE_APPEND);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Content-Type: application/json');



$settings = require __DIR__ . '/../config/settings.php';
require_once __DIR__ . '/../src/Launcher.php';
require_once __DIR__ . '/../config/config.php';

$roots = $settings['roots'];
$versions = $settings['phpVersions'];
$selectedRoot = $_POST['root'] ?? null;
$project = $_POST['project'];
$phpExec = $versions[$_POST['php']] ?? null;
file_put_contents('debug.txt', "Selected root: $selectedRoot, PHP: $phpExec, Project: $project\n", FILE_APPEND);

if (!$phpExec || !$roots[$selectedRoot]) {
    file_put_contents('error_log.txt', "Invalid PHP version or root path\n", FILE_APPEND);
    echo json_encode(['error' => 'Invalid configuration.']);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project'], $_POST['php'])) {

    $port = rand(8000, 8999); // Random port for simplicity
    $launcher = new Launcher();
    $launch = $launcher->launch($roots[$selectedRoot], $project, $phpExec, $port);
    $launch['root'] = $selectedRoot;
    $launch['project'] = $project;
    $launch['php'] = $_POST['php'];
    if ($launch['url']) {
        // Log the URL to a file for debugging
        file_put_contents('launch_log.txt', "Project launched: {$launch['url']}\n", FILE_APPEND);
        echo json_encode($launch) . PHP_EOL;
    } else {
        file_put_contents('error_log.txt', "Launch failed: got empty URL\n", FILE_APPEND);
        echo json_encode(['error' => 'URL not generated.']) . PHP_EOL;
    }
}
