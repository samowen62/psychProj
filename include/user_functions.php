<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');

    /*
     * This function gets the number of users in our database
     */
    function getUserNumber(){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM user;";
        $result = mysqli_query($dbc, $query);
        $count = mysqli_num_rows($result);
        mysqli_close($dbc);
        return $count;
    }

    /*
     * This function gets current user's username from cookie
     */
    function getCurrentUser(){
        if (isset($_COOKIE['psy_username'])){
            return $_COOKIE['psy_username'];   
        }else{
            return 'Username';
        }
    }

    /*
     * This function returns the community current user belongs to
     */
    function getCurrentUserCommunityName(){
        if (isset($_COOKIE['psy_username'])){
            $username = getCurrentUser();
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $query = "SELECT community.* FROM user 
                        INNER JOIN community_has_user
                            ON community_has_user.username=user.username
                        INNER JOIN community
                            ON community_has_user.community_id=community.id
                        WHERE user.username='{$username}';";
            $result = mysqli_query($dbc, $query);
            while ($row = mysqli_fetch_array($result)){
                $name = $row['name'];
            }
            mysqli_close($dbc);
            return $name;
        }
    }

    function getAvailableGameInCommunity($community){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT user.* FROM community_has_user 
                    INNER JOIN community 
                        ON community.id=community_has_user.community_id
                    INNER JOIN user
                        ON community_has_user.username=user.username
                    INNER JOIN game
                        ON user.username=game.host
                    WHERE community.name='{$community}' AND game.game_status='WAITING';";
        $result = mysqli_query($dbc, $query);
        mysqli_close($dbc);
        return $result;
    }
?>