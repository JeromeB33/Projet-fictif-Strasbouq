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
     * Show all informations
     */
    public function showAll(): string
    {
        $commandeManager = new CommandeManager();
        $commandes = $commandeManager->selectAll();

        return $this->twig->render("Commande/indexCommande.html.twig", ['commandes' => $commandes]);
    }

    /**
     * Show informations by id
     */
    public function showById(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commandeManager = new CommandeManager();
            $details = $commandeManager->selectOneById($id);

            return $this->twig->render("Commande/indexCommande.html.twig", ['details' => $details]);
        }
    }

    /**
     * Add a new command
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $total = trim($_POST['totalAmount']);
            $total = (int) $total;

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $commandeManager = new CommandeManager();

                $commandeManager->insert($total);
        }

        header("Location: /Commande/show");
    }


    /*
     * Delete a command and his details
     */
    public function suppr(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commandeManager = new CommandeManager();
            $commandeManager->delete($id);
            header("Location: /Commande/index");
        }
    }
}
