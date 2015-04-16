<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/signup_functions.php');

    if (isset($_POST['username'])){
        $username = $_POST['username'];
        if (user_already_registered($username)){
            echo 'user exists';
        }else{
            echo 'user not exists';
        }
    }

?>