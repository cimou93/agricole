<?php

namespace Api\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;

class DefaultController extends Controller
{
    /**
     * @Rest\View()
     */

    public function indexAction()
    {

        $user = $this->get('doctrine_mongodb')
            ->getRepository('AppBundle:User')
            ->findAll();
        return $user;
    }
}
