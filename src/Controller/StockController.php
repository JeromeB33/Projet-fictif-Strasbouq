<?php

namespace App\Controller;

use App\Model\StockManager;

class StockController extends AbstractController
{
    public function index(): string
    {
        if ($_SESSION['admin'] === false) {
            header('Location: /Home/accessdenied');
        }
        $stockManager = new StockManager();
        $stocks = $stockManager->selectAll('name');

        return $this->twig->render('Stock/index.html.twig', ['stocks' => $stocks]);
    }
    public function show(int $id): string
    {
        if ($_SESSION['admin'] === false) {
            header('Location: /Home/accessdenied');
        }
        $stockManager = new StockManager();
        $stock = $stockManager->selectOneByID($id);

        return $this->twig->render('Stock/show.html.twig', ['stock' => $stock]);
    }

    public function edit(int $id): string
    {
        if ($_SESSION['admin'] === false) {
            header('Location: /Home/accessdenied');
        }
        $stockManager = new StockManager();
        $stock = $stockManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stock = array_map('trim', $_POST);

            $stockManager->update($stock);
            header('Location: /stock/show/' . $id);
        }

        return $this->twig->render('Stock/edit.html.twig', [
        'stock' => $stock,
        ]);
    }

    public function add(): string
    {
        if ($_SESSION['admin'] === false) {
            header('Location: /Home/accessdenied');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stock = array_map('trim', $_POST);
            $stockManager = new StockManager();
            $id = $stockManager->insert($stock);
            header('Location:/stock/show/' . $id);
        }

        return $this->twig->render('Stock/add.html.twig');
    }

    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stockManager = new StockManager();
            $stockManager->delete($id);
            header('Location:/stock/index');
        }
    }
}
