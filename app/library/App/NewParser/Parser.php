<?php

namespace App\NewParser;

use App\Model\Articles;
use App\NewParser\Interfaces\Parsed;
use App\NewParser\Interfaces\ParserConfig;
use App\Parser\Base\AppCrawler;

abstract class Parser
{
    /**
     * @var Parsed
     */
    protected $parsed = [];

    /**
     * @var array
     */
    protected $rewrite = [];
    /**
     * @var ParserConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $result;


    public function __construct(ParserConfig $config)
    {
        $this->config = $config;
        $this->parsed = $this->getParsed();
        $this->rewrite = $this->getParsed(0);
    }


    public function run(): void
    {
        $this->result = $this->parse();

        $this->writeResult();

    }

    public function parse(): array
    {
        $path = $this->config->startPath;
        $configs = $this->config->config;

        $paths = new ParseResult($path);

        foreach ($configs as $config) {
            $paths = $this->parseNodes($paths, $config);
        }

        return $paths;
    }


    /**
     * @param string $node
     * @param array $config
     * @return array
     */
    abstract public function parseNode(string $node, array $config): array;

    /**
     * @param array $paths
     * @param array $config
     * @return array
     */
    public function parseNodes(array $paths, array $config): array
    {
        $res = [];
        /** @var ParseResult $path */
        foreach ($paths as $path) {
            $category = $path->category;
            $node = $path->node;
            if (in_array($node, $this->parsed['links'], true)) {
                continue;
            }
            $results = $this->parseNode($node, $config);
            /** @var ParseResult $result */
            foreach ($results as $result) {
                $cat = [
                    implode('_', $category) => $result->category
                ];
                $res[] = new ParseResult($result->node, $cat, $result->html);
            }
        }

        return $res;
    }

    /**
     * @param int $mode
     * @return Parsed
     */
    private function getParsed(int $mode = 1): Parsed
    {

        /** @var Articles $articles */
        $articles = Articles::find([
            'conditions' => ' parsed = ?1 AND sources = "' . $this->config->baseUrl .  '" ',
            'bind' => [
                1 => $mode
            ]
        ]);

        $links = [];
        $ids = [];

        /** @var Articles $article */
        foreach ($articles as $article) {
            $links[] = $article->getLink();
            $ids[$article->getLink()] = $article->getId();
        }

        return new Parsed($links, $ids);
    }

    protected function writeResult()
    {
        /** @var ParseResult $item */
        foreach ($this->result as $item) {
            $path = $item->node;
            if (isset($this->parsed->ids[$path])) {
                $article = Articles::findFirst((int) $this->parsed->ids[$path]);
                if ($article instanceof Articles) {
                    $article->setHtml($item->html);
                    $article->setCategoryId($this->transformCategory($item->category));
                    $article->setMapped(0);
                    $article->setParsed(1);
                    $article->update();
                }
            } else {
                $article = new Articles();
                $article->setSources($this->config->baseUrl);
                $article->setHtml($item->html);
                $article->setLink($item->node);
                $article->setCategoryId($this->transformCategory($item->category));
                $article->setMapped(0);
                $article->setParsed(1);
                $article->setLanguageId($this->config->language);
            }
        }

    }

    /**
     * @param array $category
     * @return string
     */
    abstract protected function transformCategory(array $category): string;

    /**
     * @param string $content
     * @param string|null $url
     * @param string|null $baseUrl
     * @return AppCrawler
     */
    protected function getCrawler(string $content, string $url = null, string $baseUrl = null)
    {
        return new AppCrawler($content, $url, $baseUrl);
    }

}