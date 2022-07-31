<?php
namespace App\DbParser;

use App\Model\ArticleSource;
use App\Model\SourceCategory;

class Extractor
{
    /**
     * @param ArticleSource $source
     * @param string $html
     * @return string
     */
    public function extractCategoryTitle(ArticleSource $source, string $html): string
    {
        return '';
    }

    /**
     * extract articles paths from html
     *
     * @param ArticleSource $source
     * @param string $html
     * @return array
     */
    public function extractArticlesPath(ArticleSource $source, string $html): array
    {
        return [];
    }


    /**
     * extract title, description, text
     *
     * @param ArticleSource $source
     * @param string $html
     * @return array
     */
    public function extractArticlesData(ArticleSource $source, string $html): array
    {
        return ['title' => '', 'description' => '', 'text' => '', 'image'];
    }

    /**
     * @param SourceCategory $source
     * @param $xpath
     * @return array
     */
    public function extractArticlesUrl(SourceCategory $source, $xpath): array
    {
        return [];
    }

}