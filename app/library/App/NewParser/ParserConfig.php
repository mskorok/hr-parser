<?php

namespace App\NewParser\Interfaces;

use App\Constants\Services;

class ParserConfig
{
    public $startPath = 'startPath';

    public $baseUrl = 'https://arcana.pw';

    public $language = 1;



    public $config = [
        [
            'page' => 'numberPage',
            'nodePath' =>'nodeXPath',
            'isCategory' => false,
            'categoryPath' => 'categoryXPath'
        ],
        [
            'page' => 'numberPage',
            'nodePath' =>'nodeXPath',
            'isCategory' => false,
            'categoryPath' => 'categoryXPath'
        ],
        [
            'page' => 'numberPage',
            'nodePath' =>'nodeXPath',
            'isCategory' => false,
            'categoryPath' => 'categoryXPath'
        ],

    ];

    /**
     * @return string
     */
    public function getHttpClientName(): string
    {
        return Services::SYMPFONY_HTTP;
    }
}