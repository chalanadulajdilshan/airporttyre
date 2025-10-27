<?php

class Income
{
    public $id;
    public $name;
    public $amount;
    public $date;

    public function __construct($id = null)
    {
        if ($id) {
            $query = "SELECT * FROM `income` WHERE `id` = " . (int)$id;
            $db = new Database();
            $result = mysqli_fetch_array($db->readQuery($query));

            if ($result) {
                $this->id = $result['id'];
                $this->name = $result['name'];
                $this->amount = $result['amount'];
                $this->date = $result['date'];
            }
        }
    }

    public function create()
    {
        $query = "INSERT INTO `income` (`name`, `amount`, `date`) 
                  VALUES (
                    '{$this->name}', 
                    '{$this->amount}', 
                    '{$this->date}'
                  )";

        $db = new Database();
        return $db->readQuery($query) ? mysqli_insert_id($db->DB_CON) : false;
    }

    public function update()
    {
        $query = "UPDATE `income` 
                  SET 
                    `name` = '{$this->name}', 
                    `amount` = '{$this->amount}', 
                    `date` = '{$this->date}'
                  WHERE `id` = '{$this->id}'";

        $db = new Database();
        return $db->readQuery($query);
    }

    public function delete()
    {
        $query = "DELETE FROM `income` WHERE `id` = '{$this->id}'";
        $db = new Database();
        return $db->readQuery($query);
    }

    public function all()
    {
        $query = "SELECT * FROM `income` ORDER BY `date` DESC";
        $db = new Database();
        $result = $db->readQuery($query);
        $array = [];

        while ($row = mysqli_fetch_array($result)) {
            array_push($array, $row);
        }

        return $array;
    }

    public function totalIncome()
    {
        $query = "SELECT SUM(`amount`) AS total FROM `income`";
        $db = new Database();
        $result = mysqli_fetch_array($db->readQuery($query));
        return $result ? $result['total'] : 0;
    }
}
