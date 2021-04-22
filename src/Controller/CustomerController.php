<?php

namespace App\Controller;

use App\Model\CustomerManager;

class CustomerController extends AbstractController
{
    /**
     * List customers.
     */
    public function index(): string
    {
        $customerManager = new CustomerManager();
        $customers = $customerManager->selectAll('lastname');

        return $this->twig->render('Customer/index.html.twig', ['customers' => $customers]);
    }

    /**
     * Show informations for a specific customer.
     */
    public function show(int $id): string
    {
        $customerManager = new CustomerManager();
        $customer = $customerManager->selectOneById($id);

        return $this->twig->render('Customer/show.html.twig', ['customer' => $customer]);
    }

    /**
     * Edit a specific customer.
     */
    public function edit(int $id): string
    {
        $errors = [];

        $customerManager = new CustomerManager();
        $customer = $customerManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $customer = array_map('trim', $_POST);
            $errors = $this->validate($customer);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            if (empty($errors)) {
                $customerManager->update($customer);
                header('Location: /customer/show/' . $id);
            }
        }

        return $this->twig->render('Customer/edit.html.twig', [
            'customer' => $customer, 'errors' => $errors,
        ]);
    }

    /**
     * Add a new customer.
     */
    public function add(): string
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $customer = array_map('trim', $_POST);
            $errors = $this->validate($customer);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            if (empty($errors)) {
                $customerManager = new CustomerManager();
                $id = $customerManager->insert($customer);
                header('Location:/customer/show/' . $id);
            }
        }

        return $this->twig->render('Customer/add.html.twig', ['errors' => $errors]);
    }

    /**
     * Delete a specific customer.
     */
    public function delete(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerManager = new CustomerManager();
            $customerManager->delete($id);
            header('Location:/customer/index');
        }
    }

    private function validate(array $customer): array
    {
        $errors = [];

        if (empty($customer['firstname'])) {
            $errors[] = 'Prénom requis';
        }
        if (empty($customer['lastname'])) {
            $errors[] = 'Nom requis';
        }
        if (empty($customer['email'])) {
            $errors[] = 'Email requis';
        }
        if (!empty($customer['email']) && !filter_var($customer['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email non valide';
        }
        if (empty($customer['phone'])) {
            $errors[] = 'Numéro de téléphone requis';
        }
        if (!empty($customer['phone']) && strlen($customer['phone']) != 10) {
            $errors[] = 'Format du numéro de téléphone invalide';
        }

        return $errors ?? [];
    }

    public function indexBouquetCustomer(): string
    {
        $customerManager = new CustomerManager();
        $customers = $customerManager->selectAll('name');

        return $this->twig->render('Customer/index.html.twig', ['customers' => $customers]);
    }
}
