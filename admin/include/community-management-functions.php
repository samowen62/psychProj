<?php
    
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');
    require_once(ROOT . '/admin/include/Admin_users.php');
    require_once(ROOT . '/admin/include/Community.php');

    function getCommunities(){
        $community_arr = array();
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM community ORDER BY id ASC;";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $id = $row['id'];
            $name = $row['name'];
            $capacity = $row['capacity'];
            
            $query = "SELECT COUNT(*) AS cnt FROM community_has_user WHERE community_id={$id}";
            $result2 = mysqli_query($dbc, $query);
            while($row2 = mysqli_fetch_array($result2)){
                $user_cnt = $row2['cnt'];
            }
            // echo "<tr><td>{$admin_id}</td><td>{$username}</td</tr>";
            $community = new Community($id, $name, $capacity, $user_cnt);
            array_push($community_arr, $community);
        }
        mysqli_close($dbc);
        echo json_encode($community_arr);
    }

    function addCommunity($name, $capacity){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "INSERT INTO community VALUES(0, '{$name}', {$capacity});";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    function deleteCommunity($name){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "DELETE FROM community WHERE name='{$name}';";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }
    

    if (isset($_POST['request'])){
        if ($_POST['request'] == "get_communities"){
            getCommunities();   
        }
        if ($_POST['request'] == "add_community"){
            addCommunity($_POST['name'], $_POST['capacity']);   
        }
        if ($_POST['request'] == "delete_community"){
            deleteCommunity($_POST['name']);   
        }
        if ($_POST['request'] == "get_current_user"){
            getCurrentUser($_POST['username']);   
        }
    }

?>