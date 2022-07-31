<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 21.03.21
 * Time: 13:41
 */

namespace App\Parser\Articles\Parse\Habr;

class MappingConfig extends \App\Parser\Base\MappingConfig
{
    protected $pageNumPrefix;

    protected $baseUrl = 'https://habr.com';

    protected $articlesUrl;

    protected $articleXPath = '';

    protected $titleXPath = '';

    protected $descriptionXPath = '';

    protected $paramsList = [];
}