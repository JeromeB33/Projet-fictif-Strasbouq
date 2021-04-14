<?php


namespace App\Controller;

use App\Model\CommandeDetailsManager;


class CommandDetailsController extends AbstractController
{
    public function show(int $id): string
    {
        $commandDetailsManager = new commandDetailsManager();
        $details = $commandDetailsManager->selectOneById($id);

        return $this->twig->render('Commande/indexCommande.html.twig', ['details' => $details]);
    }
}