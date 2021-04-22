<?php

namespace App\Controller;

use App\Model\BouquetVitrineManager;

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

        return $this->twig->render('BouquetVitrine/show.html.twig', ['bouquetVitrine' => $bouquetVitrine]);
    }

    public function edit(int $id): string
    {
        $bouquVitrineManager = new BouquetVitrineManager();
        $bouquetVitrine = $bouquVitrineManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bouquetVitrine = array_map('trim', $_POST);

            $bouquVitrineManager->update($bouquetVitrine);
            header('Location: /BouquetVitrine/show/' . $id);
        }

        return $this->twig->render('BouquetVitrine/edit.html.twig', [
            'bouquetVitrine' => $bouquetVitrine,
        ]);
    }

    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bouquetVitrine = array_map('trim', $_POST);
            $bouquVitrineManager = new BouquetVitrineManager();
            $id = $bouquVitrineManager->insert($bouquetVitrine);
            header('Location:/bouquetVitrine/show/' . $id);
        }

        return $this->twig->render('BouquetVitrine/add.html.twig');
    }

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bouquVitrineManager = new BouquetVitrineManager();
            $bouquVitrineManager->delete($id);
            header('Location:/bouquetVitrine/index');
        }
    }
}
