<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.11.20
 * Time: 12:04
 */

namespace App\Interfaces;

/**
 * Interface CrawleraHttpClientInterface
 * @package App\Interfaces
 */
interface CrawleraHttpClientInterface
{
    public function get($url, $params = []);
}