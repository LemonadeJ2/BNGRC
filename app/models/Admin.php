<?php
    namespace app\models;

    class Admin{

        protected $db;

        public function _construct($db){
            $this->db = $db;
        }

        public function loginAdmin($username, $password){
            if($username === 'admin' && $password === 'admin123'){
                return true;
            }
            return false;
        }

    }
?>  