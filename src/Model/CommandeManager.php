<?php

namespace App\Model;

class CommandeManager extends AbstractManager
{
    public const TABLE = "command";
    public const TABLE_2 = "commandDetails";

    public function insert(int $total): void
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (totalAmount) VALUES (:totalAmount)");
        $statement->bindValue('totalAmount', $total, \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Get one row from database by ID.
     *
     */
    public function selectOneById(int $commandId)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE_2 . " WHERE command_id=:id");
        $statement->bindValue('id', $commandId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
}
