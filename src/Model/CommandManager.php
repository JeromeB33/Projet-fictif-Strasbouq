<?php

namespace App\Model;

class CommandManager extends AbstractManager
{
    public const TABLE = "command";
    public const TABLE_2 = "commandDetails";

    /*
     *  insert command in database
     */
    public function insertCommand(array $commande): void
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (totalAmount) VALUES (:totalAmount)");
        $statement->bindValue('totalAmount', $commande['totalAmount'], \PDO::PARAM_INT);
        $statement->execute();
    }


    /*
         *  insert command details in database
         */
    public function insertCommandDetails(array $commande): void
    {
        $query = ("INSERT INTO " . self::TABLE_2 . " (stock_id, command_id, customer_id, dataorder, datapick) 
                    VALUES (:stock_id, :command_id, :customer_id, :dataorder, :datapick)");
        $req = $this->pdo->prepare($query);
        $req->bindValue('stock_id', $commande['stock_id'], \PDO::PARAM_INT);
        $req->bindValue('customer_id', $commande['customer_id'], \PDO::PARAM_INT);
        $req->bindValue('command_id', $commande['command_id'], \PDO::PARAM_INT);
        $req->bindValue('dataorder', date('Y-m-d H:i:s', time()));
        $req->bindValue('datapick', $commande['datapick']);
        $req->execute();
    }



    /*
     * Get one row from database by ID.
     */
    public function selectOneById(int $commandId)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE_2 . " WHERE command_id=:id");
        $statement->bindValue('id', $commandId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    /*
     * Select last id in table
     */
    public function selectLastId()
    {
        // prepared request
        $statement = $this->pdo->query("SELECT max(id)  FROM " . static::TABLE);
        return $statement->fetch(\PDO::FETCH_NUM);
    }

    /*
     * Edit by id command details : date pick
     */
    public function editDatePicksById(int $id, $newDatePick): void
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE_2 . " SET datapick = :datapick WHERE command_id=$id");
        $statement->bindValue('datapick', $newDatePick);
        $statement->execute();
    }
}
