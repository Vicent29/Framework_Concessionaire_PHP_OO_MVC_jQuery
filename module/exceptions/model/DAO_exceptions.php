<?php
    include("model/connect.php");
    
	class DAOException{
		function register_exception($type, $lugar){
			$type_error = $type;
			$where = $lugar;
		
        	
            $sql = ("INSERT INTO exceptions (type_error,spot,current_date_time) VALUES ('$type_error','$where', NOW())");

            $conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
            connect::close($conexion);
			return $res;
		}

		function select_exceptions(){
			$sql = "SELECT * FROM exceptions ORDER BY current_date_time";
			
			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
			connect::close($conexion);
            return $res;
		}

		
	}