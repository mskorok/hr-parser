<?php
declare(strict_types=1);

namespace App\Model;

use App\Constants\Services;

/**
 * ArticlesSource
 *
 * @package App\Model
 * @autogenerated by Phalcon Developer Tools
 * @date 2021-03-21, 12:59:30
 */
class ArticlesSource extends DateTrackingModel implements ParsedInterface
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $host;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $language_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $link;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $html;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    protected $parsed;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $creationDate;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $modifiedDate;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field host
     *
     * @param string $host
     * @return $this
     */
    public function setHost($host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Method to set the value of field language_id
     *
     * @param integer $language_id
     * @return $this
     */
    public function setLanguageId($language_id): self
    {
        $this->language_id = $language_id;

        return $this;
    }

    /**
     * Method to set the value of field link
     *
     * @param string $link
     * @return $this
     */
    public function setLink($link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Method to set the value of field html
     *
     * @param string $html
     * @return $this
     */
    public function setHtml($html): self
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Method to set the value of field parsed
     *
     * @param integer $parsed
     * @return $this
     */
    public function setParsed($parsed): self
    {
        $this->parsed = $parsed;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the value of field host
     *
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * Returns the value of field language_id
     *
     * @return integer|null
     */
    public function getLanguageId(): ?int
    {
        return $this->language_id;
    }

    /**
     * Returns the value of field link
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Returns the value of field html
     *
     * @return string|null
     */
    public function getHtml(): ?string
    {
        return $this->html;
    }

    /**
     * Returns the value of field parsed
     *
     * @return integer|null
     */
    public function getParsed(): ?int
    {
        return $this->parsed;
    }

    /**
     * Initialize method for model.
     */
    public function initialize(): void
    {
        $this->setSchema($this->getDI()->get(Services::CONFIG)->database->dbname);
        $this->setSource('articles_source');
        $this->belongsTo('language_id', Languages::class, 'id', ['alias' => 'Language']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'articles_source';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ArticlesSource[]|ArticlesSource|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ArticlesSource|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap(): array
    {
        return parent::columnMap() + [
                'id' => 'id',
                'host' => 'host',
                'language_id' => 'language_id',
                'link' => 'link',
                'html' => 'html',
                'parsed' => 'parsed'
            ];
    }
}