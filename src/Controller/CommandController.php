<?php

namespace App\Controller;

use App\Model\CommandManager;
use App\Model\CommandStatusManager;

class CommandController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render("Commande/indexCommande.html.twig");
    }

    /**
     * Show all informations
     */
    public function showAll(): string
    {
        $commandeManager = new CommandManager();
        $commandes = $commandeManager->selectAll();

        return $this->twig->render("Commande/indexCommande.html.twig", ['commandes' => $commandes]);
    }

    /**
     * Show informations by id
     */
    public function showById(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commandeManager = new CommandManager();
            $details = $commandeManager->selectOneById($id);

            return $this->twig->render("Commande/indexCommande.html.twig", ['details' => $details]);
        }
    }

    /**
     * Add a new command with its details and status
     */
    public function add(): void
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
            /*
             * insert command
            */
            $commandeManager = new CommandManager();
            $commandeManager->insertCommand($commande);
            /*
             * take id of the last input in command to associate the command details and status
             */
            $lastID = $commandeManager->selectLastId();
            $commande['command_id'] = (int) $lastID[0];
            /*
            * insert command details
            */
            $commandeManager->insertCommandDetails($commande);
            /*
             * test to transform in booleen
             */
            if ($commande['ispick'] === 'false') {
                $commande['ispick'] = 0;
            } elseif ($commande['ispick'] === 'true') {
                $commande['ispick'] = 1;
            }

            if ($commande['isprepared'] === 'false') {
                $commande['isprepared'] = 0;
            } elseif ($commande['isprepared'] === 'true') {
                $commande['isprepared'] = 1;
            }
            /*
            * insert command status
            */
            $commandStatusManager = new CommandStatusManager();
            $commandStatusManager->insertStatus($commande);
        }

        header("Location: /Commande/showAll");
    }

    /*
     * Delete a command
     */
    public function suppr(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commandeManager = new CommandManager();
            $commandeManager->delete($id);
            header("Location: /Commande/showAll");
        }
    }

    /*
     * edit a command details : date pick
     */
    public function editDatePickById($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newDate = $_POST['newDataPick'];
            $commandManager = new CommandManager();

            $commandManager->editDatePicksById($id, $newDate);
            header("Location: /Commande/showAll");
        }
    }

    /*
     * liste the whole command
     */
    public function listCommand($id)
    {
        $commandManager = new CommandManager();
        $commandList = $commandManager->listCommand($id);

        return $this->twig->render("Commande/indexCommande.html.twig", ['commandList' => $commandList]);
    }
}
