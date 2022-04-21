<?php
	$path = $_SERVER['DOCUMENT_ROOT'] . '/Framework_Concesionaire';
	include($path . "/model/connect.php");
    
	class DAOSearch {
		function select_type_car(){
			$sql = "SELECT * FROM type_motor";

			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
            connect::close($conexion);
            return $res;
        }

        function select_brand(){
            $sql = "SELECT b.name_brand FROM brand b";

			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
            connect::close($conexion);
            return $res;
        }

        function select_motor_brand($motor){
            $sql = "SELECT DISTINCT b.name_brand
            FROM car c, model m, brand b
            WHERE c.model = m.id_model
            AND m.id_brand = b.name_brand
            AND  c.motor = '$motor'";

			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
            connect::close($conexion);
            return $res;
        }

        function select_auto_motor($auto, $motor){
            $sql = "SELECT DISTINCT C.city
                    FROM car c, type_motor t 
                    WHERE c.motor = t.cod_tmotor
                    AND t.cod_tmotor = '$motor'
                    AND c.city LIKE '$auto%'";
			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
            connect::close($conexion);
            return $res;
        }

        function select_auto_motor_brand($auto, $motor, $brand){
            
            $sql = "SELECT DISTINCT c.city
                    FROM car c, type_motor t, model m
                    WHERE c.motor = t.cod_tmotor
                    AND c.model = m.id_model
                    AND t.cod_tmotor = '$motor'
                    AND m.id_brand = '$brand'
                    AND c.city LIKE '$auto%'";
			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
            connect::close($conexion);
            return $res;
        }

        function select_auto_brand($auto, $brand){
            $sql = "SELECT DISTINCT c.city
                    FROM car c, model m
                    WHERE c.model = m.id_model
                    AND m.id_brand = '$brand'
                    AND c.city LIKE '$auto%'";
			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
            connect::close($conexion);
            return $res;
        }

        function select_auto($auto){
            $sql = "SELECT DISTINCT c.city
                    FROM car c
                    WHERE c.city LIKE '$auto%'";
			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
            connect::close($conexion);
            return $res;
        }
	
		
	}// end class DAOHome