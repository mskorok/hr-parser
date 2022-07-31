<?php
declare(strict_types=1);

namespace App\Controllers;
 
use App\Constants\Limits;
use App\Constants\Services;
use App\Forms\ArticlesForm;
use App\Model\Articles;
use App\Model\ArticleTag;
use App\Model\Images;
use App\Model\Tag;
use App\Traits\RenderView;
use App\Validators\ArticlesValidator;
use App\Validators\ImagesValidator;
use Phalcon\Http\Response;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Validation\Message\Group;
use Phalcon\Paginator\Adapter\QueryBuilder;

/**
 * Class ArticlesController
 * @package App\Controllers
 */
class ArticlesController extends ControllerBase
{
    use RenderView;

    public static $availableIncludes = [
        'ArticleImages',
        'Images'
    ];

    /**
     * @param $key
     * @param $value
     * @param $data
     * @return mixed
     */
    protected function transformPostDataValue($key, $value, $data)
    {
        $fields = ['text', 'title', 'description'];
        if (in_array($key, $fields, true)) {
            $value = htmlspecialchars($value);
        }
        return parent::transformPostDataValue($key, $value, $data);
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function getFindResponse($item)
    {
        if (property_exists($item, 'text')) {
            $item->text = html_entity_decode($item->text);
        }

        if (property_exists($item, 'title')) {
            $item->title = html_entity_decode($item->title);
        }

        if (property_exists($item, 'description')) {
            $item->description = html_entity_decode($item->description);
        }
        return parent::getFindResponse($item);
    }
}
