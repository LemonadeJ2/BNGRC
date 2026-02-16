<?php
    namespace app\controllers;

    use Flight;
    use flight\Engine;
    use app\models\Admin;

    class AdminController{

        protected Engine $app;
        private $adminModel;

        public function __construct(Engine $app){
            $this->app = $app;
            $this->adminModel = new Admin(Flight::db());
        }

        public function loginAdmin()
        {
            $admin = $this->adminModel;
            $this->app->render('dashboard.php', ['admin' => $admin]);
        }
    }
?>