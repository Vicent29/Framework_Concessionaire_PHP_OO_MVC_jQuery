<?php
	$path = $_SERVER['DOCUMENT_ROOT'] . '/Framework_Concesionaire';
	include($path . "/model/connect.php");
    
	class DAOHome {
		function select_brand() {
			$sql= "SELECT * FROM `brand` ORDER BY name_brand ASC LIMIT 30;";

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
		}// end_select_brand

		function select_categories() {
			$sql= "SELECT *FROM category";

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
		}// end_select_categories

		function select_type_motor() {
			$sql= "SELECT *FROM type_motor ORDER BY cod_tmotor DESC";

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
		}// end_select_type_motor
	
		
	}// end class DAOHome