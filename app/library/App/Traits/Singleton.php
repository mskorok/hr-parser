<?php

namespace App\Traits;

/**
 * Singleton
 *
 * @package  Engine\Traits
 *
 */
trait Singleton
{
    /**
     * @var self
     */
    protected static $instance;

    /**
     * Запрещен уровнем доступа
     * @return void
     */
    public function __construct() {
        static::setInstance($this);
    }

    /**
     * getInstance
     *
     * @return static
     *
     */
    final public static function getInstance()
    {
        return static::$instance ?? (static::$instance = new static);
    }

    /**
     * Запрещен уровнем доступа
     */
    protected function __wakeup()
    {

    }

    /**
     * Запрещен уровнем доступа
     */
    protected function __clone()
    {

    }

    /**
     * @param self $instance
     */
    final public static function setInstance(self $instance)
    {
        self::$instance = $instance;
    }
}
