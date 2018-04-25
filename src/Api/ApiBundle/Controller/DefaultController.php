<?php

namespace Api\ApiBundle\Controller;

use Api\ApiBundle\Document\Parcelle;
use Api\ApiBundle\Document\UserParcelle;
use AppBundle\Document\User;
use DateTime;
use Monolog\Handler\UdpSocketTest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Rest\Route(name="_homepage_api",path="/")
     * @Rest\View()
     */

    public function indexAction()
    {
//        $dm = $this->get("doctrine_mongodb")->getManager();
//
//        $user = $this->get("doctrine_mongodb")->getRepository("AppBundle:User")->findAll();
//        $parcelles = $this->get("doctrine_mongodb")->getRepository("ApiBundle:Parcelle")->findAll();
//
//        foreach ($parcelles as $parcelle) {
//            $user_parcelle = new UserParcelle();
//            $user_parcelle->setParcelleId($parcelle);
//            $user_parcelle->setUserId($user[0]);
////            $dm->persist($user_parcelle);
////
////            $dm->flush();
//        }

        $user_parcelles =$this->get("doctrine_mongodb")->getRepository("ApiBundle:UserParcelle")->findAll();

        return $user_parcelles;
    }
}
