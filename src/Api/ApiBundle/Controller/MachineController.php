<?php
/**
 * Created by PhpStorm.
 * User: wissem jouida
 * Date: 01/05/2018
 * Time: 00:53
 */

namespace Api\ApiBundle\Controller;

use Api\ApiBundle\Document\Machine;
use Api\ApiBundle\Document\UserParcelle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserParcelleController
 * @package Api\ApiBundle\Controller
 * @Rest\Prefix("/machine")
 */
class MachineController extends Controller
{
    /**
     * @Rest\Route(name="_machine",path="/add",methods={"POST"})
     * @Rest\View()
     */
    public function addAction(Request $request)
    {
        //recuperer les information envoyé
        $id_parcelle = $request->get("id_parcelle");
        $reference = $request->get("reference");
        $type = $request->get("type");
        $longitude = $request->get("longitude");
        $altitude = $request->get("altitude");

        if (!empty($id_parcelle) && !empty($reference) && !empty($type) && !empty($longitude) && !empty($altitude)) {
            //recuperer l'utilisateur connecté
            $current_user = $this->getUser();

            if ($current_user->hasRole("ROLE_ADMIN")) {

                //instance doctrine mongodb manager
                $dm = $this->get("doctrine_mongodb")->getManager();

                $verif_parcelle = $dm->getRepository("ApiBundle:UserParcelle")->findOneBy(array("user_id" => $current_user->getId(), "parcelle_id" => $id_parcelle));

                if (!$verif_parcelle)
                    return array("statut" => "failed", "message" => "vous n'avez pas le droit d'ajouter machine a cette parcelle");
                else{

                    $machine = new Machine();

                    $machine->setParcelleId($id_parcelle);
                    $machine->setAltitude($altitude);
                    $machine->setLongitude($longitude);
                    $machine->setReference($reference);
                    $machine->setType($type);

                    $dm->persist($machine);
                    $dm->flush();

                    return array("statut" => "success", "message" => "success");
                }
            } else {
                return array("statut" => "f", "message" => "succée");

            }
        } else {
            return array("statut" => "f", "message" => "succée");
        }
    }

    /**
     * @Rest\Route(name="_machine",path="/show/{id}",methods={"GET"})
     * @Rest\View()
     * parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Machine Id"}
     *  }
     */
    public function ShowAction($id)
    {
        //verifier les informations envoyés
        if (!empty($id)) {

            //instance doctrin mongodb manager
            $dm = $this->get("doctrine_mongodb")->getManager();

            $machine = $dm->getRepository("ApiBundle:Machine")->find($id);

            if (!$machine){
                return array("statut" => "f", "message" => "succée");
            }

            return array("statut" => "success", "message" => "succée","machine" => $machine);
        }else{
            return array("statut" => "f", "message" => "succée");
        }
    }

    /**
     * @Rest\Route(name="_machine",path="/delete",methods={"POST"})
     * @Rest\View()
     */
    public function deleteAction(Request $request)
    {
        //recuperer l'id envoyer
        $id = $request->get("id");

        if (!empty($id)){

            //instance doctrine mongodb
            $dm = $this->get("doctrine_mongodb")->getManager();

            //recuperer la machine en question
            $machine = $dm->getRepository("ApiBundle:Machine")->find($id);

            if (!$machine)
                return array("statut" => "f", "message" => "succée");

            //delete machine
            $dm->remove($machine);
            $dm->flush();
            return array("statut" => "succée", "message" => "succée");

        }else{
            return array("statut" => "f", "message" => "succée");
        }
    }

    /**
     * @Rest\Route(name="_machine",path="/update",methods={"POST"})
     * @Rest\View()
     * parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Machine Id"}
     *  }
     */
    public function updateAction(Request $request)
    {
        //recuperer les information envoyé
        $id = $request->get("id");
        $id_parcelle = $request->get("id_parcelle");
        $reference = $request->get("reference");
        $type = $request->get("type");
        $longitude = $request->get("longitude");
        $altitude = $request->get("altitude");

        if (!empty($id_parcelle) && !empty($reference) && !empty($type) && !empty($longitude) && !empty($altitude) && !empty($id)) {
            //recuperer l'utilisateur connecté
            $current_user = $this->getUser();

            if ($current_user->hasRole("ROLE_ADMIN")) {

                //instance doctrine mongodb manager
                $dm = $this->get("doctrine_mongodb")->getManager();

                $verif_parcelle = $dm->getRepository("ApiBundle:UserParcelle")->findOneBy(array("user_id" => $current_user->getId(), "parcelle_id" => $id_parcelle));

                if (!$verif_parcelle)
                    return array("statut" => "failed", "message" => "vous n'avez pas le droit d'ajouter machine a cette parcelle");
                else{

                    $machine = $dm->getRepository("ApiBundle:Machine")->find($id);

                    $machine->setParcelleId($id_parcelle);
                    $machine->setAltitude($altitude);
                    $machine->setLongitude($longitude);
                    $machine->setReference($reference);
                    $machine->setType($type);

                    $dm->flush();

                    return array("statut" => "success", "message" => "success");
                }
            } else {
                return array("statut" => "f", "message" => "succée");

            }
        } else {
            return array("statut" => "f", "message" => "succée");
        }
    }
}