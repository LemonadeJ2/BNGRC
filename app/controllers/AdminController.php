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
            $name = $_POST['name'];
            $password = $_POST['password'];
            if ($this->adminModel->loginAdmin($name, $password)) {
                $_SESSION['admin'] = $name;
                $this->app->redirect('/dashboard');
            } else {
                $this->app->render('login_admin', ['error' => 'Nom d\'utilisateur ou mot de passe incorrect']);
            }
        }

        public function isLoggedIn()
        {
            return isset($_SESSION['admin']);
        }
    }
?>