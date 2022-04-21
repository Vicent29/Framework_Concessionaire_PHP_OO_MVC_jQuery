<?php
$path = $_SERVER['DOCUMENT_ROOT'] . '/Framework_Concesionaire';
include($path . "/module/exceptions/model/DAO_exceptions.php");
if (isset($_SESSION["tiempo"])) {  
    $_SESSION["tiempo"] = time(); //Devuelve la fecha actual
}
switch ($_GET['op']) {

    case '404':
        $daoexcep = new DAOException();
        $rdo = $daoexcep->register_exception($_GET['type'], $_GET['lugar']);

        include("module/exceptions/views/pages/error404.php");
        break;

    case '503';
        $daoexcep = new DAOException();
        $rdo = $daoexcep->register_exception($_GET['type'], $_GET['lugar']);

        include("module/exceptions/views/pages/error503.php");
        break;

    case 'list_exceptions':
        try {
            $daocar = new DAOException();
            $rdo = $daocar->select_exceptions();
        } catch (Exception $e) {
            $callback = 'index.php?module=ctrl_exceptions&op=503&type=503&lugar=Ctrl_car List exceptions';
            die('<script>window.location.href="' . $callback . '";</script>');
        }

        if (!$rdo) {
            $callback = 'index.php?module=ctrl_exceptions&op=503&type=503&lugar=DAO_car List exceptions';
            die('<script>window.location.href="' . $callback . '";</script>');
        } else {
            include("module/exceptions/views/pages/list_exceptions.php");
        }
        break;

    default;
        include("module/exceptions/views/pages/error404.php");
        break;
}
