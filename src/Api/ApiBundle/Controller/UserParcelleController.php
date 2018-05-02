<?php
/**
 * Created by IntelliJ IDEA.
 * User: dev freeway Jouida
 * Date: 27/04/2018
 * Time: 14:24
 */

namespace Api\ApiBundle\Controller;

use Api\ApiBundle\Document\UserParcelle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class UserParcelleController
 * @package Api\ApiBundle\Controller
 * @Rest\Prefix("/parcelle")
 */
class UserParcelleController extends Controller
{

    /**
     * @Rest\Route(name="_user_parcelle",path="/",methods={"GET", "POST"})
     * @Rest\View()
     */
    public function indexAction()
    {
        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //instance doctrine mongodb manager
        $dm = $this->get("doctrine_mongodb")->getManager();

        $parcelles = $dm->getRepository("ApiBundle:UserParcelle")->findBy(array("user_id" => $current_user->getId()));

        return array("statut" => "success", "message" => "succée", "users" => $parcelles);
    }

    /**
     * @Rest\Route(name="_user_parcelle",path="/show/{id}",methods={"GET", "POST"})
     * @Rest\View()
     * parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="User Id"}
     *  }
     */
    public function showAction($id)
    {
        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //instance doctrine mongodb manager
        $dm = $this->get("doctrine_mongodb")->getManager();

        $parcelles = $dm->getRepository("ApiBundle:UserParcelle")->findBy(array("user_id" => $current_user->getId(), "parcelle_id" => $id));

        return array("statut" => "success", "message" => "succée", "users" => $parcelles);

    }

    /**
     * @Rest\Route(name="_user_parcelle",path="/show-user/{id}",methods={"GET", "POST"})
     * @Rest\View()
     * parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="User Id"}
     *  }
     */
    public function showUserAction($id)
    {
        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //verifier si l'utilisateur est administrateur
        if ($current_user->hasRole("ROLE_ADMIN")) {

            //instance doctrine mongodb manager
            $dm = $this->get("doctrine_mongodb")->getManager();

            //recuperer list des utilisateur lié a la parcelle séléctionnée
            $users = $dm->getRepository("ApiBundle:UserParcelle")->findBy(array("parcelle_id" => $id));

            return array("statut" => "success", "message" => "succée", "users" => $users);

        } else {
            return array(
                "statut" => "failed",
                "message" => "vous n'avez pas le droit d'accéder à cette page"
            );
        }
    }

    /**
     * @Rest\Route(name="_user_parcelle",path="/affecter/{id_user}/{id_parcelle}",methods={"GET", "POST"})
     * @Rest\View()
     * parameters={
     *      {"name"="id_user", "dataType"="integer", "required"=true, "description"="User Id"}
     *      {"name"="id_parcelle", "dataType"="integer", "required"=true, "description"="Parcelle Id"}
     *  }
     */
    public function affecterAction($id_user,$id_parcelle)
    {
        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //verifier si l'utilisateur est administrateur
        if ($current_user->hasRole("ROLE_ADMIN")) {

            //instance doctrine mongodb manager
            $dm = $this->get("doctrine_mongodb")->getManager();

            $user = $dm->getRepository("AppBundle:User")->find($id_user);

            if (!$user)
                return array(
                    "statut" => "failed",
                    "message" => "vous n'avez pas le droit d'accéder à cette page"
                );

            if ($user->getCreatedBy() == $current_user->getId()){

                //recuperer list des utilisateur lié a la parcelle séléctionnée
                $user_parcelle = new UserParcelle();

                $user_parcelle->setUserId($user->getId());
                $user_parcelle->setParcelleId($id_parcelle);

                $dm->persist($user_parcelle);
                $dm->flush();

                return array("statut" => "success", "message" => "succée");
            }else{
                return array(
                    "statut" => "failed",
                    "message" => "vous n'avez pas le droit d'accéder à cette page"
                );
            }


        } else {
            return array(
                "statut" => "failed",
                "message" => "vous n'avez pas le droit d'accéder à cette page"
            );
        }
    }

    /**
     * @Rest\Route(name="_user_parcelle",path="/desaffecter/{id_user}/{id_parcelle}",methods={"GET", "POST"})
     * @Rest\View()
     * parameters={
     *      {"name"="id_user", "dataType"="integer", "required"=true, "description"="User Id"}
     *      {"name"="id_parcelle", "dataType"="integer", "required"=true, "description"="Parcelle Id"}
     *  }
     */
    public function desaffecterAction($id_user,$id_parcelle)
    {
        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //verifier si l'utilisateur est administrateur
        if ($current_user->hasRole("ROLE_ADMIN")) {

            //instance doctrine mongodb manager
            $dm = $this->get("doctrine_mongodb")->getManager();

            $user = $dm->getRepository("AppBundle:User")->find($id_user);

            if (!$user)
                return array(
                    "statut" => "failed",
                    "message" => "vous n'avez pas le droit d'accéder à cette page"
                );

            if ($user->getCreatedBy() == $current_user->getId()){

                //recuperer list des utilisateur lié a la parcelle séléctionnée
                $user_parcelle = $dm->getRepository("ApiBundle:UserParcelle")->findBy(array("user_id" => $id_user, "parcelle_id" => $id_parcelle));

                foreach ($user_parcelle as $userParcelle){
                    $dm->remove($userParcelle);
                }
                $dm->flush();

                return array("statut" => "success", "message" => "succée");
            }else{
                return array(
                    "statut" => "failed",
                    "message" => "vous n'avez pas le droit d'accéder à cette page"
                );
            }


        } else {
            return array(
                "statut" => "failed",
                "message" => "vous n'avez pas le droit d'accéder à cette page"
            );
        }
    }
}