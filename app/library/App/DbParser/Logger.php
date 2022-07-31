<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.11.20
 * Time: 11:48
 */

namespace App\DbParser;


class Logger
{
    /**
     * @var string
     */
    protected static $log_file = 'parser.log';

    /**
     * @param $message
     */
    public  static function log($message): void
    {
        $time = (new \DateTime())
            ->format('Y-m-d H:i:s');
        try {
            $file = fopen(__DIR__ . '/log/' . static::$log_file, 'ab');
            if ((filesize($file)-125000) > 0) {
                ftruncate($file, 0);
            }
            fwrite($file, $time . PHP_EOL . $message . PHP_EOL);
            fclose($file);
        } catch (\RuntimeException $exception) {

        }
    }

}