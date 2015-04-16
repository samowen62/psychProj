<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');
    
    require_once(ROOT . '/include/login_functions.php');

    if (user_isLogged()){
        require(ROOT . '/dashboard.php');
    }else{
        require(ROOT . '/login.php');
    }
?>