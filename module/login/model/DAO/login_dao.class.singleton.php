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

        public function select_verify_email($db, $token_email) {
            $sql = "SELECT active FROM users WHERE email_token='$token_email'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        }

        public function  update_active_email($db, $token_email) {
            $sql = "UPDATE users SET active = 1 WHERE email_token = '$token_email'";
            return $db->ejecutar($sql);
        }

        public function insert_user($db, $id_user, $username, $hashed_pass, $email,$email_token, $avatar ) {
            $sql = "INSERT INTO `users`(`id_user`,`username`, `password`, `email`,`email_token`, `type_user`, `avatar`) 
                    VALUES ('$id_user','$username','$hashed_pass','$email','$email_token','client','$avatar')";
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