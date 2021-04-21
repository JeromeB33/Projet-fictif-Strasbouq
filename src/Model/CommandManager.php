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
        $query = "INSERT INTO " . self::TABLE . " (totalAmount, customer_id, dateOrder, datePick) 
                    VALUES (:totalAmount, :customer_id, :dateOrder, :datePick)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('totalAmount', $commande['totalAmount'], \PDO::PARAM_INT);
        $statement->bindValue('dateOrder', date('Y-m-d H:i:s', time()));
        $statement->bindValue('datepick', $commande['datepick']);
        $statement->bindValue('customer_id', $commande['customer_id'], \PDO::PARAM_INT);

        $statement->execute();
    }


    /*
         *  insert command details in database
         */
    public function insertCommandDetails(array $commande): void
    {
        $query = ("INSERT INTO " . self::TABLE_2 . " (stock_id, command_id, quantity) 
                    VALUES (:stock_id, :command_id, :quantity)");
        $req = $this->pdo->prepare($query);
        $req->bindValue('stock_id', $commande['stock_id'], \PDO::PARAM_INT);
        $req->bindValue('command_id', $commande['command_id'], \PDO::PARAM_INT);
        $req->bindValue('quantity', $commande['quantity'], \PDO::PARAM_INT);

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
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE_2 . " SET datePick = :datePick WHERE command_id=$id");
        $statement->bindValue('datePick', $newDatePick);
        $statement->execute();
    }

    /*
     * select all tuple with same command_id to have the whole command with each stock id
     */

    public function listCommand(int $id): array
    {
        $query = ("SELECT * FROM commandDetails d 
                    INNER JOIN command c ON d.command_id = c.id 
                    INNER JOIN commandStatus s ON s.command_id= d.command_id 
                    WHERE d.command_id=" . $id);
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
