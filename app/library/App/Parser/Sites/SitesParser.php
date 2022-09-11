<?php
namespace App\Parser\Sites;

use App\Model\ArticlesSource;
use Phalcon\Mvc\Model\ResultSetInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


/**
 * Created by PhpStorm.
 * User: mike
 * Date: 21.03.21
 * Time: 13:39
 */

class SitesParser extends \App\Parser\Base\ParserBase
{
    /**
     * @var SitesDataMapper
     */
    protected $dataMapper;

    public function parse()
    {
        $this->addParsed();
        $collection  = $this->getSource(false);

        /** @var ArticlesSource $source */
        foreach ($collection as $source) {
            if ($this->isParsed($source->getLink())) {
                continue;
            }

            $this->extractor->extractLink($source);

            sleep(1);
        }
    }

    protected function addParsed(): void
    {
        $parsed = $this->getSource(true);
        /** @var ArticlesSource $source */
        foreach ($parsed as $source) {
            if (!$this->isParsed($source->getLink())) {
                $this->parsed[] = $source->getLink();
            }
        }
    }

    /**
     * @param $parsed
     * @return ArticlesSource|ArticlesSource[]|ResultSetInterface
     */
    protected function getSource($parsed)
    {
        $parsed = (int) $parsed;
        return ArticlesSource::find([
            'conditions' => ' parsed = ?1 AND link LIKE "%' . $this->host .  '%" ',
            'bind' => [
                1 => $parsed
            ]
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function setSiteContent()
    {
        $this->dataMapper->setSiteContent();
    }
}