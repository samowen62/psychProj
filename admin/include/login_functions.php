<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');
	
    /*
     * This function checks whether there is already a user logged in,
     * if there is, return true; otherwise return false
     */
    function user_isLogged(){
        // check if cookie already contains username
        if (isset($_COOKIE['psy_admin_username'])){
            // connect to database and query the given username and password
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $username = $_COOKIE['psy_admin_username'];
            $query = "SELECT * FROM admin WHERE username='{$username}';";
            $result = mysqli_query($dbc, $query);
            mysqli_close($dbc);
            $count = mysqli_num_rows($result);
            if ($count == 1){ 
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
        
    /*
     * This function tries to login a user, if success, redirect to index.php
     * otherwise redirect to login.php
     *
     * @param username: given username
     * @param password: given password
     */
    function loginUser($username, $password){
        // connect to database and query the given username and password
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        //$crypt_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "SELECT * FROM admin WHERE username='{$username}' AND password='{$password}';";
        $result = mysqli_query($dbc, $query);
		mysqli_close($dbc);
        $count = mysqli_num_rows($result);
        if ($count == 1){
            setcookie("psy_admin_username", $username, time()+3600, "/", "sapir.psych.wisc.edu");
            header("Location: http://sapir.psych.wisc.edu/~zhexuan/Psycho-Project/admin/admin-index.php");
        }else{
            header("Location: http://sapir.psych.wisc.edu/~zhexuan/Psycho-Project/admin/admin-login.php");
        }
    }

    /*
     * This function logouts current user
     */
    function logOutUser(){
        if (user_isLogged()){
            setcookie("psy_admin_username", $username, -1000, "/", "sapir.psych.wisc.edu");
        }
        header("Location: http://sapir.psych.wisc.edu/~zhexuan/Psycho-Project/admin/admin-login.php");
    }
?>