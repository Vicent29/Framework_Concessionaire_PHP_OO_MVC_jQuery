<?php
$path = $_SERVER['DOCUMENT_ROOT'] . '/Framework_Concesionaire';
include($path . "/module/search/model/DAO_search.php");
if (isset($_SESSION["tiempo"])) {  
    $_SESSION["tiempo"] = time(); //Devuelve la fecha actual
}

switch ($_GET['op']) {
    case 'type_car':
        try {
            $dao = new DAOSearch();
            $rdo = $dao->select_type_car();
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    case 'brand_car':
        try {
            $dao = new DAOSearch();
            $rdo = $dao->select_brand();
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;
    case 'brand_category':
        try {
            $dao = new DAOSearch();
            $rdo = $dao->select_motor_brand($_POST['motor']);
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    case 'autocomplete':
        try {
            $dao = new DAOSearch();
            if (!empty($_POST['type_car']) && empty($_POST['brand_car'])) {            
                $rdo = $dao->select_auto_motor($_POST['complete'], $_POST['type_car']);
            } else if (!empty($_POST['type_car']) && !empty($_POST['brand_car'])) {
                $rdo = $dao->select_auto_motor_brand($_POST['complete'], $_POST['type_car'], $_POST['brand_car']);
            } else if (empty($_POST['type_car']) && !empty($_POST['brand_car'])) {
                $rdo = $dao->select_auto_brand($_POST['complete'], $_POST['brand_car']);
            } else {
                $rdo = $dao->select_auto($_POST['complete']);
            }
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    default:
        include("module/exceptions/views/inc/error404.php");
        break;
}
