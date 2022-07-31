<?php
namespace App\DbParser;

use App\Model\ArticleSource;
use App\Model\SourceCategory;
use DOMXPath;

class Extractor
{
    /**
     * @param ArticleSource $source
     * @param string $html
     * @return string
     */
    public function extractCategoryTitle(ArticleSource $source, string $html): string
    {
        $path = $source->getCategoryTitleXpath();
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        return (new DOMXpath($dom))->query($path);
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
        $path = $source->getArticleXpath();
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        return  (new DOMXpath($dom))->query($path);
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
        $path = $source->getArticleTitleXpath();
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $title =  (new DOMXpath($dom))->query($path);

        $path = $source->getArticleDescriptionXpath();
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $description =  (new DOMXpath($dom))->query($path);

        $path = $source->getArticleTextXpath();
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $text =  (new DOMXpath($dom))->query($path);

        $path = $source->getArticleImageXpath();
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $image =  (new DOMXpath($dom))->query($path);

        return [$title, $description, $text, $image];
    }

    /**
     * @param SourceCategory $source
     * @param $xpath
     * @return array
     */
    public function extractArticlesUrl(SourceCategory $source, $xpath): array
    {
        $html = $source->getHtml();
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        return  (new DOMXpath($dom))->query($xpath);
    }

}