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
        //TODO : add a command with multiple stock id and quantity : one tuple for one id

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO validations (length, format...)
            if (!empty($_POST)) {
                //clean $_POST data
                //$commande = array_map('trim', $_POST);
                $commande = $_POST;
                //insert command
                $commandeManager = new CommandManager();
                $commandeManager->insertCommand($commande);

                //TODO if validation is ok, insert and redirection

                //take id of the last input in command to associate the command details and status
                $lastID = $commandeManager->selectLastId();
                $commande['command_id'] = (int)$lastID[0];

                //insert command details :
                //for each stock id if tis quantity > 0 add a tuple
                foreach ($commande['stock_id'] as $stockId => $quantities) {
                    foreach ($quantities as $quantity) {
                        if (!empty($quantity) && $quantity !== '0') {
                            $commande['stock_id'] = (int)$stockId;
                            $commande['quantity'] = (int)$quantity;
                            $commandeManager->insertCommandDetails($commande);
                        }
                    }
                }

                    //test to transform value in tinyint (bool) (status table)
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

                    // insert command status
                    $commandStatusManager = new CommandStatusManager();
                    $commandStatusManager->insertStatus($commande);
            }
            //redirection
            header("Location: /Command/showAll");
        }
    }

    /*
     * Delete a command
     */
    public function suppr(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commandeManager = new CommandManager();
            $commandeManager->delete($id);
            header("Location: /Command/showAll");
        }
    }

    /*
     * edit a command details : date pick
     */
    public function editDatePickById($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newDate = $_POST['newDatePick'];
            $commandManager = new CommandManager();

            $commandManager->editDatePicksById($id, $newDate);
            header("Location: /Command/showAll");
        }
    }

    /*
     * liste the whole command
     */
    public function listCommand($id)
    {
        $commandManager = new CommandManager();
        $commandList = $commandManager->listCommand($id);

        for ($i = 1; $i < 1; $i++) {
            if ($commandList[$i]['isprepared'] === '0') {
                $commandList[$i]['isprepared'] = 'Nop';
            } elseif ($commandList[$i]['isprepared'] === "1") {
                $commandList[$i]['isprepared'] = 'Yup';
            }
            if ($commandList[$i]['ispick'] === '0') {
                $commandList[$i]['ispick'] = 'Nop';
            } elseif ($commandList[$i]['ispick'] === "1") {
                $commandList[$i]['ispick'] = 'Yup';
            }
        }
        return $this->twig->render("Commande/indexCommande.html.twig", ['commandList' => $commandList]);
    }
}
