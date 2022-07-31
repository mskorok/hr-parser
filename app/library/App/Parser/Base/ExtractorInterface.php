<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 28.02.21
 * Time: 21:33
 */

namespace App\Parser\Base;


use App\Model\Articles;
use App\Model\ParsedInterface;

interface ExtractorInterface
{

    /**
     * @param string $url
     * @return bool
     */
    public function extract(string $url);


    public function extractArticle(string $url);

    /**
     * @return array
     */
    public function getArticles();

    /**
     * @param string $url
     * @return Articles | null
     */
    public function getArticle(string $url);

    /**
     * @param ParsedInterface $article
     * @return mixed
     */
    public function saveArticle(ParsedInterface $article);

    /**
     * @return array
     */
    public function getPagesUrl();

    /**
     * @param string $url
     * @return string
     */
    public function getCategory(string $url);

}