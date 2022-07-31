<?php
declare(strict_types=1);

namespace App\Bootstrap;

use App\BootstrapInterface;
use App\Constants\Services;
use App\Controllers\ArticlesController;
use App\Controllers\ArticlesTranslatedController;
use App\Controllers\DashboardController;
use App\Controllers\UsersController;
use App\Forms\LoginForm;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconRest\Api;

/**
 * Class RouteBootstrap
 * @package App\Bootstrap
 */
class RouteBootstrap implements BootstrapInterface
{
    /**
     * @param Api $api
     * @param DiInterface $di
     * @param Config $config
     */
    public function run(Api $api, DiInterface $di, Config $config)
    {
        $api->get('/', function () use ($api) {

            /** @var \Phalcon\Mvc\View\Simple $view */
            $view = $api->di->get(Services::VIEW);
            $form = new LoginForm();
            $form->setAction('/users/authenticate');
            $form->renderForm();

            return $view->render('general/index', ['form' => $form]);
        });

        $api->get('/proxy.html', function () use ($api, $config) {

            /** @var \Phalcon\Mvc\View\Simple $view */
            $view = $api->di->get(Services::VIEW);

            $view->setVar('client', $config->clientHostName);
            return $view->render('general/proxy');
        });

        $api->get('/documentation.html', function () use ($api, $config) {

            /** @var \Phalcon\Mvc\View\Simple $view */
            $view = $api->di->get(Services::VIEW);

            $view->setVar('title', $config->application->title);
            $view->setVar('description', $config->application->description);
            $view->setVar('documentationPath', $config->hostName . '/export/documentation.json');
            return $view->render('general/documentation');
        });
    }
}
