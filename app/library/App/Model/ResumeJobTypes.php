<?php
declare(strict_types=1);

namespace App\Model;

use App\Constants\Services;
use Phalcon\Mvc\Model;

/**
 * ResumeJobTypes
 * 
 * @package App\Model
 * @autogenerated by Phalcon Developer Tools
 * @date 2020-08-02, 17:49:23
 */
class ResumeJobTypes extends Model
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
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $resume_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $type_id;

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
     * Method to set the value of field resume_id
     *
     * @param integer $resume_id
     * @return $this
     */
    public function setResumeId(int $resume_id): self
    {
        $this->resume_id = $resume_id;

        return $this;
    }

    /**
     * Method to set the value of field type_id
     *
     * @param integer $type_id
     * @return $this
     */
    public function setTypeId(int $type_id): self
    {
        $this->type_id = $type_id;

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
     * Returns the value of field resume_id
     *
     * @return integer|null
     */
    public function getResumeId(): ?int
    {
        return (int)$this->resume_id;
    }

    /**
     * Returns the value of field type_id
     *
     * @return integer|null
     */
    public function getTypeId(): ?int
    {
        return (int)$this->type_id;
    }

    /**
     * Initialize method for model.
     */
    public function initialize(): void
    {
        $this->setSchema($this->getDI()->get(Services::CONFIG)->database->dbname);
        $this->setSource('resume_job_types');
        $this->belongsTo('resume_id', Resumes::class, 'id', ['alias' => 'Resumes']);
        $this->belongsTo('type_id', JobTypes::class, 'id', ['alias' => 'JobTypes']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'resume_job_types';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResumeJobTypes[]|ResumeJobTypes|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResumeJobTypes|\Phalcon\Mvc\Model\ResultInterface
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
            'resume_id' => 'resume_id',
            'type_id' => 'type_id'
        ];
    }

}