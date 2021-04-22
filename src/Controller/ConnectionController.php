<?php

namespace App\Controller;

class ConnectionController extends AbstractController
{
    /*
     * verification if user is already in the database
     */
    public function userExists()
    {
        //TODO : verifier si user existe dans la base (si son email est dans la base et/ou tel)
    }

    /*
     * add a user depending his existence in database
     */
    public function addUser()
    {
        //TODO Verifications (si formulaire bien transmis pas vide etc

        //TODO : si email user n'existe pas : appel crud customer pour ajouter le user
        // sinon return message 'email ou tel ' est deja relié à utilisateur
        // (+ ajout cookie pour avoir id prochaine connection) ??
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
