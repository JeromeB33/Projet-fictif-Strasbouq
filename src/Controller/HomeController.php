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
    /**
     * Display home page
     */
    public function index(): string
    {
        return $this->twig->render('Home/index.html.twig');
    }

    /**
     * Display contact page
     */
    public function contact(): string
    {
        return $this->twig->render('Home/contact.html.twig');
    }

    /**
     * Display log in page
     */
    public function logIn(): string
    {
        return $this->twig->render('Home/logIn.html.twig');
    }

    /**
     * Display sign in page
     */
    public function signIn(): string
    {
        return $this->twig->render('Home/signIn.html.twig');
    }
}
