<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.11.20
 * Time: 11:45
 */

namespace App\Parser\Base;

abstract class ParserBase
{
    /**
     * @var Extractor
     */
    protected $extractor;

    /**
     * @var string 
     */
    protected $host;

    /**
     * @var array
     */
    protected $parsed;

    /**
     * @var DataMapper
     */
    protected $dataMapper;

    public function __construct(Extractor $extractor)
    {
        $this->parsed = [];
        $this->extractor = $extractor;
        $this->dataMapper = $this->extractor->getDataMapper();
        $this->host = parse_url($this->extractor->getDataMapper()->getConfig()->getBaseUrl());

    }

    abstract public function parse();

    public function check()
    {
        return get_called_class ( ) . PHP_EOL;
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function isParsed(string $path): bool
    {
        return in_array($path, $this->parsed, true);

    }

    abstract protected function addParsed(): void;

}