<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');
    
    require_once(ROOT . '/admin/include/login_functions.php');

    if (user_isLogged()){
        require(ROOT . '/admin/admin-dashboard.php');
    }else{
        require(ROOT . '/admin/admin-login.php');
    }
?>