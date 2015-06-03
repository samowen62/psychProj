<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');
    require_once(ROOT . '/include/user_functions.php');

    /*
     * This function creates a new game for the given user
     * and will leave the join_user as NULL. 
     * i.e. waiting for the other user to join
     *
     * @param $username:        the given user
     */
    function createGame($username){
        // first delete any WAITING games
        deleteGame($username);
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);   
        $query = "INSERT INTO game VALUES (0, '{$username}', NULL, 'WAITING', '{$username}|NULL', DATE_ADD(NOW(), INTERVAL 1 HOUR), NULL, NULL, NULL);";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    /*
     * This function deletes an existing game created by the given user
     *
     * @param $username:        the given user
     */
    function deleteGame($username){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);   
        $query = "DELETE FROM game WHERE host='{$username}' AND game_name='{$username}|NULL';";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    function quitGame($host, $guest){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM game WHERE host='{$host}' AND guest='{$guest}' AND game_status='PLAYING';";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $game_id = $row['game_id'];   
        }
        $query = "DELETE FROM game_has_turn WHERE game_id={$game_id};";
        mysqli_query($dbc, $query);
        $query = "DELETE FROM game WHERE host='{$host}' AND guest='{$guest}' AND game_status='PLAYING';";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    /*
     * This function poll game status as a host to see if anyone
     * joins his game or not
     *
     * @param $username: host's username
     */
    function hostPollGame($username){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM game WHERE host='{$username}' AND game_status='WAITING';";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) == 0){
            $query = "SELECT * FROM game WHERE host='{$username}' AND game_status='PLAYING';";
            $result = mysqli_query($dbc, $query);
            if (mysqli_num_rows($result) == 0){
                mysqli_close($dbc);
                echo "noUser";   
                return;
            }else{
                while ($row = mysqli_fetch_array($result)){
                    $join_user = $row['guest'];
                }
                echo $join_user;
                mysqli_close($dbc);
                return;
            }
        }
        while ($row = mysqli_fetch_array($result)){
            $join_user = $row['guest'];
        }
        if ($join_user === NULL){
            mysqli_close($dbc);
            echo "noUser";   
            return;
        }else{
            mysqli_close($dbc);
            echo $join_user;
            return;
        }
    }

    /*
     * This function poll game status as a host to see if there
     * are available games or not
     *
     * @param $community: community the guest belongs to
     */
    function guestPollGame($community){
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
        if (mysqli_num_rows($result) == 0){
            echo "<li><a href='#'>No available games now</a></li>";
        }else{
            $echo_content = "";
            while ($row = mysqli_fetch_array($result)){
                $username = $row['username'];
                if ($username != getCurrentUser()){
                    $echo_content .= "<li>
                                        <a class='user-option special-anchor-direct' href='#message-play-wrapper' data-slide='message-play-wrapper' data-guestuser='" . getCurrentUser() . "' data-hostuser='" . $username . "'>
                                            {$username}
                                        </a>
                                    </li>";
                }
            }
            echo $echo_content;
        }
    }

    /* 
     * This function joins a guset to a game
     *
     * @param $guest_name:      guest's username
     * @param $host_name:       host's username
     */ 
    function guestJoinGame($host_name, $guest_name){
       error_reporting(E_ALL);
	 $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT user.*, game.*, community.capacity FROM community_has_user
                    INNER JOIN community
                        ON community.id=community_has_user.community_id
                    INNER JOIN user
                        ON community_has_user.username=user.username
                    INNER JOIN game
                        ON user.username=game.host
                    WHERE game.host='{$host_name}' AND game.game_status='WAITING';";
        $result = mysqli_fetch_array(mysqli_query($dbc, $query));
	
	$num = 2;
	$guest_num = 'guest';
	if($result['guest'] == null){
		$guest_num = 'guest';
	}else if($result['guest_2'] == null){
		$guest_num = 'guest_2';
		$num = 3;
	}else if($result['guest_3'] == null){
                $guest_num = 'guest_3';
		$num = 4;
	}else if($result['guest_4'] == null){
                $guest_num = 'guest_4';
		$num = 5;
	}


	if($num < (int)$result['capacity']){
        	$query = "UPDATE game SET 
                	    {$guest_num}='{$guest_name}',
                	    game_name='{$host_name}|{$guest_name}',
                	    game_status='WAITING'
                	    WHERE host='{$host_name}' AND game_status='WAITING';";
        	$result = mysqli_query($dbc, $query);
        	mysqli_close($dbc);
	    	
	
	}else if($num == (int)$result['capacity']){
		$query = "UPDATE game SET 
                            {$guest_num}='{$guest_name}',
                            game_name='{$host_name}|{$guest_name}',
                            game_status='PLAYING'
                            WHERE host='{$host_name}' AND game_status='WAITING';";
                $result = mysqli_query($dbc, $query);
                mysqli_close($dbc);


	}
    }

    /*
     * This function starts a new game
     */
    function startGame($host_name, $guest_name, $turn){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT COUNT(*) AS cnt FROM grid;";
        $result = mysqli_query($dbc, $query);
        // random a grid id first
        while ($row = mysqli_fetch_array($result)){  
            $total_grid_num = $row['cnt'];
        }
        if ($total_grid_num == 1)
            $random_grid_id = 1;
        else{
            $random_grid_id = rand(1, $total_grid_num);
        }
        // create a new turn
        $query = "INSERT INTO turn VALUES(0, {$random_grid_id}, 0, {$turn}, '', '', '{$host_name}', '{$guest_name}', 0, 0, 'PLAYING', 'PLAYING');";
        echo $query;
        mysqli_query($dbc, $query);
        
        // link turn and game, first get game id
        $query = "SELECT * FROM game WHERE game_name='{$host_name}|{$guest_name}' AND game_status='PLAYING';";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) == 0){
            $query = "SELECT * FROM game WHERE game_name='{$guest_name}|{$host_name}' AND game_status='PLAYING';";
            $result = mysqli_query($dbc, $query);
        }
        while ($row = mysqli_fetch_array($result)){
            $game_id = $row['game_id'];   
        }
        // echo $query;
        // then get turn id
        $query = "SELECT * FROM turn WHERE host='{$host_name}' AND guest='{$guest_name}' AND game_status='PLAYING';";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $turn_id = $row['turn_id'];
        }
        $query = "INSERT INTO game_has_turn VALUES({$game_id}, {$turn_id});";
        echo $query;
        mysqli_query($dbc, $query);
        
        mysqli_close($dbc);
    }

    /*
     * This function retrieves grid for a turn
     *
     * @param $host_name:
     * @param $guset_name:
     */
    function retrieveGrid($host_name, $guest_name, $action){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT grid.* FROM game
                    INNER JOIN game_has_turn
                        ON game.game_id=game_has_turn.game_id
                    INNER JOIN turn
                        ON turn.turn_id=game_has_turn.turn_id
                    INNER JOIN grid
                        ON turn.grid_id=grid.grid_id
                    WHERE game.host='{$host_name}' AND game.guest='{$guest_name}' AND game.game_status='PLAYING';";
        $result = mysqli_query($dbc, $query);
        $count = mysqli_num_rows($result);
        if ($count == 0){
            $query = "SELECT grid.* FROM game
                    INNER JOIN game_has_turn
                        ON game.game_id=game_has_turn.game_id
                    INNER JOIN turn
                        ON turn.turn_id=game_has_turn.turn_id
                    INNER JOIN grid
                        ON turn.grid_id=grid.grid_id
                    WHERE game.host='{$guest_name}' AND game.guest='{$host_name}' AND game.game_status='PLAYING';";
            $result = mysqli_query($dbc, $query);
            $count = mysqli_num_rows($result);
        }
        if ($count == 0){
            echo "no grid found";   
        }else{
            // get item ids
            while ($row = mysqli_fetch_array($result)){
                $grid_item_id_arr = array(
                    "0" => $row['item1'],
                    "1" => $row['item2'],
                    "2" => $row['item3'],
                    "3" => $row['item4'],
                    "4" => $row['item5'],
                    "5" => $row['item6'],
                    "6" => $row['item7'],
                    "7" => $row['item8'],
                    "8" => $row['item9']  
                );
                $grid_id = $row['grid_id'];
            }
            // get each item
            $grid_item_arr = array();
            for ($i = 0; $i < count($grid_item_id_arr); $i ++){
                $grid_item_id_temp = $i;
                $query = "SELECT * FROM grid_item WHERE grid_item_id={$grid_item_id_temp};";
                $result = mysqli_query($dbc, $query);
                while ($row = mysqli_fetch_array($result)){
                    $grid_item = $row['grid_item'];
                }
                array_push($grid_item_arr, $grid_item);
            }
            // randomize grid items
            for ($i = 0; $i < 5; $i ++){
                $index = rand(0, 8);
                $index2 = ($index + 2) % 9;
                $temp = $grid_item_arr[$index];
                $temp2 = $grid_item_id_arr[$index];
                $grid_item_arr[$index] = $grid_item_arr[$index2];
                $grid_item_id_arr[$index] = $grid_item_id_arr[$index2];
                $grid_item_arr[$index2] = $temp;
                $grid_item_id_arr[$index2] = $temp2;
            }
            // update grid order in turn table
            if ($action == 'host_retrieve_grid'){
                $host_grid_order = "";
                for ($i = 0; $i < count($grid_item_id_arr); $i ++){
                    if ($i != (count($grid_item_id_arr) - 1)){
                        $host_grid_order = $host_grid_order . $grid_item_id_arr[$i] . '|';
                    }else{
                        $host_grid_order = $host_grid_order . $grid_item_id_arr[$i];
                    }
                }
                $query = "UPDATE turn SET host_grid_order='{$host_grid_order}' WHERE host='{$host_name}' AND guest='{$guest_name}' AND game_status='PLAYING';";
            }else{
                $guest_grid_order = "";   
                for ($i = 0; $i < count($grid_item_id_arr); $i ++){
                    if ($i != (count($grid_item_id_arr) - 1)){
                        $guest_grid_order = $guest_grid_order . $grid_item_id_arr[$i] . '|';
                    }else{
                        $guest_grid_order = $guest_grid_order . $grid_item_id_arr[$i];
                    }
                }
                $query = "UPDATE turn SET guest_grid_order='{$guest_grid_order}' WHERE host='{$host_name}' AND guest='{$guest_name}' AND game_status='PLAYING';";
            }
            mysqli_query($dbc, $query);
        
		$imgLoc = "http://sapir.psych.wisc.edu/wp-content/uploads/";

	    // echo grid
		$alt = 17;
            echo "<ul class='grid_list' data-gridid='$grid_id'>";
            for ($i = 0; $i < count($grid_item_arr); $i ++){
                $grid_item_id_temp = $grid_item_id_arr[$i];
                $grid_item_temp = isset($grid_item_arr[$i]) ? $grid_item_arr[$i] : $alt;
		$alt++;
                if ($i % 3 == 0){
                    echo "<li class='grid_row'>
                            <ul class='grid_row_list'>
                                <li class='grid_item' data-itemid='{$grid_item_temp}'><img src='".$imgLoc.$grid_item_id_temp."' alt='item ".$grid_item_id_temp."' /></li>";
                }
                if ($i % 3 == 1){
                    echo "<li class='grid_item' data-itemid='{$grid_item_temp}'><img src='".$imgLoc.$grid_item_id_temp."' alt='item ".$grid_item_id_temp."' /></li>";
                }
                if ($i % 3 == 2){
                    echo "<li class='grid_item' data-itemid='{$grid_item_temp}'><img src='".$imgLoc.$grid_item_id_temp."' alt='item ".$grid_item_id_temp."' /></li></ul></li>";
                }
            }
            echo "</ul>";
            if ($action == 'host_retrieve_grid'){
                echo "<script>
                    $( document ).on( 'mousemove', function( event ) {
                        mouse_x = event.pageX;
                        mouse_y = event.pageY;
                    });
                    $('.grid_list .grid_row .grid_row_list .grid_item').each(function(){
                        $(this).click(hostSelectsItem);
                    });</script>";
            }
        }
        
        mysqli_close($dbc);         
    }

    function updateGameStatusPOST($host_name, $guest_name, $previous_status, $status){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "UPDATE game
                    SET game_status='{$status}'
                    WHERE host='{host_name}' AND guest='{$guest_name}' AND game_status='{$previous_status}';";
        mysqli_query($dbc, $query);
        $query = "UPDATE turn
                    SET game_status='{$status}'
                    WHERE host='{host_name}' AND guest='{$guest_name}' AND game_status='{$previous_status}';";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    /*
     * This function gets the current turn number of a given game
     */
    function getCurrentTurnNum($host_name, $guest_name){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM turn WHERE host='{$host_name}' AND guest='{$guest_name}' AND game_status='PLAYING';";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) == 0){
            echo 'turn not found';   
        }else{
            while ($row = mysqli_fetch_array($result)){
                $turn_num = $row['turn_number'];   
            }
        }
        echo $turn_num;
        mysqli_close($dbc);
    }

    /*
     * 
     */
    function hostSelectsItem($host_name, $guest_name, $selection){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "UPDATE turn
                    SET host_select_item={$selection}
                    WHERE host='{$host_name}' AND guest='{$guest_name}' AND game_status='PLAYING';";
        // echo $query;
        mysqli_query($dbc, $query);
        createNewMessage($dbc, $host_name, $guest_name);
        // bind message to turn
        $query = "SELECT * FROM message WHERE sender='{$host_name}' AND recipient='{$guest_name}' AND content='new_message';";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $message_id = $row['message_id'];   
        }
        // echo $query;
        $query = "UPDATE turn
                    SET message_id={$message_id}
                    WHERE host='{$host_name}' AND guest='{$guest_name}' AND game_status='PLAYING';";
        mysqli_query($dbc, $query);
        // echo $query;
        mysqli_close($dbc);
    }

    function createNewMessage($dbc, $sender, $recipient){
        echo 'aaaa';
        $query = "INSERT INTO message VALUES(0, '{$sender}', '{$recipient}', 'new_message')";
        mysqli_query($dbc, $query);
    }

    function hostSendMessage($host, $guest, $content){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM message WHERE sender='{$host}' AND recipient='{$guest}' AND content='new_message';";
        // echo $query;
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) != 0){
            $query = "UPDATE message
                        SET content='{$content}'
                        WHERE sender='{$host}' AND recipient='{$guest}';";
            // echo $query;
            mysqli_query($dbc, $query);
        }else{
            $query = "SELECT * FROM message WHERE sender='{$host}' AND recipient='{$guest}';";
            // echo $query;
            $result = mysqli_query($dbc, $query);
            while ($row = mysqli_fetch_array($result)){
                $old_content = $row['content'];   
            }
            $store_content = $old_content . '|' . $content;
            $query = "UPDATE message
                        SET content='{$store_content}'
                        WHERE sender='{$host}' AND recipient='{$guest}';";
            // echo $query;
            mysqli_query($dbc, $query);
        }
        $query = "UPDATE turn
                    SET game_status='HOST_SENT'
                    WHERE host='{$host}' AND guest='{$guest}' AND game_status='PLAYING';";
        mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }

    function guestRetrieveMessage($host, $guest){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM turn WHERE host='{$host}' AND guest='{$guest}' AND game_status='HOST_SENT';";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) == 0){
            echo 'not received';
        }else{
            while ($row = mysqli_fetch_array($result)){
                $msg_id = $row['message_id'];   
            }
            $query = "SELECT * FROM message WHERE message_id={$msg_id};";
            $result = mysqli_query($dbc, $query);
            while ($row = mysqli_fetch_array($result)){
                $content = $row['content'];   
            }
            echo $content;
            $query = "UPDATE turn
                    SET game_status='GUEST_REC'
                    WHERE host='{$host}' AND guest='{$guest}' AND game_status='HOST_SENT';";
            mysqli_query($dbc, $query);
            echo "<script>
                $('.grid_list .grid_row .grid_row_list .grid_item').each(function(){
                        $(this).click(guestSelectsItem);
                    });
            </script>";
        }
        mysqli_close($dbc);
    }

    function guestSelectsItem($host, $guest, $selection){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "UPDATE turn
                    SET guest_select_item={$selection}
                    WHERE host='{$host}' AND guest='{$guest}' AND game_status='GUEST_REC';";
        mysqli_query($dbc, $query);
        $query = "SELECT * FROM turn
                    WHERE host='{$host}' AND guest='{$guest}' AND game_status='GUEST_REC';";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $host_select = $row['host_select_item'];
            $guest_select = $row['guest_select_item'];
        }
        if ($host_select == $guest_select){
            $query = "UPDATE turn
                    SET game_result='matched'
                    WHERE host='{$host}' AND guest='{$guest}' AND game_status='GUEST_REC';";
            mysqli_query($dbc, $query);
            echo 'matched ' . $host_select . ' ' . $guest_select;   
        }else{
            $query = "UPDATE turn
                    SET game_result='unmatched'
                    WHERE host='{$host}' AND guest='{$guest}' AND game_status='GUEST_REC';";
            mysqli_query($dbc, $query);
            echo 'unmatched ' . $host_select . ' ' . $guest_select;
        }
        mysqli_close($dbc);
    }

    function hostChecksResult($host, $guest){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM turn WHERE host='{$host}' AND guest='{$guest}' AND game_status='GUEST_REC';";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $game_result = $row['game_result'];
            $host_select = $row['host_select_item'];
            $guest_select = $row['guest_select_item'];
        }
        if ($game_result == 'PLAYING'){
            echo 'not_finished';   
        }else{
            if ($game_result == 'matched'){
                echo 'matched ' . $host_select . ' ' . $guest_select;    
            }else if ($game_result == 'unmatched'){
                echo 'unmatched ' . $host_select . ' ' . $guest_select;  
            }
            $query = "UPDATE turn
                    SET game_status='FINISHED'
                    WHERE host='{$host}' AND guest='{$guest}' AND game_status='GUEST_REC';";
            mysqli_query($dbc, $query);
        }
        mysqli_close($dbc);
    }

    function guestStartsTurn($host, $guest){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM turn WHERE host='{$host}' AND guest='{$guest}' AND game_status='PLAYING';";
        $result = mysqli_query($dbc, $query);
        mysqli_close($dbc);
        if (mysqli_num_rows($result) == 0){
            echo '0';   
        }else{
            echo '1';
        }
    }

    function finishGame($host, $guest){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        // since it is 20 turns, host and guest should be swapped
        $query = "UPDATE game SET game_status='FINISHED' WHERE host='{$guest}' AND guest='{$host}' AND game_status='PLAYING';";
        echo $query;
        $result = mysqli_query($dbc, $query);
        mysqli_close($dbc);
    }
?>
