<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\StockManager;
use App\Model\BouquetVitrineManager;

class HomeController extends AbstractController
{
    /*
     * Display home page
     * @return string
     */
    public function index(): string
    {
        return $this->twig->render('Home/index.html.twig');
    }

    public function panier()
    {
        return $this->twig->render('Home/panier.html.twig');
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

    /*
     * display page compose ton bouquet
     */
    public function composeBouquet(): string
    {
        $stockManager = new StockManager();
        $stock = $stockManager->selectAll();
        return $this->twig->render('Home/compose.html.twig', ['stock' => $stock]);
    }

    /*
     * display page choisi ton bouquet
     */
    public function choisiBouquet(): string
    {
        $bouquetVitrine = new BouquetVitrineManager();
        $bouquets = $bouquetVitrine->selectAll();
        return $this->twig->render('Home/choisi.html.twig', ['bouquets' => $bouquets]);
    }
}
