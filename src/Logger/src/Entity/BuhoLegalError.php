<?php
namespace Logger\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class
 *
 * @ORM\Entity
 * @ORM\Table(name="bl_errors")
 */
class BuhoLegalError
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer",name="error_id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $errorId;

    /**
     * @ORM\Column(type="string",name="type")
     */
    protected $type;

    /**
     * @ORM\Column(type="string",name="name")
     */
    protected $name;

    /**
     * @ORM\Column(type="string",name="detail")
     */
    protected $detail;

    /**
     * @ORM\Column(type="datetime",name="error_date")
     */
    protected $date;

    /**
     * @return mixed
     */
    public function getErrorId()
    {
        return $this->errorId;
    }

    /**
     * @param mixed $errorId
     */
    public function setErrorId($errorId): void
    {
        $this->errorId = $errorId;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param mixed $detail
     */
    public function setDetail($detail): void
    {
        $this->detail = $detail;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}