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
            $sql = "UPDATE users SET active = '1' WHERE email_token = '$token_email'";
            return $db->ejecutar($sql);
        }

        public function insert_user($db, $id_user, $username, $hashed_pass, $email,$email_token, $avatar ) {
            $sql = "INSERT INTO `users`(`id_user`,`username`, `password`, `email`,`email_token`, `type_user`, `avatar`) 
                    VALUES ('$id_user','$username','$hashed_pass','$email','$email_token','client','$avatar')";
            return $db->ejecutar($sql);
        }
        public function update_recover_password($db, $email, $token_email) {
            $sql = $sql = "UPDATE `users` SET `email_token`= '$token_email', `active`= '0' WHERE `email` = '$email'";
            $stmt = $db->ejecutar($sql);
            return "ok_update";
        }

        public function  select_old_passwd_email_token($db, $email_token) {
            $sql = "SELECT `password` FROM `users` WHERE email_token='$email_token'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);
        } 

        public function update_new_passwd_email($db, $email_token, $new_passwd) {
            $sql = $sql = "UPDATE `users` SET `password`= '$new_passwd', `active`= '1' WHERE `email_token` = '$email_token'";
            $stmt = $db->ejecutar($sql);
            return "ok_update";
        }

        public function select_user($db, $username) {
            $sql = "SELECT * FROM `users` WHERE username='$username'";
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