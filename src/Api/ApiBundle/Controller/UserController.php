<?php
/**
 * Created by IntelliJ IDEA.
 * User: dev freeway Jouida
 * Date: 27/04/2018
 * Time: 08:54
 */

namespace Api\ApiBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package Api\ApiBundle\Controller
 * @Rest\Prefix("/user")
 */
class UserController extends Controller
{
    /**
     * @Rest\Route(name="_user",path="/",methods={"GET", "POST"})
     * @Rest\View()
     */
    public function indexAction()
    {
        //instance doctrine mongodb manager
        $dm = $this->get("doctrine_mongodb")->getManager();

        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //return les utilisateur creer pas l'utilisateur connecté
        $users = $dm->getRepository("AppBundle:User")->GetUserForAdmin($current_user->getId());

        return array("statut" => "success", "message" => "succée", "users" => $users);

    }

    /**
     * @Rest\Route(name="_user",path="/add",methods={"POST"})
     * @Rest\View()
     */
    public function addAction(Request $request)
    {
        //recuperer les infomation saisies
        $username = $request->get("username");
        $email = $request->get("email");
        $password = $request->get("password");
        $roles = $request->get("roles");

        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //verifier le role de l'utilisateur connecté
        if ($current_user->hasRole("ROLE_ADMIN")) {
            //verifier les informations envoyés
            if (!empty($username) && !empty($email) && !empty($password) && !empty($roles)) {
                //instance doctrin mongodb manager
                $dm = $this->get("doctrine_mongodb")->getManager();
                //verifier si l'email et username existe
                $verif_user = $dm->getRepository("AppBundle:User")->verifierUser($email, $username);

                if ($verif_user) {
                    //instance manipulateur FOSUSER
                    $manipulator = $this->container->get('fos_user.util.user_manipulator');

                    //creation de l'utilisateur
                    $user = $manipulator->create($username, $password, $email, true, false);

                    //ajouter les roles de l'utilisateur creé
                    foreach ($roles as $role) {
                        $manipulator->addRole($user->getUsername(), $role);
                    }

                    //recuperer l'utilisateur crée
                    $user = $dm->getRepository("AppBundle:User")->find($user->getId());

                    //lié l'utilisateur par l'administrateur connecté
                    $user->setCreatedBy($current_user->getId());

                    //update bdd mongo
                    $dm->flush();

                    //success
                    return array("statut" => "success", "message" => "l'utilisateur créer avec succée", "user" => $user);
                } else {
                    //failed
                    return array("statut" => "failed", "message" => "l'email ou username existe");
                }
            } else {
                //failed
                return array("statut" => "failed", "message" => "vérifier les informations saisies");
            }
        } else {
            //failed
            return array("statut" => "failed", "message" => "Vous n'avez pas le droit de creer un utilisateur");
        }


    }

    /**
     * @Rest\Route(name="_user",path="/show/{id}",methods={"Get"},requirements={"id" = "\d+"})
     * @Rest\View()
     * parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="User Id"}
     *  }
     * @param $id
     * @return array
     */
    public function showAction($id)
    {

        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //verifier les informations envoyés
        if (!empty($id)) {

            //instance doctrin mongodb manager
            $dm = $this->get("doctrine_mongodb")->getManager();

            //verifier le role de l'utilisateur connecté
            if ($current_user->getId() == $id) {

                //return utilisateur séléctionné
                return array("statut" => "success", "message" => "l'utilisateur séléctionner", "user" => $current_user);

            } elseif ($current_user->hasRole("ROLE_ADMIN")) {
                //recuperer l'utilisateur en question
                $user = $dm->getRepository("AppBundle:User")->find($id);
                if (!$user) {
                    //failed
                    return array("statut" => "failed", "message" => "aucun utilisateur séléctionné");
                } else {
                    if ($user->getCreatedBy() == $current_user->getId()) {
                        //return utilisateur séléctionné
                        return array("statut" => "success", "message" => "l'utilisateur séléctionner", "user" => $user);
                    } else {
                        //Failed
                        return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
                    }
                }

            } else {
                //failed
                return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
            }
        } else {
            //failed
            return array("statut" => "failed", "message" => "vérifier les informations saisies");
        }
    }

    /**
     * @Rest\Route(name="_user",path="/delete",methods={"POST"})
     * @Rest\View()
     */
    public function deleteAction(Request $request)
    {
        //recuperer les infomation saisies
        $id = $request->get("id");

        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //verifier les informations envoyés
        if (!empty($id)) {

            //instance doctrin mongodb manager
            $dm = $this->get("doctrine_mongodb")->getManager();

            //verifier le role de l'utilisateur connecté
            if ($current_user->hasRole("ROLE_ADMIN")) {
                //recuperer l'utilisateur en question
                $user = $dm->getRepository("AppBundle:User")->find($id);

                if (!$user) {
                    //failed
                    return array("statut" => "failed", "message" => "aucun utilisateur séléctionné");
                } else {
                    if ($user->getCreatedBy() == $current_user->getId()) {
                        //delete utilisateur
                        $dm->remove($user);

                        //update bdd
                        $dm->flush();
                        //return utilisateur séléctionné
                        return array("statut" => "success", "message" => "l'utilisateur séléctionner est supprimé avec succé");
                    } else {
                        //Failed
                        return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
                    }
                }

            } else {
                //failed
                return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
            }
        } else {
            //failed
            return array("statut" => "failed", "message" => "vérifier les informations saisies");
        }
    }

    /**
     * @Rest\Route(name="_user",path="/add-role",methods={"POST"})
     * @Rest\View()
     */
    public function addRoleAction(Request $request)
    {
        //recuperer les infomation saisies
        $id = $request->get("id");
        $roles = $request->get("roles");

        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //verifier les informations envoyés
        if (!empty($id) && !empty($roles)) {

            //instance doctrin mongodb manager
            $dm = $this->get("doctrine_mongodb")->getManager();

            //verifier le role de l'utilisateur connecté
            if ($current_user->hasRole("ROLE_ADMIN")) {
                //recuperer l'utilisateur en question
                $user = $dm->getRepository("AppBundle:User")->find($id);

                if (!$user) {
                    //failed
                    return array("statut" => "failed", "message" => "aucun utilisateur séléctionné");
                } else {
                    if ($user->getCreatedBy() == $current_user->getId()) {

                        //instance manipulateur FOSUSER
                        $manipulator = $this->container->get('fos_user.util.user_manipulator');

                        //update role
                        foreach ($roles as $role)
                            $manipulator->addRole($user->getUsername(), $role);

                        //return utilisateur séléctionné
                        return array("statut" => "success", "message" => "Role ajouter avec succé");
                    } else {
                        //Failed
                        return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
                    }
                }

            } else {
                //failed
                return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
            }
        } else {
            //failed
            return array("statut" => "failed", "message" => "vérifier les informations saisies");
        }
    }

    /**
     * @Rest\Route(name="_user",path="/delete-role",methods={"POST"})
     * @Rest\View()
     */
    public function deleteRoleAction(Request $request)
    {
        //recuperer les infomation saisies
        $id = $request->get("id");
        $roles = $request->get("roles");

        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //verifier les informations envoyés
        if (!empty($id) && !empty($roles)) {

            //instance doctrin mongodb manager
            $dm = $this->get("doctrine_mongodb")->getManager();

            //verifier le role de l'utilisateur connecté
            if ($current_user->hasRole("ROLE_ADMIN")) {
                //recuperer l'utilisateur en question
                $user = $dm->getRepository("AppBundle:User")->find($id);

                if (!$user) {
                    //failed
                    return array("statut" => "failed", "message" => "aucun utilisateur séléctionné");
                } else {
                    if ($user->getCreatedBy() == $current_user->getId()) {

                        //instance manipulateur FOSUSER
                        $manipulator = $this->container->get('fos_user.util.user_manipulator');

                        //update role
                        foreach ($roles as $role)
                            $manipulator->removeRole($user->getUsername(), $role);

                        //return utilisateur séléctionné
                        return array("statut" => "success", "message" => "Role supprimé avec succé");
                    } else {
                        //Failed
                        return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
                    }
                }

            } else {
                //failed
                return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
            }
        } else {
            //failed
            return array("statut" => "failed", "message" => "vérifier les informations saisies");
        }
    }

    /**
     * @Rest\Route(name="_user",path="/enable-disable",methods={"POST"})
     * @Rest\View()
     */
    public function enableDisableAction(Request $request)
    {
        //recuperer les infomation saisies
        $id = $request->get("id");
        $action = $request->get("action");

        //recuperer l'utilisateur connecté
        $current_user = $this->getUser();

        //verifier les informations envoyés
        if (!empty($id) && !empty($action)) {

            //instance doctrin mongodb manager
            $dm = $this->get("doctrine_mongodb")->getManager();

            //verifier le role de l'utilisateur connecté
            if ($current_user->hasRole("ROLE_ADMIN")) {
                //recuperer l'utilisateur en question
                $user = $dm->getRepository("AppBundle:User")->find($id);

                if (!$user) {
                    //failed
                    return array("statut" => "failed", "message" => "aucun utilisateur séléctionné");
                } else {
                    if ($user->getCreatedBy() == $current_user->getId()) {

                        //instance manipulateur FOSUSER
                        $manipulator = $this->container->get('fos_user.util.user_manipulator');

                        //verifier l'action
                        if ($action == "true"){

                            //activate
                            $manipulator->activate($user->getUsername());

                            //return
                            return array("statut" => "success", "message" => "utilisateur activé");
                        }else{
                            //deactivate
                            $manipulator->deactivate($user->getUsername());

                            //return utilisateur séléctionné
                            return array("statut" => "success", "message" => "utilisateur desactivé");
                        }

                    } else {
                        //Failed
                        return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
                    }
                }

            } else {
                //failed
                return array("statut" => "failed", "message" => "Vous n'avez pas le droit d'accéder à cette page");
            }
        } else {
            //failed
            return array("statut" => "failed", "message" => "vérifier les informations saisies");
        }
    }

}