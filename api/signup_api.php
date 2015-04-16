<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/signup_functions.php');
    require_once(ROOT . '/include/login_functions.php');

    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['community'])){
           user_signup($_POST['username'], $_POST['password'], $_POST['community']);
           loginUser($_POST['username'], $_POST['password']);
    }
?>