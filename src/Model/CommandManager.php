<?php

namespace App\Model;

class CommandManager extends AbstractManager
{
    public const TABLE = "command";
    public const TABLE_2 = "commandDetails";
    public const TABLE_3 = "commandStatus";

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
        $statement->bindValue('datePick', $commande['datePick']);
        $statement->bindValue('customer_id', $_SESSION['user']['id'], \PDO::PARAM_INT);

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
        $statement = $this->pdo->query("SELECT MAX(id)  FROM " . static::TABLE);
        return $statement->fetch(\PDO::FETCH_NUM);
    }

    /*
     * Edit by id command : date pick
     */
    public function editDatePicksById(int $id, $newDatePick): void
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET datePick = :datePick WHERE id=$id");
        $statement->bindValue('datePick', $newDatePick);
        $statement->execute();
    }

    /*
     * select all tuple with same command_id to have the whole command with each stock id
     */

    public function listCommand(int $id): array
    {
        $query = ("SELECT * FROM " . self::TABLE_2 . " d 
                    INNER JOIN " . self::TABLE . " c ON d.command_id = c.id  
                    WHERE d.command_id=" . $id);
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
