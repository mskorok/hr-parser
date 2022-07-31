<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.11.20
 * Time: 11:34
 */

namespace App\Parser\Base;

interface MappingConfigInterface
{

    public function __get($name);

    public function __set($name, $value);

    public function __isset($name);

    /**
     * @return string
     */
    public function getPageNumPrefix(): string;

    /**
     * @return string
     */
    public function getBaseUrl(): string;

    /**
     * @return string
     */
    public function getArticlesUrl(): string;

    /**
     * @return string
     */
    public function getArticleXPath(): string;

    /**
     * @return string
     */
    public function getTitleXPath(): string;

    /**
     * @return string
     */
    public function getDescriptionXPath(): string;

    /**
     * @return array
     */
    public function getParamsList(): array;

    /**
     * @return string
     */
    public function getHttpClientName(): string;

}