<?php

namespace App\Model;

class StockManager extends AbstractManager
{
    public const TABLE = 'stock';

    public function insert(array $stock): int
    {
        $statement = $this->pdo->prepare("INSERT INTO "
            . self::TABLE . " (name,  description, avalaibleNumber,price, image )
         VALUES (:name, :description, :avalaibleNumber, :price, :image)");
        $statement->bindValue('name', $stock['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $stock['description'], \PDO::PARAM_STR);
        $statement->bindValue('avalaibleNumber', $stock['avalaibleNumber'], \PDO::PARAM_INT);
        $statement->bindValue('price', $stock['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $stock['image'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $stock): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name , `description` = :description ,
         `avalaibleNumber` = :avalaibleNumber , `price` = :price, `image` = :image WHERE id=:id");
        $statement->bindValue('id', $stock['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $stock['name'], \PDO::PARAM_STR);
        $statement->bindValue('description', $stock['description'], \PDO::PARAM_STR);
        $statement->bindValue('avalaibleNumber', $stock['avalaibleNumber'], \PDO::PARAM_INT);
        $statement->bindValue('price', $stock['price'], \PDO::PARAM_INT);
        $statement->bindValue('image', $stock['image'], \PDO::PARAM_STR);

        return  $statement->execute();
    }
}
