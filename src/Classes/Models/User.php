<?php


namespace App\Models;

//Модель для тестирования
use InvalidArgumentException;

class User
{
    private $name;
    private $email;
    private $password;

    private $observers;

    public function saveUser(DataBase $db){
        if($db->connect("host", "user", "pass", "db")){
            $db->query("");

            return true;
        }
        return false;
    }

    public function attach(UserObserver $userObserver){
        $this->observers[] = $userObserver;
    }

    public function update(){
        $this->notify('update');
    }

    /**
     * User constructor.
     * @param $name
     * @param $email
     * @param $password
     */
    public function __construct($name = null, $email = null, $password = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        if (empty($this->email)){
            throw new InvalidArgumentException("Empty email", 10);
        }
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    private function notify(string $string)
    {
        foreach ($this->observers as $observer){
            $observer->update($string);
        }
    }

}