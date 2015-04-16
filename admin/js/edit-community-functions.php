<?php
    
    defined('ROOT') or define('ROOT', '/home/zhexuan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');
    require_once(ROOT . '/admin/include/Admin_users.php');
    require_once(ROOT . '/admin/include/Users.php');
    require_once(ROOT . '/admin/include/Community.php');

    function getAllUsers($community_name){
        $all_users = array();
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT user.id, user.username FROM community
                    INNER JOIN community_has_user
                        ON community.id=community_has_user.community_id
                    INNER JOIN user
                        ON community_has_user.username=user.username
                    WHERE community.name='{$community_name}';";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $id = $row['id'];
            $username = $row['username'];
            
            $query = "SELECT COUNT(*) AS cnt 
                        FROM game 
                        WHERE game.host='{$username}' OR game.guest='{$username}';";
            $result2 = mysqli_query($dbc, $query);
            while($row2 = mysqli_fetch_array($result2)){
                $game_cnt = $row2['cnt'];
            }
            // echo "<tr><td>{$admin_id}</td><td>{$username}</td</tr>";
            $user = new Users($id, $username, $game_cnt);
            array_push($all_users, $user);
        }
        mysqli_close($dbc);
        echo json_encode($all_users);
    }

    function getMigrateCommunity($exclude_community_name){
        $communities = array();
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM community WHERE NOT name='{$exclude_community_name}';";
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
            array_push($communities, $community);
        }
        mysqli_close($dbc);
        echo json_encode($communities);
    }
    
    function updateCapacity($community_name, $capacity){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "UPDATE community 
                    SET capacity={$capacity}
                    WHERE name='{$community_name}'";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    function migrateUser($src_community, $dest_community, $username){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELCT * FROM community WHERE name='{$src_community}'";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $src_id = $row['id'];
        }
        $query = "SELCT * FROM community WHERE name='{$dest_community}'";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $dest_id = $row['id'];
        }
        $query = "UPDATE community_has_user 
                    SET community_id={$dest_id}
                    WHERE community_id={$src_id}";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
        
    }

    if (isset($_POST['request'])){
        if ($_POST['request'] == "get_all_users"){
            getAllUsers($_POST['community_name']);   
        }
        if ($_POST['request'] == "update_capacity"){
            updateCapacity($_POST['community_name'], $_POST['new_capacity']);   
        }
        if ($_POST['request'] == "get_migrate_community"){
            getMigrateCommunity($_POST['community_name']);   
        }
        if ($_POST['request'] == "get_current_user"){
            getCurrentUser($_POST['username']);   
        }
        if ($_POST['request'] == "migrate_user"){
            migrateUser($_POST['src_community'], $_POST['dest_community'], $_POST['username']);
        }
    }

?>