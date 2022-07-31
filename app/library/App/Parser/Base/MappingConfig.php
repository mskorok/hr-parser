<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 04.04.21
 * Time: 15:13
 */

namespace App\Parser\Base;


use App\Constants\Services;
use App\Traits\Singleton;

abstract class MappingConfig implements MappingConfigInterface
{
    use Singleton;

    protected $pageNumPrefix;

    protected $baseUrl = '';

    protected $articlesUrl = '';

    protected $articleXPath = '';

    protected $titleXPath = '';

    protected $descriptionXPath = '';

    protected $paramsList = [];
    /**
     * @return string
     */
    public function getHttpClientName(): string
    {
        return Services::SYMPFONY_HTTP;
    }

    /**
     * @return string
     */
    public function getPageNumPrefix(): string
    {
        return $this->pageNumPrefix;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getArticlesUrl(): string
    {
        return $this->articlesUrl;
    }

    /**
     * @return string
     */
    public function getArticleXPath(): string
    {
        return $this->articleXPath;
    }

    /**
     * @return string
     */
    public function getTitleXPath(): string
    {
        return $this->titleXPath;
    }

    /**
     * @return string
     */
    public function getDescriptionXPath(): string
    {
        return $this->descriptionXPath;
    }


    /**
     * @return array
     */
    public function getParamsList(): array
    {
        return $this->paramsList;
    }

    public function __get($name)
    {
        $method = 'get' . ucwords($name);

        if (method_exists($this, $method)) {
            $this->{$method};
        }
    }

    public function __set($name, $value)
    {
        //
    }

    public function __isset($name)
    {
        //
    }

}