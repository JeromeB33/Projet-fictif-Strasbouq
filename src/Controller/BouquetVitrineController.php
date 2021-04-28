<?php

namespace App\Controller;

use App\Model\AbstractManager;
use App\Model\BouquetVitrineManager;
use App\Model\StockManager;

class BouquetVitrineController extends AbstractController
{
    public function index(): string
    {

        $bouquVitrineManager = new BouquetVitrineManager();
        $bouquetVitrines = $bouquVitrineManager->selectAll('name');

        return $this->twig->render('BouquetVitrine/index.html.twig', ['bouquetVitrines' => $bouquetVitrines]);
    }
    public function show(int $id): string
    {
        $bouquVitrineManager = new BouquetVitrineManager();
        $bouquetVitrine = $bouquVitrineManager->selectOneByID($id);

        $compoBouquet = $bouquVitrineManager->showBouquet($id);

        return $this->twig->render(
            'BouquetVitrine/show.html.twig',
            ['bouquetVitrine' => $bouquetVitrine , 'compoBouquet' => $compoBouquet]
        );
    }



    public function edit(int $id): string
    {
        $stockManager = new StockManager();
        $fleursVitrine = $stockManager->selectAll();
        $bouquVitrineManager = new BouquetVitrineManager();
        $bouquetVitrine = $bouquVitrineManager->selectOneById($id);
        $idBouquetV = $bouquVitrineManager->selectOneById($id);
        $bouquet = $bouquVitrineManager->showBouquet($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['name'] = trim($_POST['name']);
            $bouquetVitrine = $_POST;
            $bouquetVitrine['bouquetV_id'] = $idBouquetV['id'];
            $bouquVitrineManager->update($bouquetVitrine);
            foreach ($bouquetVitrine['idStock'] as $idFleurs => $quantity) {
                foreach ($quantity as $number) {
                    if ($number != '0') {
                        $bouquetVitrine['idStock'] = $idFleurs;
                        while ((int) $number != 0) {
                            $bouquVitrineManager->insertStockBouquetVitrine($bouquetVitrine);
                            $number--;
                        }
                    }
                }
            }
            header('Location: /BouquetVitrine/show/' . $id);
        }

        return $this->twig->render('BouquetVitrine/edit.html.twig', [
            'bouquetVitrine' => $bouquetVitrine, 'fleursVitrine' => $fleursVitrine, 'bouquet' => $bouquet
        ]);
    }

    public function add(): string
    {
        $stockManager = new StockManager();
        $fleursVitrine = $stockManager->selectAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['name'] = trim($_POST['name']);

            $bouquetVitrine = $_POST;
            $bouquVitrineManager = new BouquetVitrineManager();
            $bouquVitrineManager->insert($bouquetVitrine);
            $lastId = $bouquVitrineManager->selectLastId();
            $bouquetVitrine['bouquetV_id'] = (int)$lastId[0];

            foreach ($bouquetVitrine['idStock'] as $idFleurs => $quantity) {
                foreach ($quantity as $number) {
                    if ($number != '0') {
                        $bouquetVitrine['idStock'] = $idFleurs;
                        while ((int) $number != 0) {
                            $bouquVitrineManager->insertStockBouquetVitrine($bouquetVitrine);
                            $number--;
                        }
                    }
                }
            }
            header('Location:/bouquetVitrine/show/' . $bouquetVitrine['bouquetV_id']);
        }

        return $this->twig->render('BouquetVitrine/add.html.twig', ['fleursVitrine' => $fleursVitrine]);
    }

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bouquVitrineManager = new BouquetVitrineManager();
            $bouquVitrineManager->delete($id);
            header('Location:/bouquetVitrine/index');
        }
    }

    public function deleteFleurBouquet(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bouquVitrineManager = new BouquetVitrineManager();
            $bouquVitrineManager->deleteFleur($id, $_POST['bouquet']);
            header('Location:/bouquetVitrine/edit/' . $_POST['bouquet']);
        }
    }
}
