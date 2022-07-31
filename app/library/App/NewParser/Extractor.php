<?php

namespace App\NewParser;

use App\Model\Articles;
use App\NewParser\Interfaces\Mapped;
use Phalcon\Mvc\Model\ResultsetInterface;

class Extractor
{

    /**
     * @var DataMapper
     */
    protected $mapper;

    /**
     * @var MappingConfig
     */
    public $config;

    /**
     * @var Articles|Articles[]|ResultsetInterface
     */
    protected $articles;


    public function __construct(MappingConfig $config, DataMapper $mapper)
    {
        $this->config = $config;

        $this->mapper = $mapper;

        $this->articles = $this->getMapped();
    }


    /**
     * @return void
     */
    public function extract(): void
    {
        /** @var Articles $article */
        foreach ($this->articles as $article)
        {
            /** @var Mapped $mapped */
            $mapped = $this->mapper->map($article);

            $article->setTitle($mapped->title);
            $article->setDescription($mapped->description);
            $article->setText($mapped->text);
            $article->setMapped(1);
            $article->update();
        }

        $this->articles = $this->getMapped();

    }

    /**
     * @return Articles|Articles[]|ResultsetInterface
     */
    private function getMapped()
    {

        /** @var Articles $articles */
        return Articles::find([
            'conditions' => ' mapped = ?1 AND sources = "' . $this->config->baseUrl .  '" ',
            'bind' => [
                1 => 0
            ]
        ]);
    }
}