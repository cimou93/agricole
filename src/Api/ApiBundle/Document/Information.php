<?php
/**
 * Created by IntelliJ IDEA.
 * User: dev freeway Jouida
 * Date: 25/04/2018
 * Time: 16:10
 */

namespace Api\ApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Api\ApiBundle\Repository\InformationRepository")
 */
class Information
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Api\ApiBundle\Document\Machine",storeAs="reference")
     */
    protected $reference;

    /**
     * @MongoDB\Field(name="tempurature_aire",type="float")
     */
    protected $tempurature_aire;

    /**
     * @MongoDB\Field(name="tempurature_sol",type="float")
     */
    protected $tempurature_sol;

    /**
     * @MongoDB\Field(name="humidite_aire",type="float")
     */
    protected $humidite_aire;

    /**
     * @MongoDB\Field(name="humidite_sol",type="float")
     */
    protected $humidite_sol;

    /**
     * @MongoDB\Field(name="vitesse_vente",type="float")
     */
    protected $vitesse_vente;

    /**
     * @MongoDB\Field(name="nature_sol",type="string")
     */
    protected $nature_sol;

    /**
     * @MongoDB\Field(name="CO2",type="float")
     */
    protected $CO2;

    /**
     * @MongoDB\Field(name="date",type="string")
     */
    protected $date;
}