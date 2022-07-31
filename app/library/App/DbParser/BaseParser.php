<?php

namespace App\DbParser;

use App\Constants\Services;
use App\Model\Articles;
use App\Model\ArticleSource;
use App\Model\SourceCategory;
use Phalcon\Di\Injectable;
use RuntimeException;

abstract class BaseParser extends Injectable
{

    /**
     * @var Extractor
     */
    protected $extractor;


    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;
    }

    abstract public function parse();

    /**
     * @param array $paths
     * @param ArticleSource $source
     * @param SourceCategory|null $sourceCategory
     * @return void
     */
    protected function saveArticles(array $paths, ArticleSource $source, SourceCategory $sourceCategory = null): void
    {
        foreach ($paths as $path) {
            $html = $this->getHttpClient()->get($path);

            if ($html) {
                [$title, $description, $text, $image] = $this->extractor->extractArticlesData($source, $html);
                $data['url'] = $path;

                if ($image) {
                    $image = $this->createImage($image);
                }

                try {
                    $article = new Articles();
                    $article->setTitle($title);
                    $article->setDescription($description);
                    $article->setText($text);
                    $article->setHtml($html);
                    if ($image && is_numeric($image)) {
                        $article->setAvatar($image);
                    }

                    $article->setArticleSourceId($source->getId());
                    $article->setLanguageId($source->getLanguageId());
                    $article->setParsed(1);

                    if ($sourceCategory instanceof SourceCategory) {
                        $article->setSourceCategoryId($sourceCategory->getId());
                    }

                    $article->save();
                } catch (RuntimeException $exception) {
                    Logger::log($exception->getMessage() . ' source: ' . $source->getId() . ' path: ' . $path);
                }
            }
        }
    }

    protected function getSources()
    {
        return ArticleSource::find('parsed = 0');
    }

    protected function getCategorySources()
    {
        return SourceCategory::find('parsed = 0');
    }

    /**
     * @return mixed
     */
    protected function getHttpClient()
    {
        return $this->getDI()->get(Services::SYMPFONY_HTTP);
    }

    /**
     * @param string $path
     * @return int
     */
    private function createImage(string $path): int
    {
        return 1;
    }
}