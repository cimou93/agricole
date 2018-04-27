<?php
/**
 * Created by IntelliJ IDEA.
 * User: dev freeway Jouida
 * Date: 25/04/2018
 * Time: 15:43
 */

namespace Api\ApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Api\ApiBundle\Repository\MachineRepository")
 */
class Machine
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Api\ApiBundle\Document\Parcelle",storeAs="id")
     */
    protected $parcelle_id;

    /**
     * @MongoDB\Field(name="reference",type="string")
     */
    protected $reference;

    /**
     * @MongoDB\Field(name="type",type="string")
     */
    protected $type;

    /**
     * @MongoDB\Field(name="longitude",type="float")
     */
    protected $longitude;

    /**
     * @MongoDB\Field(name="altitude",type="float")
     */
    protected $altitude;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getParcelleId()
    {
        return $this->parcelle_id;
    }

    /**
     * @param mixed $parcelle_id
     */
    public function setParcelleId($parcelle_id): void
    {
        $this->parcelle_id = $parcelle_id;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference): void
    {
        $this->reference = $reference;
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
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * @param mixed $altitude
     */
    public function setAltitude($altitude): void
    {
        $this->altitude = $altitude;
    }

    /**
     * Machine constructor.
     */
    public function __construct()
    {

    }
}