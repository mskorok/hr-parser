<?php

namespace App\NewParser\Interfaces;

class Parsed
{
    /**
     * @var array
     */
    public $links;

    /**
     * @var array
     */
    public $ids;

    /**
     * @param array $links
     * @param array $ids
     */
    public function __construct(array $links, array $ids)
    {
        $this->links = $links;
        $this->ids = $ids;
    }


}