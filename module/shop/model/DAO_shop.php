<?php
$path = $_SERVER['DOCUMENT_ROOT'] . '/Framework_Concesionaire';
include($path . "/model/connect.php");

class DAOShop
{

	function select_all_cars($total_prod,$items_page){

		$sql = "SELECT * 
		FROM car c, model m
		WHERE c.model = m.id_model  
		ORDER BY c.count DESC
		LIMIT $total_prod, $items_page";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} // end_sellect_all_cars

	function select_one_car($id)
	{

		$sql = "SELECT *
		FROM car c, model m, type_motor t, category ca
		WHERE c.id_car = '$id'
		AND  c.model = m.id_model 
		AND c.category = ca.id_cat
		AND c.motor = t.cod_tmotor";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql)->fetch_object();
		connect::close($conexion);

		return $res;
	} // end_select_one_car	

	function select_imgs_car($id)
	{

		$sql = "SELECT i.id_car, i.img_cars
			    FROM img_cars i
			    WHERE i.id_car = '$id'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$imgArray = array();
		if (mysqli_num_rows($res) > 0) {
			foreach ($res as $row) {
				array_push($imgArray, $row);
			}
		}
		return $imgArray;
	} // end_sellect_img_cars

	function select_filter_cars(){
		$total_prod =  $_POST['total_prod'];
		$items_page =  $_POST['items_page'];

		//coger las variable de cada uno de lso filtros que vienen parseadas de antes
		$doors = $_GET['doors'];
		$color = $_GET['color'];
		$category = $_GET['category'];

		//Guardaremos los filtros pulsados dependoendo de si estan llenos o no
		$filtros = "";


		if ($color != '*' && $doors == '*' && $category == '*') {

			$exp_colors = explode(",", $color);
			for ($i = 0; $i < sizeof($exp_colors); $i++) {
				if ($i == 0) {
					$filtros .= "(color ='" . $exp_colors[$i] . "'";
				} else if ($i == (sizeof($exp_colors) - 1)) {
					$filtros .= " OR color = '" . $exp_colors[$i] . "')";
				} else {
					$filtros .= " OR color = '" . $exp_colors[$i] . "'";
				}
				if (sizeof($exp_colors) == 1) {
					$filtros .= ")";
				}
			}
		} else if ($color == '*' && $doors != '*' && $category == '*') {
			$filtros = "num_doors = '" . $doors . "'";
		} else if ($color == '*' && $doors == '*' && $category != '*') {
			$filtros = "category = '" . $category . "'";
		} else if ($color != '*' && $doors != '*' && $category == '*') {
			$filtros = "num_doors = '" . $doors . "' AND";

			$exp_colors = explode(",", $color);
			for ($i = 0; $i < sizeof($exp_colors); $i++) {
				if ($i == 0) {
					$filtros .= "(color ='" . $exp_colors[$i] . "'";
				} else if ($i == (sizeof($exp_colors) - 1)) {
					$filtros .= " OR color = '" . $exp_colors[$i] . "')";
				} else {
					$filtros .= " OR color = '" . $exp_colors[$i] . "'";
				}
				if (sizeof($exp_colors) == 1) {
					$filtros .= ")";
				}
			}
		} else if ($color != '*' && $doors == '*' && $category != '*') {
			$filtros = "category = '" . $category . "' AND";

			$exp_colors = explode(",", $color);
			for ($i = 0; $i < sizeof($exp_colors); $i++) {
				if ($i == 0) {
					$filtros .= "(color ='" . $exp_colors[$i] . "'";
				} else if ($i == (sizeof($exp_colors) - 1)) {
					$filtros .= " OR color = '" . $exp_colors[$i] . "')";
				} else {
					$filtros .= " OR color = '" . $exp_colors[$i] . "'";
				}
				if (sizeof($exp_colors) == 1) {
					$filtros .= ")";
				}
			}
		} else if ($color == '*' && $doors != '*' && $category != '*') {
			$filtros = "num_doors = '" . $doors . "' AND category = '" . $category . "'";
		} else {
			$filtros = "num_doors = '" . $doors . "' AND category = '" . $category . "' AND";
			$exp_colors = explode(",", $color);
			for ($i = 0; $i < sizeof($exp_colors); $i++) {
				if ($i == 0) {
					$filtros .= "(color ='" . $exp_colors[$i] . "'";
				} else if ($i == (sizeof($exp_colors) - 1)) {
					$filtros .= " OR color = '" . $exp_colors[$i] . "')";
				} else {
					$filtros .= " OR color = '" . $exp_colors[$i] . "'";
				}
				if (sizeof($exp_colors) == 1) {
					$filtros .= ")";
				}
			}
		}

		if ($doors == '*' && $color == '*' && $category == '*') {
			$sql = "SELECT c.*,m.id_brand, m.name_model, t.name_tmotor, ca.name_cat
			FROM car c, model m, type_motor t, category ca
			WHERE  c.model = m.id_model 
			AND c.category = ca.id_cat
			AND c.motor = t.cod_tmotor
			LIMIT $total_prod, $items_page";
					
		} else {
			$sql = "SELECT c.*,m.id_brand, m.name_model, t.name_tmotor, ca.name_cat
			FROM car c, model m, type_motor t, category ca
			WHERE  c.model = m.id_model 
			AND c.category = ca.id_cat
			AND c.motor = t.cod_tmotor
			AND $filtros
			LIMIT $total_prod, $items_page";

			
		}

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);
		
		$filtArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$filtArray[] = $row;
			}
		}
		return $filtArray;
	} // end_sellect_img_cars

	function select_filter_home()
	{

		$opc_filter = $_GET['opc'];
		$filter = "";

		if ($opc_filter == "brand") {
			$brand = $_GET['brand'];
			$filter = "m.id_brand = '" . $brand . "'";
		} else if ($opc_filter == "cate") {
			$category = $_GET['category'];
			$filter = "ca.name_cat = '" . $category . "'";
		} else {
			$type_motor = $_GET['motor'];
			$filter = "t.name_tmotor = '" . $type_motor . "'";
		}

		$sql = "SELECT c.*,m.id_brand, m.name_model, t.name_tmotor, ca.name_cat
    	FROM car c, model m, type_motor t, category ca
    	WHERE  c.model = m.id_model 
    	AND c.category = ca.id_cat
    	AND c.motor = t.cod_tmotor
    	AND $filter";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$carArray = array();
		if (mysqli_num_rows($res) > 0) {
			foreach ($res as $row) {
				array_push($carArray, $row);
			}
		}
		return $carArray;
	}

	//SEARCH////

	function select_motor_search($motor)
	{
		$sql = "SELECT *
			FROM car c, type_motor t ,model m
			WHERE c.motor = t.cod_tmotor
			AND c.model = m.id_model
			AND t.cod_tmotor= '$motor'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} //END-select_motor_search

	function select_brand_search($brand)
	{
		$sql = "SELECT *
				FROM car c,model m
				WHERE c.model = m.id_model
				AND m.id_brand= '$brand'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} // END-select_brand_search

	function select_city_search($city)
	{
		$sql = "SELECT *
				FROM car c, model m
				WHERE c.model = m.id_model
				AND c.city= '$city'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} // END-select_city_search

	function select_motor_brand_search($motor, $brand)
	{
		$sql = "SELECT *
				FROM car c, type_motor t, model m
				WHERE c.model = m.id_model
				AND c.motor = t.cod_tmotor
				AND t.cod_tmotor = '$motor'
				AND m.id_brand = '$brand'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} // END-select_motor_brand_search

	function select_brand_city_search($brand, $city)
	{
		$sql = "SELECT *
				FROM car c, model m
				WHERE c.model = m.id_model	
				AND m.id_brand= '$brand'
				AND c.city= '$city'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} // END-select_brand_city_search

	function select_motor_city_search($motor, $city){
		$sql = "SELECT *
				FROM car c, type_motor t ,model m
				WHERE c.motor = t.cod_tmotor
				AND c.model = m.id_model
				AND t.cod_tmotor= '$motor'
				AND c.city= '$city'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} // END-select_motor_city_search

	function select_all_search($motor, $brand, $city)
	{
		$sql = "SELECT *
				FROM car c, type_motor t ,model m
				WHERE c.motor = t.cod_tmotor
				AND c.model = m.id_model
				AND t.cod_tmotor= '$motor'
				AND m.id_brand= '$brand'
				AND c.city= '$city'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} // END-select_all_search

	function count_more_visit($id)
	{
		$sql = "UPDATE car c SET c.count = c.count+1
				WHERE C.id_car = '$id'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);
	}
	function select_all_cars_order($order,$total_prod,$items_page)
	{

		$sql = "SELECT * 
		FROM car c, model m
		WHERE c.model = m.id_model  
		ORDER BY c.$order ASC
		LIMIT $total_prod,$items_page";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} // end_sellect_all_cars_order

	// COUNT PAGINATION
	function select_count_all()
	{
		$sql = "SELECT COUNT(*) AS n_prod FROM car;";
		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);
		return $res;
	}

	function count_filter_cars(){
		
		//coger las variable de cada uno de lso filtros que vienen parseadas de antes
		$doors = $_GET['doors'];
		$color = $_GET['color'];
		$category = $_GET['category'];

		//Guardaremos los filtros pulsados dependoendo de si estan llenos o no
		$filtros = "";

		if ($color != '*' && $doors == '*' && $category == '*') {

			$exp_colors = explode(",", $color);
			for ($i = 0; $i < sizeof($exp_colors); $i++) {
				if ($i == 0) {
					$filtros .= "(color ='" . $exp_colors[$i] . "'";
				} else if ($i == (sizeof($exp_colors) - 1)) {
					$filtros .= " OR color = '" . $exp_colors[$i] . "')";
				} else {
					$filtros .= " OR color = '" . $exp_colors[$i] . "'";
				}
				if (sizeof($exp_colors) == 1) {
					$filtros .= ")";
				}
			}
		} else if ($color == '*' && $doors != '*' && $category == '*') {
			$filtros = "num_doors = '" . $doors . "'";
		} else if ($color == '*' && $doors == '*' && $category != '*') {
			$filtros = "category = '" . $category . "'";
		} else if ($color != '*' && $doors != '*' && $category == '*') {
			$filtros = "num_doors = '" . $doors . "' AND";

			$exp_colors = explode(",", $color);
			for ($i = 0; $i < sizeof($exp_colors); $i++) {
				if ($i == 0) {
					$filtros .= "(color ='" . $exp_colors[$i] . "'";
				} else if ($i == (sizeof($exp_colors) - 1)) {
					$filtros .= " OR color = '" . $exp_colors[$i] . "')";
				} else {
					$filtros .= " OR color = '" . $exp_colors[$i] . "'";
				}
				if (sizeof($exp_colors) == 1) {
					$filtros .= ")";
				}
			}
		} else if ($color != '*' && $doors == '*' && $category != '*') {
			$filtros = "category = '" . $category . "' AND";

			$exp_colors = explode(",", $color);
			for ($i = 0; $i < sizeof($exp_colors); $i++) {
				if ($i == 0) {
					$filtros .= "(color ='" . $exp_colors[$i] . "'";
				} else if ($i == (sizeof($exp_colors) - 1)) {
					$filtros .= " OR color = '" . $exp_colors[$i] . "')";
				} else {
					$filtros .= " OR color = '" . $exp_colors[$i] . "'";
				}
				if (sizeof($exp_colors) == 1) {
					$filtros .= ")";
				}
			}
		} else if ($color == '*' && $doors != '*' && $category != '*') {
			$filtros = "num_doors = '" . $doors . "' AND category = '" . $category . "'";
		} else {
			$filtros = "num_doors = '" . $doors . "' AND category = '" . $category . "' AND";
			$exp_colors = explode(",", $color);
			for ($i = 0; $i < sizeof($exp_colors); $i++) {
				if ($i == 0) {
					$filtros .= "(color ='" . $exp_colors[$i] . "'";
				} else if ($i == (sizeof($exp_colors) - 1)) {
					$filtros .= " OR color = '" . $exp_colors[$i] . "')";
				} else {
					$filtros .= " OR color = '" . $exp_colors[$i] . "'";
				}
				if (sizeof($exp_colors) == 1) {
					$filtros .= ")";
				}
			}
		}

		if ($doors == '*' && $color == '*' && $category == '*') {
			$sql = "SELECT COUNT(*) AS n_prod
					FROM car c, model m, type_motor t, category ca
			 		WHERE  c.model = m.id_model 
					AND c.category = ca.id_cat
					AND c.motor = t.cod_tmotor";		
		} else {
			$sql = "SELECT COUNT(*) AS n_prod
					FROM car c, model m, type_motor t, category ca
					WHERE  c.model = m.id_model 
					AND c.category = ca.id_cat
					AND c.motor = t.cod_tmotor
					AND $filtros";
		}

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);
		
		$filtArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$filtArray[] = $row;
			}
		}
		return $filtArray;
	} 

	function count_all_cars_order($order){
		$sql = "SELECT COUNT(*) AS n_prod
		FROM car c, model m
		WHERE c.model = m.id_model  
		ORDER BY c.$order ASC";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	} // end_sellect_all_cars

	// MORE CARS RELATED
	function count_more_cars_related($type_car){
		$sql = "SELECT COUNT(*) AS n_prod
				FROM car c 
				WHERE c.motor = '$type_car'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;

	}
	function select_cars_related($type, $loaded, $items){

		$sql = "SELECT * 
				FROM car c, model m
				WHERE c.model = m.id_model 
				AND c.motor = '$type'
				LIMIT $loaded, $items";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		
		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	}

	// LIKES

	function select_load_likes($username){
        $sql = "SELECT l.id_car FROM likes l WHERE l.id_user = (SELECT u.id_user FROM users u WHERE u.username = '$username')";
        $conexion = connect::con();
        $res = mysqli_query($conexion, $sql);
        connect::close($conexion);
        return $res;
    }

    function select_likes($id_car, $username){
        $sql = "SELECT l.id_car FROM likes l
				WHERE l.id_user = (SELECT u.id_user FROM users u WHERE u.username = '$username')
				AND l.id_car = '$id_car'";
        $conexion = connect::con();
        $res = mysqli_query($conexion, $sql);
        connect::close($conexion);
        return $res;
    }

    function like($id_car, $username){
        $sql = "INSERT INTO likes (id_user, id_car) VALUES ((SELECT  u.id_user FROM users u WHERE u.username= '$username') ,'$id_car');";
        $conexion = connect::con();
        $res = mysqli_query($conexion, $sql);
        connect::close($conexion);
        return $res;
    }

    function dislike($id_car, $username){
        $sql = "DELETE FROM likes WHERE id_car='$id_car' AND id_user=(SELECT  u.id_user FROM users u WHERE u.username= '$username')";
        $conexion = connect::con();
        $res = mysqli_query($conexion, $sql);
        connect::close($conexion);
        return $res;
    }


	
}// end class DAOShop