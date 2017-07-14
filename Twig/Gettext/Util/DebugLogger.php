<?php

namespace Twig\Gettext\Util;

class DebugLogger
{
    protected static $logPath;

    protected static function getPath()
    {
        if (!self::$logPath) {
            $fileTimestamp = date('Ymdhis');

            self::$logPath = realpath(__DIR__ . "/../../../") . "/{$fileTimestamp}-extract.log";
        }

        return self::$logPath;
    }

    public static function log($txt)
    {
        $path = self::getPath();

        $dateStamp = date('Y-m-d H:i:s');
        $line = "[{$dateStamp}] {$txt}" . PHP_EOL;

        file_put_contents($path, $line, \FILE_APPEND);
    }
}