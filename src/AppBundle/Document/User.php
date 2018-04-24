<?php
/**
 * Created by PhpStorm.
 * User: wissem jouida
 * Date: 19/04/2018
 * Time: 22:41
 */

// src/AppBundle/Document/User.php

namespace AppBundle\Document;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}