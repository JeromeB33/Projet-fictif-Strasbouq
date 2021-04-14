<?php


namespace App\Model;

class CommandeManager extends AbstractManager
{
    public const TABLE = "command";

    public function insert($total)
    {
        $statement = $this->pdo->prepare("INSERT INTO ". self::TABLE." (totalAmount) VALUES (:totalAmount)");
        $statement->bindValue('totalAmount', $total,\PDO::PARAM_INT);
        $statement->execute();
    }

    public function showAll()
    {
        $statement = $this->pdo->query("SELECT * FROM ". self::TABLE);
        $commandes = $statement->fetchAll();

        return $commandes;
    }
}