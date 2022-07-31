<?php

namespace App\NewParser;

use App\Model\Articles;
use App\NewParser\Interfaces\Mapped;

abstract class DataMapper
{


    /**
     * @var MappingConfig
     */
    protected $config;



    /**
     * @var array
     */
    protected $mapped;


    public function __construct(MappingConfig $config)
    {
        $this->config = $config;

    }

    /**
     * @param Articles $article
     * @return void
     */
    public function map(Articles $article): Mapped
    {
        $mapped =  new Mapped();
        $mapped->title = $this->mapTitle($article);
        $mapped->description = $this->mapDescription($article);
        $mapped->text = $this->mapText($article);

        return $mapped;
    }




    /**
     * @param Articles $article
     * @return string
     */
    abstract protected function mapTitle(Articles $article): string;

    /**
     * @param Articles $article
     * @return string
     */
    abstract protected function mapDescription(Articles $article): string;

    /**
     * @param Articles $article
     * @return string
     */
    abstract protected function mapText(Articles $article): string;
}