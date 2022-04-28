<?php
	class shop_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = shop_dao::getInstance();
			$this->db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_all_cars_BLL($args) {
			return $this -> dao -> select_all_cars($this->db, $args[0], $args[1]);
		}

		public function get_details_car_BLL($id) {
		
			$Date_car = $this->dao->select_one_car($this->db, $id);
        	$Date_images = $this->dao->select_imgs_car($this->db, $id);

			if (!empty($Date_car || $Date_images)) {

				$rdo = array();
				$rdo[0] = $Date_car;
				$rdo[1][] = $Date_images;
	
				return $rdo;
			} else {
				return "error";
			}
        	
		}

		public function get_shop_filters_BLL($args) {
			$operacion_sql= $args[5];
			$total_prod = $args[0];
			$items_page = $args[1];

			//coger las variable de cada uno de lso filtros que vienen parseadas de antes
			$colors = $args[2];
			$doors = $args[3];
			$category = $args[4];

			//Guardaremos los filtros pulsados dependoendo de si estan llenos o no
			$filtros = "";

			if ($colors != '*' && $doors == '*' && $category == '*') {

				for ($i = 0; $i < sizeof($colors); $i++) {
					if ($i == 0) {
						$filtros .= "(color ='" . $colors[$i] . "'";
					} else if ($i == (sizeof($colors) - 1)) {
						$filtros .= " OR color = '" . $colors[$i] . "')";
					} else {
						$filtros .= " OR color = '" . $colors[$i] . "'";
					}
					if (sizeof($colors) == 1) {
						$filtros .= ")";
					}
				}
				
			} else if ($colors == '*' && $doors != '*' && $category == '*') {
				$filtros = "num_doors = '" . $doors . "'";
			} else if ($colors == '*' && $doors == '*' && $category != '*') {
				$filtros = "category = '" . $category . "'";
			} else if ($colors != '*' && $doors != '*' && $category == '*') {
				$filtros = "num_doors = '" . $doors . "' AND";

				for ($i = 0; $i < sizeof($colors); $i++) {
					if ($i == 0) {
						$filtros .= "(color ='" . $colors[$i] . "'";
					} else if ($i == (sizeof($colors) - 1)) {
						$filtros .= " OR color = '" . $colors[$i] . "')";
					} else {
						$filtros .= " OR color = '" . $colors[$i] . "'";
					}
					if (sizeof($colors) == 1) {
						$filtros .= ")";
					}
				}
			} else if ($colors != '*' && $doors == '*' && $category != '*') {
				$filtros = "category = '" . $category . "' AND";

				for ($i = 0; $i < sizeof($colors); $i++) {
					if ($i == 0) {
						$filtros .= "(color ='" . $colors[$i] . "'";
					} else if ($i == (sizeof($colors) - 1)) {
						$filtros .= " OR color = '" . $colors[$i] . "')";
					} else {
						$filtros .= " OR color = '" . $colors[$i] . "'";
					}
					if (sizeof($colors) == 1) {
						$filtros .= ")";
					}
				}
			} else if ($colors == '*' && $doors != '*' && $category != '*') {
				$filtros = "num_doors = '" . $doors . "' AND category = '" . $category . "'";
			} else {
				$filtros = "num_doors = '" . $doors . "' AND category = '" . $category . "' AND";
				for ($i = 0; $i < sizeof($colors); $i++) {
					if ($i == 0) {
						$filtros .= "(color ='" . $colors[$i] . "'";
					} else if ($i == (sizeof($colors) - 1)) {
						$filtros .= " OR color = '" . $colors[$i] . "')";
					} else {
						$filtros .= " OR color = '" . $colors[$i] . "'";
					}
					if (sizeof($colors) == 1) {
						$filtros .= ")";
					}
				}
			}
			if ($operacion_sql == "select") {
				return $this -> dao -> select_shop_filters($this->db, $total_prod, $items_page, $filtros);
			}elseif ($operacion_sql == "count") {
				return $this -> dao -> count_shop_filters($this->db, $filtros);
			}
		}


		public function get_home_filter_BLL($args) {
			$opc_filter = $args[2];
			$type_filter= $args[3];
			$filter = "";
	
			if ($opc_filter == "brand") {
				$brand = $type_filter;
				$filter = "m.id_brand = '" . $brand . "'";
			} else if ($opc_filter == "cate") {
				$category = $type_filter;
				$filter = "ca.name_cat = '" . $category . "'";
			} else {
				$type_motor = $type_filter;
				$filter = "t.name_tmotor = '" . $type_motor . "'";
			}

			return $this -> dao -> select_filter_home($this->db, $args[0], $args[1], $filter);
		}

		public function get_count_cars_home_filters_BLL($args) {
		
			$opc_filter = $args[0];
			$date_filter= $args[1];
			$filter = "";
	
			if ($opc_filter == "brand") {
				$filter = "m.id_brand = '" . $date_filter . "'";
			} else if ($opc_filter == "cate") {
				$filter = "ca.name_cat = '" . $date_filter . "'";
			} else {
				$filter = "t.name_tmotor = '" . $date_filter . "'";
			}

			return $this -> dao -> count_filter_home($this->db, $filter);
		}
		

		// public function get_search_filter_BLL() {
		// 	return $this -> dao -> select_data_type($this->db);
		// }

		public function get_count_more_visit_BLL($id) {
			return $this -> dao -> count_more_visit($this->db, $id);
		}

		public function get_order_filter_BLL($args) {

			if ($args[2] == "0") {
                $Dates_Cars = $this -> dao -> select_all_cars($this->db, 0, 20); 
            } else {
                $Dates_Cars = $this -> dao -> select_all_cars_order($this->db, $args[0], $args[1], $args[2]);
            }
			return $Dates_Cars;
		}

		public function get_count_cars_pag_BLL() {
			return $this -> dao -> select_count_all($this->db);
		}

		public function get_count_order_filter_BLL($args) {
			return $this -> dao -> count_all_cars_order($this->db, $args);
		}

		public function get_count_cars_related_BLL($args) {
			return $this -> dao -> count_more_cars_related($this->db,$args[0]);
		}

		public function get_cars_related_BLL($args) {
			return $this -> dao -> select_cars_related($this->db,$args[0],$args[1],$args[2]);
		}

		// public function get_control_likes_BLL() {
		// 	return $this -> dao -> select_data_type($this->db);
		// }

		// public function get_load_likes_user_BLL() {
		// 	return $this -> dao -> select_data_type($this->db);
		// }


	}
?>