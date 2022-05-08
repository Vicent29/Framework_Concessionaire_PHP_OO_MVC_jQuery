<?php
 // @session_start();
    class ctrl_login {
        function login_register_view() {
            common::load_view('top_page_login.php', VIEW_PATH_LOGIN . 'login_register.html');
        }

        function register() {
            echo json_encode(common::load_model('login_model', 'get_register', [ $_POST['username_reg'], $_POST['passwd1_reg'], $_POST['email_reg'] ]));
        }

        function login() {
            echo json_encode(common::load_model('login_model', 'get_login',[ $_POST['username_log'], $_POST['passwd_log'] ]));
        }

        function logout() {
            echo json_encode(common::load_model('login_model', 'get_logout'));
        }

        function data_user() {
            echo json_encode(common::load_model('login_model', 'get_data_user', $_POST['token']));
        }

        function actividad() {
            echo json_encode(common::load_model('login_model', 'get_actividad'));
        }

        function controluser() {
            echo json_encode(common::load_model('login_model', 'get_controluser', $_POST['token']));
        }

        function refresh_token() {
            echo json_encode(common::load_model('login_model', 'get_refresh_token', $_POST['token']));
        }

        function refresh_cookie() {
            echo json_encode(common::load_model('login_model', 'get_refresh_cookie'));
        }

    }
?>