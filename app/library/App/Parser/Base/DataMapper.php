<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.11.20
 * Time: 14:25
 */

namespace App\Parser\Base;

use App\Model\Articles;

/**
 * Class DataMapper
 * @package App\Parser\Base
 */
abstract class DataMapper extends \Phalcon\Di\Injectable
{
    protected $mappedFields = [];

    /**
     * @var MappingConfigInterface
     */
    protected $config;

    /**
     * DataMapper constructor.
     * @param MappingConfigInterface $config
     */
    public function __construct(MappingConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param $url
     * @return array
     */
    abstract public function mapArticles(string $url): array;

    /**
     * @param string $content
     * @return Articles | null
     */
    abstract public function map(string $content): ?Articles;

    /**
     * @return mixed
     */
    abstract public function mapCategory();

    /**
     * @return array
     */
    abstract public function getPagesUrl(): array;

    /**
     * @param string $url
     * @return string|null
     */
    abstract public function getContent(string $url): ?string;

    /**
     * @param string$content
     * @param null $url
     * @return AppCrawler
     */
    protected function getCrawler(string $content, $url = null)
    {
        return new AppCrawler($content, $url, $this->getConfig()->getBaseUrl());
    }

    /**
     * @return MappingConfigInterface
     */
    public function getConfig(): MappingConfigInterface
    {
        return $this->config;
    }
}