<?php

namespace App\Controller;

use App\Controller\CustomerController;
use App\Model\CustomerManager;

class ConnectionController extends AbstractController
{
    /*
     * verification if user is already in the database
     */
    public function userExists($userTest): bool
    {
        //TODO : verifier si user existe dans la base (si son email est dans la base et/ou tel)

        $customerManager = new CustomerManager();
        $users = $customerManager->selectAll();
        $retour = true;

        foreach ($users as $user) {
            if ($userTest['email'] === $user['email'] || $userTest['phone'] === $user['phone']) {
                $retour =  true;
            } else {
                $retour =  false;
            }
        }
        return $retour;
    }

    /*
     * add a user depending of his existence in database
     */
    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //user unknow = add to databse
            if ($this->userExists($_POST) === false) {
                $customerController = new CustomerController();
                $customerController->add();

            //user know : any insertion in base, return message
            } else {
                $userExist = 'Utilisateur connu';
                return $this->twig->render('Home/signIn.html.twig', ['userExist' => $userExist]);
            }
        }
        //TODO  ajout cookie pour avoir id prochaine connection ??
    }

    /*
     * connection
     */
    public function connection()
    {
        //TODO : si user existe (email) et POST bon password : connection + redirection ver accueil site
        // et si cookie créer connection direct ??
        // (+ ajout id en session pour connection sur toutes les pages) ??

        //TODO: si existe pas : message mauvais mdp / user innexistant
    }

    /*
     * test if admin
     */
    public function isAdmin()
    {
        //TODO : si log in avec droit admin définira accès aux pages admins
    }
}
