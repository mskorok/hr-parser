<?php

namespace App\NewParser;

class ParseResult
{
    /**
     * @var string
     */
    public $node;

    /**
     * @var array;
     */
    public $category;

    /**
     * @var string
     */
    public $html;

    /**
     * @param string $node
     * @param array $category
     * @param string $html
     */
    public function __construct( string $node, array $category = [], string $html = '')
    {
        $this->node = $node;
        $this->category = $category;
        $this->html = $html;
    }


}