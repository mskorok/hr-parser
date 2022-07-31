<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 21.03.21
 * Time: 13:38
 */

namespace App\Parser\Articles\Parse\CodersBar;


use App\Model\Articles;
use App\Parser\Articles\ArticlesDataMapper;

class DataMapper extends ArticlesDataMapper
{



    /**
     * @param string $content
     * @return Articles|null
     */
    public function map(string $content): ?Articles
    {
        // TODO: Implement map() method.
        return null;
    }

    /**
     * @return mixed
     */
    public function mapCategory()
    {
        // TODO: Implement mapCategory() method.
    }
}