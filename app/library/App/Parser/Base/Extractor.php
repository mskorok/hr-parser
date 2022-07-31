<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.11.20
 * Time: 11:32
 */

namespace App\Parser\Base;

use App\Model\Articles;
use App\Model\ArticlesSource;
use App\Model\ParsedInterface;

class Extractor implements ExtractorInterface
{

    /**
     * @var DataMapper
     */
    protected $dataMapper;

    /**
     * @desc urls of articles
     *
     * @var array
     */
    protected $articleUrls = [];

    /**
     * @var array article content;
     */
    protected $articles = [];

    /**
     * @var array
     */
    protected $categories = [];

    /**
     * @var int
     */
    protected $errorsCount = 0;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Extractor constructor.
     *
     * @param DataMapper $mapper
     */
    public function __construct(DataMapper $mapper)
    {
        $this->dataMapper = $mapper;
        $this->logger = new Logger;

    }

    /**
     * @param string $url
     * @return bool
     *
     */
    public function extract(string $url): bool
    {
        try {
            $this->extractArticle($url);
        } catch (\RuntimeException $e) {
            $this->errorsCount++;
            $this->logger::log('Extract message : '.$e->getMessage().PHP_EOL);
        }

        return true;

    }

    public function extractLink(ArticlesSource $source) {
        $links = $this->dataMapper->mapArticles($source->getLink());
        foreach ($links as $link) {
            $article = new Articles();
            $article->setLink($link);
            $article->setLanguageId($source->getLanguageId());
            try {
                $article->save();
            } catch (\RuntimeException $e) {
                $this->errorsCount++;
                $this->logger::log('Extract message : '.$e->getMessage().PHP_EOL);
            }
        }
    }

    /**
     * @param string $url
     * @return string | null
     *
     */
    public function extractArticle(string $url): ?string
    {
        $content = $this->dataMapper->getContent($url);
        $category = $this->dataMapper->mapCategory();
        if ($category) {
            $this->categories[$url] = $category;
        }
        $article = $this->dataMapper->map($content);
        if ($content) {
            $this->articles[$url] = $article;
            $this->saveArticle($article);

            return $article;
        }

        return null;
    }

    /**
     * @desc Saving page data into storage (DB, file, etc)
     *
     * @param ParsedInterface $article
     * @return bool
     */
    public function saveArticle(ParsedInterface $article = null)
    {
        if ($article instanceof Articles || $article instanceof ArticlesSource) {
            $article->setParsed(1);
            $article->save();
            return true;
        }

        return false;
    }

    /**
     * @desc Get extracted article content
     *
     * @param string $url
     * @return Articles | null
     */
    public function getArticle(string $url): ?Articles
    {
        return $this->articles[$url] ?? null;
    }

    /**
     * @return array
     *
     */
    public function getPagesUrl()
    {
        $this->articleUrls = $this->dataMapper->getPagesUrl();
        return $this->articleUrls;
    }

    /**
     * @param string $url
     * @return string | null
     */
    public function getCategory(string $url)
    {
        return $this->categories[$url] ?? null;

    }

    /**
     * @return int
     */
    public function getErrorsCount(): int
    {
        return $this->errorsCount;
    }

    /**
     * @return DataMapper
     */
    public function getDataMapper(): DataMapper
    {
        return $this->dataMapper;
    }

    /**
     * @return array
     */
    public function getArticles(): array
    {
        return $this->articles;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger): void
    {
        $this->logger = $logger;
    }
}