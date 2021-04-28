<?php

namespace App\Controller;

use App\Model\CommandStatusManager;

class CommandStatusController extends AbstractController
{
    /**
     * Show command status by id
     */
    public function showById(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commandStatusManager = new CommandStatusManager();
            $status = $commandStatusManager->selectOneById($id);

            //transfrom value for more comprehension for the view
            if (!empty($status)) {
                if ($status['ispick'] === '0') {
                    $status['ispick'] = 'Non';
                } elseif ($status['ispick'] === '1') {
                    $status['ispick'] = 'Oui';
                }

                if ($status['isprepared'] === '0') {
                    $status['isprepared'] = 'Non';
                } elseif ($status['isprepared'] === '1') {
                    $status['isprepared'] = 'Oui';
                }
            }

            return $this->twig->render("Commande/indexCommande.html.twig", ['status' => $status]);
        }
    }

    /*
    * edit status
    */
    public function editStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ispick = $_POST['isPick'];
            $isprepared = $_POST['isPrepared'];

            //transform value to fit in the table
            if ($ispick === 'false') {
                $ispick = 0;
            } elseif ($ispick === 'true') {
                $ispick = 1;
            }

            if ($isprepared === 'false') {
                $isprepared = 0;
            } elseif ($isprepared === 'true') {
                $isprepared = 1;
            }

            $commandStatusManager = new CommandStatusManager();
            $commandStatusManager->editStatus($id, $ispick, $isprepared);
        }

        header("Location: /Command/showAll");
    }
}
