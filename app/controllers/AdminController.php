<?php
    namespace app\controllers;

    use Flight;
    use app\models\Admin;

    class AdminController{

        protected $app;
        private $adminModel;

        public function __construct($app){
            $this->app = $app;
            $this->adminModel = new Admin(Flight::db());
        }

        public function loginAdmin()
        {
            $admin = $this->adminModel;
            $this->app->render('login.php', ['admin' => $admin]);
        }
    }
?>