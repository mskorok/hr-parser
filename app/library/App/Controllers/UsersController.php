<?php
declare(strict_types=1);

namespace App\Controllers;
 
use App\Auth\UsernameAccountType;
use App\Constants\Services;
use App\Model\Images;
use App\Traits\RenderView;
use App\Transformers\UsersTransformer;
use App\Validators\ImagesValidator;
use App\Validators\UsersValidator;
use Phalcon\Mvc\Model;
use App\Model\Users;
use Phalcon\Validation\Message;
use PhalconApi\Constants\PostedDataMethods;
use PhalconApi\Exception;
use Phalcon\Mvc\Model\Query\Builder as QueryBuilder;
use Phalcon\Validation\Message\Group;

/**
 * Class UsersController
 * @package App\Controllers
 */
class UsersController extends ControllerBase
{

    use RenderView;

    public static $availableIncludes = [
        'Images'
    ];

    /**
     * @var bool
     */
    protected $createMode = false;
    /**
     * @return mixed
     *
     */
    public function me()
    {
        try {
            $me = $this->userService->getDetails();
            return $this->createResourceResponse($me);
        } catch (Exception $exception) {
            return $this->createErrorResponse($exception->getMessage());
        }
    }

    /**
     * @return mixed
     * @throws \RuntimeException
     */
    public function authenticate()
    {
        $username = $this->request->getUsername();
        $password = $this->request->getPassword();

        try {
            $session = $this->authManager
                ->loginWithUsernamePassword(UsernameAccountType::NAME, $username, $password);
        } catch (Exception $exception) {
            throw new \RuntimeException($exception->getMessage());
        }

        $transformer = new UsersTransformer();
        $transformer->setModelClass(Users::class);

        /** @var Users $user */
        $user = Users::findFirst($session->getIdentity());
        if ($user instanceof Users) {
            $user->setLastLoginDate(date('Y-m-d H:i:s'));
            $user->save();
        }

        $role = $user->getRole();

        $response = [
            'token' => $session->getToken(),
            'expires' => $session->getExpirationTime(),
            'user' => $user,
            'avatar' => $user->getImages(),
            'role' => $role
        ];

        return $this->createArrayResponse($response, 'data');
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        // Destroy the whole session
        $this->session->destroy();
        return $this->createOkResponse();
    }






    /***** PROTECTED  ******/

    /**
     * @param $data
     * @return array
     * @throws \RuntimeException
     */
    protected function transformPostData($data)
    {
        if (!isset($data['id'])) {
            $data['id'] = '';
        }

        $this->messages = new Group;
        $config = $this->getDI()->get(Services::CONFIG);
        $image = null;
        if ($this->request->hasFiles(true)) {
            $uploadDir = $config->application->uploadDir;

            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755) && !is_dir($uploadDir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $uploadDir));
            }
            /** @var \Phalcon\Http\Request\File $file */
            foreach ($this->request->getUploadedFiles(true) as $file) {
                if ($file->getKey() === 'fileName') {
                    $fileName = uniqid('User_' . date('Y-m-d') . '_', false);
                    $fileName .= '.' . $file->getExtension();
                    try {
                        $file->moveTo($uploadDir . $fileName);
                        $image = new Images();
                        $image->setFileName($fileName);
                        $image->setPath('/uploads/');
                        $params = $image->toArray();
                        $imageValidator = new ImagesValidator();
                        $res = $imageValidator->validate($params);
                        if ($res->count() === 0) {
                            $image->save();
                        } else {
                            $this->messages = $imageValidator->getMessages();
                        }
                    } catch (\RuntimeException $exception) {
                        $message = new Message($exception->getMessage());
                        if (!($this->messages instanceof Group)) {
                            $this->messages = new Group;
                            $this->messages->appendMessage($message);
                        }
                    }
                }
            }
        }

        unset($data['fileName']);
        if ($image instanceof Images) {
            $data['avatar'] = $image->getId();
        }

        if ($this->messages->count() > 0) {
            $messages = '';
            foreach ($this->messages as $message) {
                $messages .= $message->getMessage().PHP_EOL;
            }
            throw new \RuntimeException($messages);
        }

        return parent::transformPostData($data);
    }

    /**
     * @param $id
     * @return null|Model
     */
    protected function getItem($id)
    {
        $user = Users::findFirst((int)$id);
        if ($user instanceof Users && $user->getEmailConfirmed() === 1) {
            return $user;
        }
        return null;
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

        $query->andWhere('emailConfirmed =  1');
    }

    /**
     * @param QueryBuilder $query
     * @param $id
     */
    protected function modifyFindQuery(QueryBuilder $query, $id)
    {
        if (!$this->createMode) {
            $query->andWhere('emailConfirmed =  1');
        }
        $this->createMode = false;
    }

    /**
     *
     */
    protected function beforeHandle()
    {
        $this->messages = new Group();
    }

    /**
     *
     */
    protected function beforeHandleCreate()
    {
        $this->createMode = true;
        $resource = $this->getResource();
        $resource->postedDataMethod(PostedDataMethods::POST);
    }

    /**
     * @param Model $item
     * @param $data
     */
    protected function beforeAssignData(Model $item, $data)
    {
        /** @var Users $user */
        $user = $item;
        if (isset($data['password'])) {
            $user->setPassword($this->security->hash($data['password']));
        }
        unset($data['confirmPassword'], $data['password']);
        if ($user->getId()) {
            $user->beforeUpdate();
        } else {
            $user->setToken(self::random(40));
            $user->beforeCreate();
            $user->setEmailConfirmed(0);
        }
    }

    /**
     * @param $data
     * @param $isUpdate
     * @return bool
     */
    protected function postDataValid($data, $isUpdate): bool
    {
        $params = $data;
        if (isset($params['fileName'])) {
            unset($params['fileName']);
        }
        $validator = new UsersValidator();
        $res = $validator->validate($params);
        $this->messages = $validator->getMessages();
        if ($res->count() !== 0) {
            return false;
        }
        return $res->count() === 0;
    }

    /**
     * @param $id
     * @throws \RuntimeException
     * @throws Exception
     */
    protected function beforeHandleRemove($id)
    {
        $admin = $this->isAdminUser();
        if (!$admin) {
            throw new \RuntimeException('Only admin has permission to remove User');
        }
    }
}
