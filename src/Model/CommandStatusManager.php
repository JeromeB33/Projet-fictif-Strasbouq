<?php

namespace App\Model;

class CommandStatusManager extends AbstractManager
{
    public const TABLE = "commandStatus";

    /*
    * insert command status false by default
     */
    public function insertStatus(array $command): void
    {
        $query = ("INSERT INTO " . self::TABLE . "(command_id, isprepared, ispick) 
                    VALUES (:command_id, :isprepared, :ispick)");
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('command_id', $command['command_id']);
        $statement->bindValue('isprepared', $command['isprepared']);
        $statement->bindValue('ispick', $command['ispick']);
        $statement->execute();
    }

    /*
     * select by id
     */
    public function selectOneById(int $commandId)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE command_id=:id");
        $statement->bindValue('id', $commandId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
    /*
    * edit command status
    */
    public function editStatus(int $id, $ispick, $isprepared)
    {
        $query = ("UPDATE " . self::TABLE . " SET isPrepared = :isPrepared, isPick= :isPick WHERE command_id=$id");
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('isPick', $ispick);
        $statement->bindValue('isPrepared', $isprepared);
        $statement->execute();

        header("Location: /Command/showAll");
    }
}
