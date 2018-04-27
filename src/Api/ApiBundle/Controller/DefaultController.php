<?php

namespace Api\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;

class DefaultController extends Controller
{
    /**
     * @Rest\Route(name="_homepage_api",path="/")
     * @Rest\View()
     */

    public function indexAction()
    {
        $dm = $this->get("doctrine_mongodb")->getManager();

        $user = $this->get("doctrine_mongodb")->getRepository("AppBundle:User")->find("5ae2dd2214519b7a40003231");

        $user->setCreatedBy('5ae075cd14519bed5f0059e1');

        $dm->flush();
        $user = $this->get("doctrine_mongodb")->getRepository("AppBundle:User")->find("5ae2dd2214519b7a40003231");


        return $user;
    }
}
