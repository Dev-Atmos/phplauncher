<?php
require_once __DIR__ . '/../config/config.php';
class Launcher
{
    private static $currentProject = '';
    private static $currentPort = '';
    public static function scanProjects($root)
    {
        return array_filter(scandir($root), function ($item) use ($root) {
            return is_dir("$root\\$item") && $item !== '.' && $item !== '..';
        });
    }

    public function launch($rootPath, $projectFolder, $phpPath, $port)
    {
        if (self::isPortInUse($port)) {
            file_put_contents('debug.txt', "Port $port already in use\n", FILE_APPEND);
            return "http://127.0.0.1:$port";
        }

        self::$currentProject = $projectFolder;
        self::$currentPort = $port;
        $dir = "$rootPath\\$projectFolder";
        $cmd = "cd /d \"$dir\" && start \"\" \"$phpPath\" -S 127.0.0.1:$port";
        $url = "http://127.0.0.1:$port";

        self::executeShellCommand($cmd);
        $time = date('Y-m-d H:i:s');
        file_put_contents('debug.txt', "[$time] Launching: $projectFolder on $url\n", FILE_APPEND);

        return [
            'url' => $url,
            'portAvailable' => !self::isPortInUse($port),
            // 'cmdExecuted' => $cmd
        ];
    }
    private static function executeShellCommand($cmd)
    {
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];
        $process = proc_open($cmd, $descriptorspec, $pipes);
        if (is_resource($process)) {
            $status = proc_get_status($process);
            $pid = $status['pid'];

            $entry = [
                'pid' => $pid,
                'port' => self::$currentPort,
                'project' => self::$currentProject,
                'time' => date('Y-m-d H:i:s')
            ];

            $logFile = __DIR__ . '/../active_servers.json';
            $entries = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
            $entries[] = $entry;
            file_put_contents($logFile, json_encode($entries, JSON_PRETTY_PRINT));

            // Don't close proc if you want the PHP server to persist
        }
        file_put_contents('debug.txt', "Executing command: $cmd\n", FILE_APPEND);
        pclose(popen("start /B cmd /c \"$cmd\"", "r"));  // Fully detached process
    }

    // private static function executeShellCommand($cmd)
    // {
    //     file_put_contents('debug.txt', "Executing command: $cmd\n", FILE_APPEND);
    //     shell_exec($cmd);
    // }
    public static function isPortInUse($port)
    {
        $connection = @fsockopen("127.0.0.1", $port);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }
}
