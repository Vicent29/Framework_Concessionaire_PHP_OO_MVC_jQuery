<?php
class common
{
    public static function load_error()
    {
        require_once(VIEW_PATH_PAGES . 'top_page.html');
        require_once(VIEW_PATH_PAGES . 'header.html');
        require_once(VIEW_PATH_PAGES . 'menu.html');
        require_once(VIEW_PATH_PAGES . '404.html');
        require_once(VIEW_PATH_PAGES . 'footer.html');
       

    } //end load error

    public static function load_view($top_page, $view)
    {
        $top_page = VIEW_PATH_PAGES . $top_page;
        $top_page_default = VIEW_PATH_PAGES . 'top_page.html';
        if ((file_exists($top_page)) && (file_exists($view)) && (file_exists($top_page_default))) {
            require_once($top_page_default);
            require_once($top_page);
            require_once(VIEW_PATH_PAGES . 'header.html');
            require_once(VIEW_PATH_PAGES . 'menu.html');
            require_once($view);
            require_once(VIEW_PATH_PAGES . 'footer.html');
        } else {
            self::load_error();
        } //end else if
    } //end load_view


    function friendlyURL($url) {
        $link = "";
        if (URL_FRIENDLY) {
            $url = explode("&", str_replace("?", "", $url));
            foreach ($url as $key => $value) {
                $aux = explode("=", $value);
                $link .=  $aux[1]."/";
            }
        } else {
            $link = "index.php?" . $url;
        }// end_else
        return SITE_PATH . $link;
    }// end_friendlyURL

}//class