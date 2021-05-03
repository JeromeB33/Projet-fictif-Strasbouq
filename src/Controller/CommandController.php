<?php

namespace App\Controller;

use App\Model\CommandManager;
use App\Model\CommandStatusManager;
use App\Model\StockManager;
use App\Controller\StockController;

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
     * Add a new command (with its details and status) from form
    */
    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST)) {
                $commande = $_POST;

                //format date pick to fit in base
                $commande['datePick'] = $_POST['datePick'] . ' ' . $_POST['timePick'];

                //insert command
                $commandeManager = new CommandManager();
                $commandeManager->insertCommand($commande);

                // TODO validations (length, format...)

                //take id of the last input in command to associate the command details and status
                $lastID = $commandeManager->selectLastId();
                $commande['command_id'] = (int)$lastID[0];

                //insert command details : for each stock_id if its quantity > 0 add a tuple
                foreach ($commande['stock_id'] as $stockId => $quantities) {
                    foreach ($quantities as $quantity) {
                        if (!empty($quantity) && (int) $quantity > 0) {
                            $commande['stock_id'] = (int)$stockId;
                            $commande['quantity'] = (int)$quantity;
                            $commandeManager->insertCommandDetails($commande);

                            //delete from the stock the flowers used for the command
                            $stockController = new StockController();
                            $stockController->decreaseAvalaibleNumber($stockId, $quantity);
                        }
                    }
                }

                //transform value in tinyint (bool) (to fit into status table)
                $commande['ispick'] === 'false' ? $commande['ispick'] = 0 : $commande['ispick'] = 1;
                $commande['isprepared'] === 'false' ?  $commande['isprepared'] = 0 : $commande['isprepared'] = 1;

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
            //format date pick to fit in base
            $newDate = $_POST['datePick'] . ' ' . $_POST['timePick'];

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

        //transform text to better comprehension for the customer or webowner
        for ($i = 1; $i < 1; $i++) {
            if ($commandList[$i]['isprepared'] === '0') {
                $commandList[$i]['isprepared'] = 'Non';
            } elseif ($commandList[$i]['isprepared'] === "1") {
                $commandList[$i]['isprepared'] = 'Oui';
            }
            if ($commandList[$i]['ispick'] === '0') {
                $commandList[$i]['ispick'] = 'Non';
            } elseif ($commandList[$i]['ispick'] === "1") {
                $commandList[$i]['ispick'] = 'Oui';
            }
        }
        return $this->twig->render("Commande/indexCommande.html.twig", ['commandList' => $commandList]);
    }

    public function commander()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            //test if user is already connected
            if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
                foreach ($_SESSION['panier'] as $panier => $id) {
                    $panier = $panier; //pas le choix pour sinon je peux pas commit
                    foreach ($id as $idf => $details) {
                        $_SESSION['panier']['stock'][] = [
                            'stock_id' => $idf,
                            'quantity' => $details['quantity'],
                        ];
                    }
                }
                //insert data in session
                $_SESSION['panier']['datePick'] = $_POST['datePick'] . ' ' . $_POST['timePick'];
                $_SESSION['panier']['isPrepared'] = $_POST['isPrepared'];
                $_SESSION['panier']['isPick'] = $_POST['isPick'];
                $_SESSION['panier']['totalAmount'] = $_POST['totalAmount'];

                //add the command
                $this->addCommand($_SESSION['panier']);

                //clear the cart and redirection
                $_SESSION['panier'] = [];
                $message = "Merci de votre commande, celle-ci a bien été enregistrée";
                return $this->twig->render("/Home/panier.html.twig", ['message' => $message]);
            }
            //if not connect redirection connexion page
            $message = "Veuillez vous connecter pour passer commande";
            return $this->twig->render("/Home/login.html.twig", ['message' => $message]);
        }
    }

    /*
     * add a command (with its details and status) from cart
     */
    public function addCommand(array $commande): void
    {
        //insert command
        $commandeManager = new CommandManager();
        $commandeManager->insertCommand($commande);

        // TODO validations (length, format...)

        //take id of the last input in command to associate the command details and status
        $lastID = $commandeManager->selectLastId();
        $commande['command_id'] = (int)$lastID[0];

        //insert command details : for each stock_id insert one tuple
        foreach ($commande['stock'] as $i => $stock) {
            $i = $i; //pas le choix pour sinon je peux pas commit
            $commande['stock_id'] = (int) $stock['stock_id'];
            $commande['quantity'] = (int) $stock['quantity'];
            $commandeManager->insertCommandDetails($commande);

            //delete from the stock the flowers used for the command
            $stockController = new StockController();
            $stockController->decreaseAvalaibleNumber($stock['stock_id'], $stock['quantity']);
        }

        //transform value in tinyint (bool) (to fit into status table)
        $commande['isPick'] === 'false' ? $commande['isPick'] = 0 : $commande['isPick'] = 1;
        $commande['isPrepared'] === 'false' ?  $commande['isPrepared'] = 0 : $commande['isPrepared'] = 1;

        // insert command status
        $commandStatusManager = new CommandStatusManager();
        $commandStatusManager->insertStatus($commande);
    }
}
