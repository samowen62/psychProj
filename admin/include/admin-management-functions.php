<?php
    
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');
    require_once(ROOT . '/admin/include/Admin_users.php');

    function getAllAdmins(){
        $admin_users = array();
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM admin ORDER BY admin_id ASC;";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $admin_id = $row['admin_id'];
            $username = $row['username'];
            $root = $row['root'];
            // echo "<tr><td>{$admin_id}</td><td>{$username}</td</tr>";
            $admin_user = new Admin_users($admin_id, $username, $root);
            array_push($admin_users, $admin_user);
        }
        mysqli_close($dbc);
        echo json_encode($admin_users);
    }

    function addAdminUser($username, $password, $root){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "INSERT INTO admin VALUES(0, '{$username}', '{$password}', {$root});";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    function deleteAdminUser($username){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "DELETE FROM admin WHERE username='{$username}';";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    function changeRoot($username, $root){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "UPDATE admin SET
                        root={$root}
                    WHERE username='{$username}';";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    if (isset($_POST['request'])){
        if ($_POST['request'] == "get_admin_users"){
            getAllAdmins();   
        }
        if ($_POST['request'] == "add_admin_user"){
            addAdminUser($_POST['username'], $_POST['password'], $_POST['root']);   
        }
        if ($_POST['request'] == "change_root"){
            changeRoot($_POST['username'], $_POST['root']);   
        }
        if ($_POST['request'] == "delete_user"){
            deleteAdminUser($_POST['username']);   
        }
        if ($_POST['request'] == "get_current_user"){
            getCurrentUser($_POST['username']);   
        }
    }
?>