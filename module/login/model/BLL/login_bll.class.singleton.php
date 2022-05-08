<?php
	class login_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = login_dao::getInstance();
			$this->db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_register_BLL($args) {
			
			$useraname= $args[0];
			$password= $args[1];
			$email= $args[2];
			
			$exist_user_email=  $this -> dao -> select_email($this->db, $email);
			$exist_user_username=  $this -> dao -> select_user($this->db, $useraname);

			if (!$exist_user_email  && !$exist_user_username) {
				$hashed_pass = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            	$hashavatar = md5(strtolower(trim($email))); 
            	$avatar = "https://i.pravatar.cc/500?u=$hashavatar";
				$rdo=  $this -> dao -> insert_user($this->db, $useraname, $hashed_pass, $email, $avatar);
				if($rdo){
					return "ok";
				}
			}elseif ($exist_user_email) {
				return "error_email";
			}elseif ($exist_user_username){
				return "error_user";
			}
			
		}

		public function get_login_BLL($args) {
			$useraname = $args[0];
			$password = $args[1];
			$exist_user=  $this -> dao -> select_user($this->db, $useraname);
			if (!$exist_user) {
				return "error_user";
			}else{
				if (password_verify($password, $exist_user[0]['password'])) {
                    $token= middleware::create_token($exist_user[0]["username"]);
                    $_SESSION['username'] = $exist_user[0]['username']; //Guardamos el usario 
                    $_SESSION['tiempo'] = time(); //Guardamos el tiempo que se logea
                    return $token;
                } else {
                    return "error_passwd";
                }
			}
		}

		public function get_logout_BLL() {
			unset($_SESSION['username']);
			unset($_SESSION['tiempo']);
			session_destroy();
			return "Done";
		}

		public function get_data_user_BLL($token) {
			$json = middleware::descode_token($token);
			return $this -> dao -> select_data_user($this->db, $json['username']);
			
		}

		public function get_actividad_BLL() {
			if (!isset($_SESSION["tiempo"])) {
				return "inactivo";
			} else {
				if ((time() - $_SESSION["tiempo"]) >= 1800) { //1800s=30min
					return "inactivo";
				} else {
					return "activo";
				}
			}
		}

		public function get_controluser_BLL($token) {
			$token_dec = middleware::descode_token($token);
			if ($token_dec['exp'] < time()) {
				return "Wrong_User";
			}

			if (isset($_SESSION['username']) && ($_SESSION['username']) == $token_dec['username']) {
				return "Correct_User";
			}else {
				return "Wrong_User";
			}
		}

		public function get_refresh_token_BLL($token) {
			$old_token = middleware::descode_token($token);
			if($old_token) {
				$new_token = middleware::create_token($old_token['username']);
				return $new_token;
			}else {
				return 'error';
			}
		}

		public function get_refresh_cookie_BLL() {
			session_regenerate_id();
			return "Done";
		}


	}
?>