<?php
namespace app\models;

class Admin
{

    protected $db;

    public function _construct($db)
    {
        $this->db = $db;
    }

    public function loginAdmin()
    {
        $name = $_POST['name'];
        $password = $_POST['password'];

        if ($name === 'admin' && $password === 'admin123') {
            $this->app->render('/dashboard', ['admin' => $name]);
        } else {
           $this->app->render('/');
        }
    }
}
?>