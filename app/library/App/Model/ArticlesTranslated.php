<?php
declare(strict_types=1);

namespace App\Model;
use App\Constants\Services;
use Phalcon\Mvc\Model;

/**
 * ArticlesTranslated
 * 
 * @package App\Model
 * @autogenerated by Phalcon Developer Tools
 * @date 2020-09-26, 07:47:16
 * @method Articles getArticle
 * @method Languages getLanguage
 */
class ArticlesTranslated extends Model
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
    protected $title;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $description;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $text;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $article_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $language_id;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Method to set the value of field text
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Method to set the value of field language_id
     *
     * @param integer $language_id
     * @return $this
     */
    public function setLanguageId(int $language_id): self
    {
        $this->language_id = $language_id;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return (int)$this->id;
    }

    /**
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Returns the value of field text
     *
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Returns the value of field language_id
     *
     * @return integer|null
     */
    public function getLanguageId(): ?int
    {
        return (int)$this->language_id;
    }

    /**
     * @return int|null
     */
    public function getArticleId(): ?int
    {
        return (int)$this->article_id;
    }

    /**
     * @param int $article_id
     */
    public function setArticleId(int $article_id): void
    {
        $this->article_id = $article_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize(): void
    {
        $this->setSchema($this->getDI()->get(Services::CONFIG)->database->dbname);
        $this->setSource('articles_translated');
        $this->belongsTo('article_id', Articles::class, 'id', ['alias' => 'Article']);
        $this->belongsTo('language_id', Languages::class, 'id', ['alias' => 'Language']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'articles_translated';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ArticlesTranslated[]|ArticlesTranslated|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ArticlesTranslated|\Phalcon\Mvc\Model\ResultInterface
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
        return [
            'id' => 'id',
            'title' => 'title',
            'description' => 'description',
            'text' => 'text',
            'article_id' => 'article_id',
            'language_id' => 'language_id'
        ];
    }

}