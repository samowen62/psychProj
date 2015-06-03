<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');
	
    /*
     * This function checks whether there is already a user logged in,
     * if there is, return true; otherwise return false
     */
    function user_isLogged(){
        // check if cookie already contains username
        if (isset($_COOKIE['psy_username'])){
            // connect to database and query the given username and password
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $username = $_COOKIE['psy_username'];
            $query = "SELECT * FROM user WHERE username='{$username}';";
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


	function currentUser(){
		if(!user_isLogged())
			return null;
		
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            	$username = $_COOKIE['psy_username'];
            	$query = "SELECT * FROM user WHERE username='{$username}';";
            	$result = mysqli_fetch_array(mysqli_query($dbc, $query));
            	mysqli_close($dbc);

		return $result;
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
        $query = "SELECT * FROM user WHERE username='{$username}' AND password='{$password}';";
        $result = mysqli_query($dbc, $query);
		mysqli_close($dbc);
        $count = mysqli_num_rows($result);
        if ($count == 1){
            setcookie("psy_username", $username, time()+3600, "/", "sapir.psych.wisc.edu");
            header("Location: http://sapir.psych.wisc.edu/~yan/Psycho-Project/index.php");
        }else{
            header("Location: http://sapir.psych.wisc.edu/~yan/Psycho-Project/login.php");
        }
    }

    /*
     * This function logouts current user
     */
    function logOutUser(){
        if (user_isLogged()){
            setcookie("psy_username", $username, -1000, "/", "sapir.psych.wisc.edu");
        }
        header("Location: http://sapir.psych.wisc.edu/~yan/Psycho-Project/login.php");
    }
?>
