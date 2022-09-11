<?php

namespace App\Parser\Sites;

use App\Model\Articles;
use App\Model\ArticlesSource;
use App\Parser\Base\HttpClientAdapter;

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 21.03.21
 * Time: 13:43
 */

abstract class SitesDataMapper extends \App\Parser\Base\DataMapper
{
    /**
     * @return bool
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function setSiteContent(): bool
    {
        $source = ArticlesSource::findFirst([
            'conditions' => ' link LIKE "%' . $this->config->getBaseUrl() .  '%" '
        ]);

        if ($source instanceof ArticlesSource) {
            $client = $this->di->get($this->config->getHttpClientName());

            $adapter = new HttpClientAdapter($client);

            $content = $adapter->get($source->getLink());

            $source->setHtml($content);

            if ($source->update()) {
                return true;
            }

        }

        return false;
    }

    /**
     * @return array
     */
    public function getPagesUrl(): array
    {

        return [];
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function getContent(string $url): ?string
    {
        /** @var ArticlesSource $article */
        $article = ArticlesSource::findFirst([
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
     * @param string $content
     * @return Articles|null
     */
    public function map(string $content): ?Articles
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function mapCategory()
    {
        return null;
    }
}