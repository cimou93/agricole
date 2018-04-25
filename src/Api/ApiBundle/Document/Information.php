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
 * @MongoDB\Document
 */
class Information
{
    protected $id;

    protected $reference;
}