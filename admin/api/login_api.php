<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/admin/include/login_functions.php');

    if (isset($_POST['username']) && isset($_POST['password'])){
           loginUser($_POST['username'], $_POST['password']);
    }
?>