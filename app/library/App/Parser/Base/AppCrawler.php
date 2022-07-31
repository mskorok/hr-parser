<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 06.03.21
 * Time: 19:38
 */

namespace App\Parser\Base;


use Symfony\Component\DomCrawler\Crawler;

class AppCrawler extends Crawler
{
    /**
     * @param $xpath
     * @return mixed
     */
    public function getXPathOneNode($xpath)
    {
        $nodes = $this->filterXPath($xpath);
        if (null === $nodes || $nodes->count() === 0) {
            return null;
        }

        if ($nodes->count() === 1) {
            return $nodes->first();
        }
        return $nodes;

    }

    /**
     * @param $xpath
     * @return mixed
     */
    public function getXPathNodes($xpath)
    {
        try {
            $nodes = $this->filterXPath($xpath);
        } catch(\InvalidArgumentException $e) {
            return null;
        }
        if ($nodes->count() === 0) {
            return null;
        }
        return $nodes;
    }
}