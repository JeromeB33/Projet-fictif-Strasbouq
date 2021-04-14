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
        $customerManager = new CustomerManager();
        $customer = $customerManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $customer = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $customerManager->update($customer);
            header('Location: /customer/show/'.$id);
        }

        return $this->twig->render('Customer/edit.html.twig', [
            'customer' => $customer,
        ]);
    }

    /**
     * Add a new customer.
     */
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $customer = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $customerManager = new CustomerManager();
            $id = $customerManager->insert($customer);
            header('Location:/customer/show/'.$id);
        }

        return $this->twig->render('Customer/add.html.twig');
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
}
