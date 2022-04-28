<?php
    class ctrl_home {
        function view() {
            common::load_view('top_page_home.php', VIEW_PATH_HOME . 'home.html');
        }

        function carousel_brand() {
            echo json_encode(common::load_model('home_model', 'get_carousel_brand'));
        }

        function categoria() {
            echo json_encode(common::load_model('home_model', 'get_categoria'));
        }

        function type() {
            echo json_encode(common::load_model('home_model', 'get_type'));
        }

    }
?>