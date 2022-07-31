<?php

namespace App\Parser\Articles;

use App\Model\Articles;
use App\Parser\Base\HttpClientAdapter;

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 21.03.21
 * Time: 13:43
 */

abstract class ArticlesDataMapper extends \App\Parser\Base\DataMapper
{

    /**
     * @return bool
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function setArticlesContent()
    {
        $articles = Articles::findFirst([
            'conditions' => ' html IS NULL OR html = "" '
        ]);

        $messages = [];

        foreach ($articles as $article) {
            if ($article instanceof Articles) {
                $client = $this->di->get($this->config->getHttpClientName());

                $adapter = new HttpClientAdapter($client);

                $content = $adapter->get($articles->getLink());

                $articles->setHtml($content);

                if (!$article->update()) {
                    $messages[] = $article->getId();
                    ArticleLogger::log('Article not parsed : ' . $article->getId() .PHP_EOL);
                }

            }
        }

        return count($messages) === 0;
    }

    /**
     * @return array
     */
    public function getPagesUrl(): array
    {
        $collection = Articles::find([
            'conditions' => ' parsed = ?1 AND link LIKE "%' . $this->config->getBaseUrl() .  '%" ',
            'bind' => [
                1 => 0
            ]
        ]);

        $urls = [];

        /** @var Articles $article */
        foreach ($collection as $article) {
            $urls[] = $article->getLink();
        }

        return $urls;
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function getContent(string $url): ?string
    {
        /** @var Articles $article */
        $article = Articles::findFirst([
            'conditions' => ' parsed = ?1 AND link = "' . $url .  '%" ',
            'bind' => [
                1 => 0
            ]
        ]);

        if ($article) {
            return $article->getHtml();
        }

        return null;
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function mapArticles(string $url): array
    {
        return [];
    }
}