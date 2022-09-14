<?php

namespace App\DbParser;

use App\Model\ArticleSource;
use App\Model\SourceCategory;
use RuntimeException;

class CategoriesParser extends BaseParser
{

    /**
     * @return void
     */
    public function parse()
    {
        $sources = $this->getSources();

        /** @var ArticleSource $source */
        foreach ($sources as $source) {
            if ($source->getHasCategory()) {
                if ($source->getCategoryPageNumber()) {
                    $this->processCategoriesWithPageNumber($source);
                } else {
                    $this->processCategories($source);
                }
            } else if ($source->getArticlePageNumber()) {
                $this->processArticlesWithPageNumber($source);
            } else {
                $this->processArticles($source);
            }
            $source->setParsed(1);
            $source->save();
        }
    }

    private function processCategoriesWithPageNumber(ArticleSource $source): void
    {
        $i = 1;

        $htmlArray = [];
        $path = $source->getCategoryXpath();
        do {
            $path = str_replace('{id}', $i, $path);
            $i++;
            $html = $this->getHttpClient()->get($path);
            if ($html) {
                $categoryTitle = $this->extractor->extractCategoryTitle($source, $html);
                $htmlArray[] = ['path' => $path, 'html' => $html, 'title' => $categoryTitle];
            }
            sleep(1);
        } while($html || $i < static::MAX_PARSED);

        $this->saveCategories($source, $htmlArray);

    }

    private function processCategories(ArticleSource $source)
    {
        $htmlArray = [];
        $path = $source->getCategoryXpath();

        $html = $this->getHttpClient()->get($path);
        if ($html) {
            $categoryData = $this->extractor->extractCategoryTitle($source, $html);
            $htmlArray[] = ['path' => $path, 'html' => $html, 'data' => $categoryData];
        }
        $this->saveCategories($source, $htmlArray);
    }



    private function saveCategories(ArticleSource $source, array $htmlArray)
    {
        foreach ($htmlArray as $item) {
            $model = new SourceCategory();
            $model->setHtml($item['html']);
            $model->setSourceId($source->getId());
            $model->setUrl($item['path']);
            $model->setParsed(0);
            try {
                $model->save();
            } catch (RuntimeException $exception) {
                Logger::log($exception->getMessage());
            }
        }
    }

    /**
     * @param ArticleSource $source
     * @return void
     */
    private function processArticlesWithPageNumber(ArticleSource $source)
    {
        $sourcePath = $source->getArticleSource();
        $paths = [];
        $i = 1;

        do {
            $_sourcePath = str_replace('{id}', $i, $sourcePath);
            $i++;
            $html = $this->getHttpClient()->get($_sourcePath);
            $articlePaths = $this->extractor->extractArticlesPath($source, $html);

            foreach ($articlePaths as $path) {
                $paths[] = $path;
            }

        } while ($html || $i < static::MAX_PARSED);

        $this->saveArticles($paths, $source);
    }

    /**
     * @param ArticleSource $source
     * @return void
     */
    private function processArticles(ArticleSource $source)
    {
        $sourcePath = $source->getArticleSource();
        $paths = [];

        $html = $this->getHttpClient()->get($sourcePath);
        $articlePaths = $this->extractor->extractArticlesPath($source, $html);

        foreach ($articlePaths as $path) {
            $paths[] = $path;
        }

        $this->saveArticles($paths, $source);
    }
}