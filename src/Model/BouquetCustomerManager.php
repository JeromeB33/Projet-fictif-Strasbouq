<?php

namespace App\Model;

class BouquetCustomerManager extends AbstractManager
{
    public const TABLE = 'bouquetCustomer';
    public const TABLE_2 = 'stock_bouquetCustomer';
    public const TABLE_3 = 'stock';
    /**
     * Insert new item in database.
     */
    public function insert(array $bouquetCustomer)
    {
        $statement = $this->pdo->prepare('INSERT INTO ' . self::TABLE . ' VALUES (null, :customer_id, :name)');
        $statement->bindValue('customer_id', $bouquetCustomer['customer_id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $bouquetCustomer['name'], \PDO::PARAM_STR);

        $statement->execute();
    }

    public function insertFlowersInBouquet(array $bouquetCustomer)
    {

            $query = "INSERT INTO " . self::TABLE_2 . " VALUES( 
                " . $bouquetCustomer['bouquet_id'] . ", " . $bouquetCustomer['stocks'] . ")";
            $this->pdo->exec($query);
    }
    /**
     * Update item in database.
     */
    public function update(array $bouquetCustomer): bool
    {
        $statement = $this->pdo->prepare('UPDATE ' . self::TABLE . ' SET `name` = :name WHERE id=:id');
        $statement->bindValue('id', $bouquetCustomer['bouquet_id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $bouquetCustomer['name'], \PDO::PARAM_STR);
        return $statement->execute();
    }

    public function selectBouquetCustomerById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "SELECT *, count(stock_id) as nombre FROM " . static::TABLE_2 . " 
            JOIN " . static::TABLE_3 . " s 
            ON s.id=stock_id  WHERE bouquetCustomer_id=:id GROUP BY stock_id"
        );
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function deleteOneFlower(int $id, int $bouquetId)
    {
                // prepared request
                $statement = $this->pdo->prepare("DELETE FROM " . static::TABLE_2 . "
                 WHERE stock_id=:id and bouquetCustomer_id=:bouquet_id LIMIT 1");
                $statement->bindValue('id', $id, \PDO::PARAM_INT);
                $statement->bindValue('bouquet_id', $bouquetId, \PDO::PARAM_INT);
                $statement->execute();
    }

    public function selectLastId()
    {
        // prepared request
        $statement = $this->pdo->query("SELECT MAX(id)  FROM " . static::TABLE);
        return $statement->fetch(\PDO::FETCH_NUM);
    }
}
