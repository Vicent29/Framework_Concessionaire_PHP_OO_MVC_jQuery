<?php
$path = $_SERVER['DOCUMENT_ROOT'] . '/Framework_Concesionaire';
include($path . "/module/shop/model/DAO_shop.php");
include($path . "/model/middleware_auth.php");
if (isset($_SESSION["tiempo"])) {
    $_SESSION["tiempo"] = time(); //Devuelve la fecha actual
}


switch ($_GET['op']) {

    case 'list':
        include('module/shop/view/shop.html');
        break;

    case 'all_cars':
        $prod = $_POST['total_prod'];
        $items = $_POST['items_page'];

        try {
            $daoshop = new DAOShop();
            $Dates_Cars = $daoshop->select_all_cars($prod, $items);
        } catch (Exception $e) {
            echo json_encode("error");
        }

        if (!empty($Dates_Cars)) {
            echo json_encode($Dates_Cars);
        } else {
            echo json_encode("error");
        }
        break;

    case 'details_car':

        try {
            $daoshop = new DAOShop();
            $Date_car = $daoshop->select_one_car($_GET['id']);
        } catch (Exception $e) {
            echo json_encode("error");
        }
        try {
            $daoshop_img = new DAOShop();
            $Date_images = $daoshop_img->select_imgs_car($_GET['id']);
        } catch (Exception $e) {
            echo json_encode("error");
        }

        if (!empty($Date_car || $Date_images)) {

            $rdo = array();
            $rdo[0] = $Date_car;
            $rdo[1][] = $Date_images;

            echo json_encode($rdo);
        } else {
            echo json_encode("error");
        }
        break;

    case 'filters':
        try {
            $daoFilter = new DAOShop();
            $Dates_filter_Cars = $daoFilter->select_filter_cars();
        } catch (Exception $e) {
            echo json_encode("error");
        }

        if (!empty($Dates_filter_Cars)) {
            echo json_encode($Dates_filter_Cars);
            exit;
        } else {
            echo json_encode("error");
        }
        break;

    case 'home_filter':
        try {
            $daoFilter = new DAOShop();
            $Dates_filter_Cars = $daoFilter->select_filter_home();
        } catch (Exception $e) {
            echo json_encode("error");
        }
        if (!empty($Dates_filter_Cars)) {
            echo json_encode($Dates_filter_Cars);
            exit;
        } else {
            echo json_encode("error");
        }

        break;

    case 'search_filter':
        $all_search = $_POST['search'];
        $city = ($all_search[0]['city']);
        $motor = ($all_search[1]['type_car'][0]);
        $brand = ($all_search[2]['brand_car']);

        try {
            $dao = new DAOShop();
            if (($motor != "0") && ($brand == "0") && ($city == "0")) {
                $rdo = $dao->select_motor_search($motor);
            } else if (($motor == "0") && ($brand != "0") && ($city == "0")) {
                $rdo = $dao->select_brand_search($brand);
            } else if (($motor == "0") && ($brand == "0") && ($city != "0")) {
                $rdo = $dao->select_city_search($city);
            } else if (($motor != "0") && ($brand != "0") && ($city == "0")) {
                $rdo = $dao->select_motor_brand_search($motor, $brand);
            } else if (($motor == "0") && ($brand != "0") && ($city != "0")) {
                $rdo = $dao->select_brand_city_search($brand, $city);
            } else if (($motor != "0") && ($brand == "0") && ($city != "0")) {
                $rdo = $dao->select_motor_city_search($motor, $city);
            } else if (($motor != "0") && ($brand != "0") && ($city != "0")) {
                $rdo = $dao->select_all_search($motor, $brand, $city);
            } else {
                $rdo = $dao->select_all_cars(0, 20);
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

    case 'count_more_visit':
        $id_car_visit = $_POST['id_car'];

        try {
            $daoCount = new DAOShop();
            $Dao_count_Car = $daoCount->count_more_visit($id_car_visit);
        } catch (Exception $e) {
            echo json_encode("error");
        }
        break;
    case 'order_filter':

        $opc_order = $_GET['order'];
        $total_prod = $_POST['total_prod'];
        $items_page = $_POST['items_page'];

        try {
            $daoshop = new DAOShop();
            if ($opc_order == "0") {
                $Dates_Cars = $daoshop->select_all_cars(0, 20);
            } else {
                $Dates_Cars = $daoshop->select_all_cars_order($opc_order, $total_prod, $items_page);
            }
        } catch (Exception $e) {
            echo json_encode("error");
        }

        if (!empty($Dates_Cars)) {
            echo json_encode($Dates_Cars);
        } else {
            echo json_encode("error");
        }
        break;
    case 'count_cars_pag':
        try {
            $dao = new DAOShop();
            $rdo = $dao->select_count_all();
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
    case 'count_cars_filters':
        try {
            $dao = new DAOShop();
            $rdo = $dao->count_filter_cars();
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
    case 'count_order_filter':
        $order = $_POST['value_orderby'][0]['order'];

        try {
            $dao = new DAOShop();
            $rdo = $dao->count_all_cars_order($order);
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

    case 'count_cars_related':
        $type_car = $_POST['type_car'];
        try {
            $dao = new DAOShop();
            $rdo = $dao->count_more_cars_related($type_car);
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
    case 'cars_related':
        $type_car = $_POST['type'];
        $loaded =  $_POST['loaded'];
        $items =  $_POST['items'];
        try {
            $dao = new DAOShop();
            $rdo = $dao->select_cars_related($type_car, $loaded, $items);
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
    case 'control_likes':
        $token = $_POST['token'];
        $id_car = $_POST['id_car'];

        try {
            $json = descode_token($token);
            $dao = new DAOShop();
            $rdo = $dao->select_likes($id_car, $json['username']);
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
            if (count($dinfo) === 0) {
                $dao = new DAOShop();
                $rdo = $dao->like($id_car, $json['username']);
                echo json_encode("0");
            } else {
                $dao = new DAOShop();
                $rdo = $dao->dislike($id_car, $json['username']);
                echo json_encode("1");
            }
        }
        break;
    case 'load_likes_user';
        try {
            $json = descode_token($_POST['token']);
            $dao = new DAOShop();
            $rdo = $dao->select_load_likes($json['username']);
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



    default;
        include("module/exceptions/views/inc/error404.php");
        break;
}
