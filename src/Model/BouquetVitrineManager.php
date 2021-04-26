<?php

namespace App\Model;

class BouquetVitrineManager extends AbstractManager
{

    public const TABLE = 'bouquetVitrine';
    public const TABLE_2 = 'stock_bouquetVitrine';

    public function insert(array $stock): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (name, price ) VALUES (:name, :price)");
        $statement->bindValue('name', $stock['name'], \PDO::PARAM_STR);
        $statement->bindValue('price', $stock['price'], \PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function insertStockBouquetVitrine(array $bouquetV): void
    {
        $query = ("INSERT INTO " . self::TABLE_2 . " (stock_id, bouquetVitrine_id) 
                    VALUES (:stock_id, :bouquetVitrine_id)");
        $req = $this->pdo->prepare($query);
        $req->bindValue('stock_id', $bouquetV['stock_id'], \PDO::PARAM_INT);
        $req->bindValue('bouquetVitrine_id', $bouquetV['bouquetVitrine_id'], \PDO::PARAM_INT);

        $req->execute();
    }

    public function update(array $stock): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name , `price` = :price
         WHERE id=:id");
        $statement->bindValue('id', $stock['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $stock['name'], \PDO::PARAM_STR);
        $statement->bindValue('price', $stock['price'], \PDO::PARAM_INT);

        return  $statement->execute();
    }

    public function stockByIdBouquet(int $id): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " b, stock_bouquetVitrine sb, stock s 
        WHERE b.id=sb.bouquetVitrine_id AND sb.stock_id=s.id AND b.id=:id ");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);

       /* $fleur = $statement->execute(); */
        return $statement->fetchAll();
    }
}
