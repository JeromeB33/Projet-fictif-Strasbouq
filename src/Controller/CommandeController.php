<?php


namespace App\Controller;


use App\Model\CommandeManager;

class CommandeController extends AbstractController
{
    public function index(): string
    {
        //$commandeManager = new CommandeManager();
        //$commandes = $commandeManager->selectAll();

        return $this->twig->render("Commande/indexCommande.html.twig");
    }

    /**
     * Show informations for a specific order
     */
    public function show():string
    {
        $commandeManager = new CommandeManager();
        $commandes = $commandeManager->showAll();

        return $this->twig->render("Commande/indexCommande.html.twig", ['commandes' => $commandes]);
    }

    /**
     * Add a new order
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            // clean $_POST data
            $total = trim($_POST['totalAmount']);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $commandeManager = new CommandeManager();

                $commandeManager->insert($total);
        }

        return $this->twig->render('Commande/indexCommande.html.twig');
    }
}