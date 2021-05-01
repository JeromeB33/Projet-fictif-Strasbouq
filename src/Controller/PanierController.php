<?php

namespace App\Controller;

use App\Model\StockManager;

class PanierController extends AbstractController
{
    /*
     * add a product to the cart
     */
    public function add(int $flowerId)
    {
        $stockManager = new StockManager();
        $flower = $stockManager->selectOneById($flowerId);

        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }

        $exists = $this->updateQuantity($flower);
        if ($exists === false) {
            $_SESSION['panier'][] = [
                $flowerId => [
                    "name" => $flower['name'],
                    "description" => $flower['description'],
                    "price" => $flower['price'],
                    "quantity" => 1,
                    "image" => $flower['image'],
                ],
            ];
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    /*
     * update quantity of an product
    */
    public function updateQuantity(array $flower)
    {
        foreach ($_SESSION['panier'] as $panier => $id) {
            foreach ($id as $idf => $details) {
                if ($flower['id'] == $idf) {
                    $details = $details;
                    $flowerId = $flower['id'];
                    $_SESSION['panier'][$panier][$flowerId]['quantity'] += 1;
                    $_SESSION['panier'][$panier][$flowerId]['price']
                        *= $_SESSION['panier'][$panier][$flowerId]['quantity'];
                    return true;
                }
            }
        }
        return false;
    }
}
