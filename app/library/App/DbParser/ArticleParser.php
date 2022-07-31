<?php

namespace App\DbParser;

use App\Model\ArticleSource;
use App\Model\SourceCategory;

class ArticleParser extends BaseParser
{


    public function parse()
    {
        $sources = $this->getCategorySources();

        foreach ($sources as $source) {
            $articleSource = $sources->getArticleSource();
            $this->processArticlesFromCategory($sources, $articleSource);

            $source->setParsed(1);
            $source->save();
        }
    }

    /**
     * @param SourceCategory $source
     * @param ArticleSource $articleSource
     * @return void
     */
    private function processArticlesFromCategory(SourceCategory $source, ArticleSource $articleSource)
    {
        $xpath = $articleSource->getArticleXpath();
        $articlePaths = $this->extractor->extractArticlesUrl($source, $xpath);
        $this->saveArticles($articlePaths, $articleSource, $source);
    }
}