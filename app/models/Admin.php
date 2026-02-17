<?php
namespace app\models;

class Admin
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function loginAdmin($name, $password)
    {
        if ($name === 'admin' && $password === 'admin123') {
            return true;
        } 
        return false;
    }
}
?>