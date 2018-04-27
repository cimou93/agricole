<?php
/**
 * Created by IntelliJ IDEA.
 * User: dev freeway Jouida
 * Date: 25/04/2018
 * Time: 11:54
 */

namespace Api\ApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Api\ApiBundle\Repository\ParcelleRepository")
 */
class Parcelle
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\Field(name="libelle",type="string")
     */
    protected $libelle;

    /**
     * @MongoDB\Field(name="longitude",type="float")
     */
    protected $longitude;

    /**
     * @MongoDB\Field(name="altitude",type="float")
     */
    protected $altitude;

    /**
     * @MongoDB\Field(name="superficie",type="float")
     */
    protected $superficie;

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
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * @param float $altitude
     */
    public function setAltitude($altitude): void
    {
        $this->altitude = $altitude;
    }

    /**
     * @return float
     */
    public function getSuperficie()
    {
        return $this->superficie;
    }

    /**
     * @param float $superficie
     */
    public function setSuperficie($superficie): void
    {
        $this->superficie = $superficie;
    }

    /**
     * Parcelle constructor.
     */
    public function __construct()
    {

    }
}