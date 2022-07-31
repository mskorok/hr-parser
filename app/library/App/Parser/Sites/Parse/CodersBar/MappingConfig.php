<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 06.03.21
 * Time: 20:34
 */

namespace App\Parser\Sites\Parse\CodersBar;

class MappingConfig extends \App\Parser\Base\MappingConfig
{

    protected $pageNumPrefix;

    protected $baseUrl = 'https://coders.bar';

    protected $articlesUrl;

    protected $articleXPath = '';

    protected $titleXPath = '';

    protected $descriptionXPath = '';

    protected $paramsList = [];
}