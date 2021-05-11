<?php


namespace App\Models;


class DataBase
{   private $mysqli =null;

    public function connect($host, $user, $pass, $db){
        $this->mysqli = new \mysqli($host, $user, $pass, $db);

        if ($this->mysqli-mysqli_connect_errno()){
            return false;
        }
    }

    /**
     * @param $query
     * @return null
     */
    public function query($query){
        return $this->mysqli-$this->query($query);
    }
}