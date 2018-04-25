<?php
/**
 * Created by IntelliJ IDEA.
 * User: dev freeway Jouida
 * Date: 25/04/2018
 * Time: 13:42
 */

namespace Api\ApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class UserParcelle
{

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="AppBundle\Document\User",storeAs="id")
     */
    protected $user_id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Api\ApiBundle\Document\Parcelle",storeAs="id")
     */
    protected $parcelle_id;

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
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
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
     * UserParcelle constructor.
     */
    public function __construct()
    {

    }

}