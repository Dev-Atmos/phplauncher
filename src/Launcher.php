<?php

class Launcher {
    public static function scanProjects($root) {
        return array_filter(scandir($root), function($item) use ($root) {
            return is_dir("$root\\$item") && $item !== '.' && $item !== '..';
        });
    }

    public static function launch($rootPath, $projectFolder, $phpPath, $port) {
        $dir = "$rootPath\\$projectFolder";
        $cmd = "cd /d \"$dir\" && start \"\" \"$phpPath\" -S localhost:$port";
        shell_exec($cmd);
        return "http://localhost:$port";
    }
}
