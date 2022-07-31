<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 01.11.17
 * Time: 17:24
 */

namespace App\Controllers;

use App\Constants\Services;
use App\Traits\Ajax;
use App\Traits\Limit;
use App\User\Service;
use Phalcon\Mvc\Model\Query\Builder as QueryBuilder;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Validation\Message\Group;
use PhalconRest\Mvc\Controllers\CrudResourceController;

/**
 * Class ControllerBase
 * @package App\Controllers
 */
class ControllerBase extends CrudResourceController
{
    use Ajax, Limit;

    /**
     * @var array
     */
    public static $availableIncludes = [];

    /**
     * @var array
     */
    public static $searchFields = [];

    /**
     * @var array
     */
    protected $formArray = [];

    /**
     * @var Group
     */
    protected $messages;

    public function onConstruct(): void
    {
        parent::onConstruct();
        $this->messages = new Group();
    }

    /**
     * @param $id
     * @return null|mixed
     */
    protected function getFindData($id)
    {
        parent::getFindData($id);
        $phqlBuilder = $this->phqlQueryParser->fromQuery($this->query, $this->getResource());

        $phqlBuilder
            ->andWhere(
                '[' . $this->getResource()->getModel() . '].[id] = :id:',
                ['id' => (int) $id]
            )->limit(1);

        $this->modifyReadQuery($phqlBuilder);
        $this->modifyFindQuery($phqlBuilder, $id);

        /** @var Simple $results */
        $results = $phqlBuilder->getQuery()->execute();

        return \count($results) >= 1 ? $results->getFirst() : null;
    }

    /**
     * @param $error
     * @return mixed
     */
    protected function createErrorResponse($error)
    {
        $response = ['result' => 'error', 'message' => $error];

        return $this->createResponse($response);
    }

    /**
     * @param QueryBuilder $query
     */
    protected function modifyAllQuery(QueryBuilder $query)
    {
        $limit = $this->request->getQuery('limit');
        if (!$limit || $limit > $this->limit) {
            $query->limit($this->limit);
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws \RuntimeException
     */
    protected function onDataInvalid($data)
    {
        try {
            parent::onDataInvalid($data);
        } catch (\Exception $exception) {
            $this->di->get(Services::LOG)->warning($exception->getMessage());
        }
        $mes = [];
        foreach ($this->messages as $message) {
            $mes[] = $message->getMessage();
        }
        return $this->createErrorResponse($mes);
    }

    /**
     * @return bool
     * @throws \PhalconApi\Exception
     */
    protected function isAdminUser(): bool
    {
        /** @var Service $service */
        $service =  $this->userService;
        $role = $service->getRole();
        return \in_array($role, ['Superadmin', 'Admin'], true);
    }
}
