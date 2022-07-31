<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.11.20
 * Time: 11:34
 */

namespace App\Parser\Sites\Parse\Otomoto;

class MappingConfig extends \App\Parser\Base\MappingConfig
{
    protected $pageNumPrefix = '&page=';

    protected $baseUrl = 'http://otomoto.pl';

    protected $articlesUrl = 'http://otomoto.pl/osobowe/{mark}/?search[new_used]=used';

    protected $articleUrl = 'http://otomoto.pl/osobowe/{mark}/?search[new_used]=used';

    protected $paramsList = [];
}