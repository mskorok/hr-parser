<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Model\ArticlesTranslated;
use App\Transformers\ArticlesTranslatedTransformer;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Validation\Message\Group;

/**
 * Class ArticlesTranslatedController
 * @package App\Controllers
 */
class ArticlesTranslatedController extends ControllerBase
{

    public static $availableIncludes = [
        'Article',
        'Language'
    ];


    /*************** PROTECTED   *********************/

    /**
     * @param Builder $query
     */
    protected function modifyAllQuery(Builder $query)
    {
        $limit = $this->request->getQuery('limit');
        if (!$limit || $limit > $this->limit) {
            $query->limit($this->limit);
        }
    }

    /**
     *
     */
    protected function beforeHandle()
    {
        $this->messages = new Group();
    }

    /**
     * @param $data
     * @return mixed
     * @throws \RuntimeException
     */
    protected function onDataInvalid($data)
    {
        $mes = [];
        $mes['Post-data is invalid'];
        foreach ($this->messages as $message) {
            $mes[] = $message->getMessage();
        }

        return $this->createErrorResponse($mes);
    }

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
