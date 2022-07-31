<?php
declare(strict_types=1);

namespace App\Bootstrap;

use App\BootstrapInterface;
use App\Collections\ExportCollection;
use App\Resources\ArticleImagesResource;
use App\Resources\ArticlesResource;
use App\Resources\ArticlesTranslatedResource;
use App\Resources\CategoriesResource;
use App\Resources\CountriesResource;
use App\Resources\ImagesResource;
use App\Resources\LanguagesResource;
use App\Resources\SettingsResource;
use App\Resources\TagResource;
use App\Resources\UsersResource;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Exception;
use PhalconRest\Api;
use Psy\Exception\RuntimeException;

/**
 * Class CollectionBootstrap
 * @package App\Bootstrap
 */
class CollectionBootstrap implements BootstrapInterface
{
    /**
     * @param Api $api
     * @param DiInterface $di
     * @param Config $config
     * @throws \Psy\Exception\RuntimeException
     */
    public function run(Api $api, DiInterface $di, Config $config): void
    {
        try {
            $api
                ->collection(new ExportCollection('/export'))
                ->resource(new ArticlesResource($config->application->adapterPath . '/articles'))
                ->resource(new ArticlesTranslatedResource($config->application->adapterPath . '/articles-translated'))
                ->resource(new ArticleImagesResource($config->application->adapterPath . '/article-images'))
                ->resource(new CategoriesResource($config->application->adapterPath . '/categories'))
                ->resource(new CountriesResource($config->application->adapterPath . '/countries'))
                ->resource(new ImagesResource($config->application->adapterPath . '/images'))
                ->resource(new LanguagesResource($config->application->adapterPath . '/languages'))
                ->resource(new SettingsResource($config->application->adapterPath . '/settings'))
                ->resource(new TagResource($config->application->adapterPath . '/tag'))
                ->resource(new UsersResource($config->application->adapterPath . '/users'));
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }
}
