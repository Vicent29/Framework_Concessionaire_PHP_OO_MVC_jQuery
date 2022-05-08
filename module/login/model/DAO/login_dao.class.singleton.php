<?php
    class login_dao {
        static $_instance;

        private function __construct() {
        }

        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function select_email($db, $email) {
            $sql = "SELECT email FROM users WHERE email='$email'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }

        public function insert_user($db, $username, $hashed_pass, $email, $avatar ) {
            $sql = "INSERT INTO `users`(`username`, `password`, `email`, `type_user`, `avatar`) 
                    VALUES ('$username','$hashed_pass','$email','client','$avatar')";
            return $db->ejecutar($sql);
        }

        public function select_user($db, $username) {
            $sql = "SELECT `username`, `password`, `email`, `type_user`, `avatar` FROM `users` WHERE username='$username'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }

        public function select_data_user($db, $username) {
            $sql = "SELECT * FROM users WHERE username='$username'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }
    }
?>