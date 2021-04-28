<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

class HomeController extends AbstractController
{
    /*
     * Display home page
     * @return string
     */
    public function index()
    {
        return $this->twig->render('Home/index.html.twig');
    }

    /**
     * Display log in page
     */
    public function logIn()
    {
        return $this->twig->render('Home/logIn.html.twig');
    }

    /**
     * Display sign in page
     */
    public function signIn()
    {
        return $this->twig->render('Home/signIn.html.twig');
    }
}
