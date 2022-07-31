<?php

namespace App\DbParser;

use App\Constants\Services;
use App\Model\Articles;
use App\Model\ArticleSource;
use App\Model\Images;
use App\Model\SourceCategory;
use Phalcon\Di\Injectable;
use RuntimeException;

abstract class BaseParser extends Injectable
{

    /**
     * @var Extractor
     */
    protected $extractor;

    private static $upload = '/upload/articles/';


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
     * @return int|null
     */
    private function createImage(string $path): ?int
    {
        $fileName = basename($path);
        $pathName = self::$upload;
        $data = file_get_contents($path);
        file_put_contents(self::$upload . $fileName, $data);
        $image = new Images();
        $image->setPath($pathName);
        $image->setFileName($fileName);
        try {
            $image->save();
            $image->refresh();

            return $image->getId();
        } catch (RuntimeException $e) {
            Logger::log($e->getMessage());
            return null;
        }
    }
}