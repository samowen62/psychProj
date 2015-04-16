<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');

    /*
     * This function gets the number of users in our database
     */
    function getUserNumber(){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM admin;";
        $result = mysqli_query($dbc, $query);
        $count = mysqli_num_rows($result);
        mysqli_close($dbc);
        return $count;
    }

    /*
     * This function gets current user's username from cookie
     */
    function getCurrentUser(){
        if (isset($_COOKIE['psy_admin_username'])){
            return $_COOKIE['psy_admin_username'];   
        }else{
            return 'Username';
        }
    }
?>