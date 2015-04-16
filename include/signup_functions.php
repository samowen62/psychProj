<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');


    /*
     * This function signup a user.
     * (i.e. stores the given user's info into our database)
     */
    function user_signup($username, $password, $community_id){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "INSERT INTO user VALUES(0, '{$username}', '{$password}');";
        mysqli_query($dbc, $query);
        $query = "INSERT INTO community_has_user VALUES('{$username}', {$community_id});";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    /*
     * This function checks whether a user is already registered
     * 
     * @param $username: username of the user
     */
    function user_already_registered($username){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM user WHERE username='{$username}';";
        $result = mysqli_query($dbc, $query);
        $count = mysqli_num_rows($result);
        if ($count == 0){
            mysqli_close($dbc);
            return false;   
        }else{
            mysqli_close($dbc);
            return true;
        }
    }

    /*
     * This function returns all rows in the community table
     *
     */
    function get_all_community_from_db(){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM community;";
        $result = mysqli_query($dbc, $query);
        mysqli_close($dbc);
        return $result;
    }

    /*
     * This function draws select options for all community
     * based on the community table
     *
     * @param $all_rows:    all rows in community table
     */
    function draw_community_select($all_rows){
        while ($row = mysqli_fetch_array($all_rows)){
            $id = $row['id'];
            $name = $row['name'];
            echo "<option value='{$id}'>{$name}</option>";
        }
    }
?>