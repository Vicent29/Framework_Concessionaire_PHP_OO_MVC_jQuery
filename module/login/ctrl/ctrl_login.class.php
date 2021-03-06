<?php
    class ctrl_login {
        function login_register_view() {
            common::load_view('top_page_login.php', VIEW_PATH_LOGIN . 'login_register.html');
        }

        function register() {
            echo json_encode(common::load_model('login_model', 'get_register', [ $_POST['username_reg'], $_POST['passwd1_reg'], $_POST['email_reg'] ]));
        }

        function verify_email() {
            echo json_encode(common::load_model('login_model', 'get_verify_email', $_POST['token_email']));
        }

        function login() {
            echo json_encode(common::load_model('login_model', 'get_login',[ $_POST['username_log'], $_POST['passwd_log'] ]));
        }

        function send_recover_email() {
            echo json_encode(common::load_model('login_model', 'get_send_recover_email', [$_POST['email_rec'], $_POST['opc_passswd']]));
        }
        
        function verify_email_token() {
            echo json_encode(common::load_model('login_model', 'get_verify_email_token', $_POST['email_token']));
        }

        function send_new_passwd_modificate() {
            echo json_encode(common::load_model('login_model', 'get_send_new_passwd_modificate', [$_POST['email_token'], $_POST['old_passwd'], $_POST['new_passwd']]));
        }

        function  send_new_passwd_recover() {
            echo json_encode(common::load_model('login_model', 'get_send_new_passwd_recover', [$_POST['email_token'], $_POST['new_passwd1']]));
        }

        function social_login() {
            echo json_encode(common::load_model('login_model', 'get_social_login', [$_POST['id'], $_POST['username'], $_POST['email'], $_POST['avatar'], $_POST['provider']]));
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