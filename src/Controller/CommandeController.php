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
            $commande = array_map('trim', $_POST);

            // TODO validations (length, format...)
            if (!empty($_POST)) {
                $commande['totalAmount'] = (int) $commande['totalAmount'];
                $commande['customer_id'] = (int) $commande['customer_id'];
                $commande['stock_id'] = (int) $commande['stock_id'];
            }

            // if validation is ok, insert and redirection
            $commandeManager = new CommandeManager();
            $commandeManager->insertCommand($commande);

            $lastID = $commandeManager->selectLastId();
            $commande['command_id'] = (int) $lastID[0];
            $commandeManager->insertCommandDetails($commande);
        }

        header("Location: /Commande/showAll");
    }


    /*
     * Delete a command and his details
     */
    public function suppr(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commandeManager = new CommandeManager();
            $commandeManager->delete($id);
            header("Location: /Commande/showAll");
        }
    }

    /*
     * edit a command details : date pick
     */
    public function editDatePickById($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newDate = $_POST['newDataPick'];
            $commandManager = new CommandeManager();

            $commandManager->editDatePicksById($id, $newDate);
            header("Location: /Commande/showAll");
        }
    }
}
