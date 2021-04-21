<?php

namespace App\Controller;

class ContactController extends AbstractController
{
    public function contactUs()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $destinataire = "mail@exemple.fr";
            $objet = "Retour client site";
            $client = "Nom: " . $_POST['lastname'] . ' Prénom : ' . $_POST['firstname'];
            $message = $_POST['message'] . '<br/>' . $client;
            $entete  = 'From:' . $_POST['email'] .
                ' MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            //envoi du mail
            $retour = mail($destinataire, $objet, $message, $entete);
            var_dump(mail($destinataire, $objet, $message, $entete));
            ($retour) ? $envoi = 'Votre message à bien était envoyé' : $envoi = "Erreur lors de l'envoi";
            return $this->twig->render('Home/contact.html.twig', ['envoi' => $envoi]);
        }
    }
}
