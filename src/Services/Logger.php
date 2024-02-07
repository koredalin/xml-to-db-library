<?php

namespace Library\Services;

/**
 * Description of Logger helper.
 *
 * @author H1
 */
class Logger
{
    /**
     * Records the log to a file.
     */
    public const TO_FILE = 3;
    public const LOGS_FOLDER = __DIR__ . '/../../logs/';

    /**
     * Records the errors into self::LOGS_FOLDER.
     *
     * @param string $message
     * @param string $fileName
     * @return void
     */
    public static function error(string $message, string $fileName): void
    {
        error_log(
            "Database exception ".date('Y-m-d H:i:s').": {$message}" . PHP_EOL . PHP_EOL,
            self::TO_FILE,
            self::LOGS_FOLDER . $fileName
        );
    }
}
