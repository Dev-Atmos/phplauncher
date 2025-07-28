<?php
require_once __DIR__ . '/../config/config.php';
class Launcher
{
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
