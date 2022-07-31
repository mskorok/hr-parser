<?php

namespace App\Parser\Articles\Parse\Habr;

use App\Model\Articles;
use App\Parser\Articles\ArticlesDataMapper;

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 21.03.21
 * Time: 13:43
 */

class DataMapper extends ArticlesDataMapper
{

    /**
     * @param string $content
     * @return Articles | null
     */
    public function map(string $content): ?Articles
    {
        // TODO: Implement map() method.
    }

    /**
     * @return mixed
     */
    public function mapCategory()
    {
        // TODO: Implement mapCategory() method.
    }
}