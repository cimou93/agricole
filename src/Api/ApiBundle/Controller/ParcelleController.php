<?php
/**
 * Created by IntelliJ IDEA.
 * User: dev freeway Jouida
 * Date: 27/04/2018
 * Time: 13:56
 */

namespace Api\ApiBundle\Controller;

use Api\ApiBundle\Document\Parcelle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ParcelleController
 * @package Api\ApiBundle\Controller
 * @Rest\Prefix("/parcelle")
 */
class ParcelleController extends Controller
{
//    /**
//     * @Rest\Route(name="_parcelle",path="/",methods={"GET", "POST"})
//     * @Rest\View()
//     */
//    public function indexAction()
//    {
//        //recuperer l'utilisateur connecté
//        $current_user = $this->getUser();
//
//        //instance doctrine mongodb manager
//        $dm = $this->get("doctrine_mongodb")->getManager();
//
//        return true;
//    }

    /**
     * @Rest\Route(name="_parcelle",path="/add",methods={"POST"})
     * @Rest\View()
     */
    public function addAction(Request $request)
    {
        //recuperer les infomation envoyer
        $libelle = $request->get("libelle");
        $longitude = $request->get("longitude");
        $altitude = $request->get("altitude");
        $superficie = $request->get("superficie");

        //verifier l'existance des information envoyer
        if (!empty($libelle) && !empty($longitude) && !empty($altitude) && !empty($superficie)){

            //recuperer l'utilisateur connecté
            $current_user = $this->getUser();

            //verfier le role de l'utilisateur connecté
            if ($current_user->hasRole("ROLE_ADMIN")){

                //instance doctrine mongodb manager
                $dm = $this->get("doctrine_mongodb")->getManager();

                $parcelle = new Parcelle();

                $parcelle->setLibelle($libelle);
                $parcelle->setLongitude(str_replace(",",".",$longitude));
                $parcelle->setAltitude(str_replace(",",".",$altitude));
                $parcelle->setSuperficie(str_replace(",",".",$superficie));

                $dm->persist($parcelle);

                //ajouter USerPArcelle

                $dm->flush();

                return array("statut" => "success", "message" => "l'utilisateur créer avec succée", "parcelle" => $parcelle);
            }else{
                return array("statut" => "failed", "message" => "vous n'avez pas le droit d'ajouter parcelle");
            }
        }else{
            return array("statut" => "failed", "message" => "vérifier les informations saisies");
        }
    }


}