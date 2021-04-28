<?php

namespace App\Controller;

use App\Controller\CustomerController;
use App\Model\CustomerManager;

class ConnexionController extends AbstractController
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
     * test if couple password + email exist in base
     */
    public function coupleExist($userTest)
    {
        $customerManager = new CustomerManager();
        //get id of the customer
        $id = $customerManager->selectIdByEmail($userTest['email']);
        $retour = false;

        if ($id) {
            //if there is an id , we search his informations
            $user = $customerManager->selectOneById($id['id']);

            //test if email and password matched
            if ($userTest['email'] === $user['email'] && $userTest['password'] === $user['password']) {
                $retour = true;
            }
        } else {
            $retour =  false;
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
                $message = "Inscription réussie, vous pouvez à présent vous connecter";
                return $this->twig->render('Home/logIn.html.twig', ['message' => $message]);

            //user know : any insertion in base, return message
            } elseif ($this->userExists($_POST) === true) {
                $message = 'Utilisateur connu';
                return $this->twig->render('Home/signIn.html.twig', ['message' => $message]);
            }
        }
        //TODO  ajout cookie pour avoir id prochaine connection ??
    }

    /*
     * connection
     */
    public function connect()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //user know then redirection to home page
            if ($this->coupleExist($_POST) === true) {
                //user know :  redirection accueil
                $_SESSION['login'] = $_POST['email'];
                return $this->twig->render('Home/index.html.twig');
            } else {
                //unknow then error message
                $message = 'Utilisateur inconnu, veuillez réessayer ou vous inscrire';
                return $this->twig->render('Home/logIn.html.twig', ['message' => $message]);
            }
        }

        // et si cookie créer connection direct ??
        // (+ ajout id en session pour connection sur toutes les pages) ??
    }

    /*
     * test if admin
     */
    public function isAdmin()
    {
        define('ADMIN', 'email@admin.fr');
        //TODO : si log in avec droit admin définira accès aux pages admins
        // si la session en cours est log avec l'email définit alors droit admin
        // : definit $_session admin true, ou ajouter en base le droit admin ?
    }

    /*
     * deconnexion session
     */
    public function deconnexion()
    {
        session_destroy();
        return $this->twig->render('/Home/index.html.twig');
    }
}
