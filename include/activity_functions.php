<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/constant.php');

    function displayAllActivities($username){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT game.*, COUNT(turn.game_result) AS cnt FROM game 
                    INNER JOIN game_has_turn 
                        ON game_has_turn.game_id=game.game_id 
                    INNER JOIN turn 
                        ON game_has_turn.turn_id=turn.turn_id 
                    WHERE game.game_status='FINISHED' AND (game.host='{$username}' OR game.guest='{$username}') AND turn.game_result='matched'
                    GROUP BY (game.game_id);";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) == 0){
            echo "<li>
                    <div class='activity-content'>
                        <h6>No activietes</h6>
                    </div>
                  </li>";
        }else{
            while ($row = mysqli_fetch_array($result)){
                $game_id = $row['game_id'];
                $host = $row['host'];
                $guest = $row['guest'];
                $creat_at = $row['create_at'];
                $cnt = $row['cnt'];
                if ($host == $username){
                    $activity = "<strong>{$guest}</strong> joined your game, and you two matched " . $cnt . " out of 20 turns";
                }else{
                    $activity = "you joined <strong>{$host}'s</strong> game, and you two matched " . $cnt . " out of 20 turns";
                }
                echo "<li data-gameid='{$game_id}'>
                        <div class='activity-tag'></div>
                        <div class='tag-line'></div>
                        <div class='activity-content'>
                            <h5>{$creat_at}</h5>
                            <h5>{$activity}</h5>
                        </div>
                    </li>";
                echo "<script>$('.activity-list li').each(function(){
                            $(this).click(activityClick);
                        });</script>";
            }
        }
        mysqli_close($dbc);
    }

    function getTurnGrid($game_id, $turn_num, $username){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT turn.* FROM game
                    INNER JOIN game_has_turn
                        ON game.game_id=game_has_turn.game_id
                    INNER JOIN turn
                        ON turn.turn_id=game_has_turn.turn_id
                    WHERE game.game_id={$game_id} AND turn.turn_number={$turn_num};";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $host_grid_order = $row['host_grid_order'];
            $guest_grid_order = $row['guest_grid_order'];
            $host = $row['host'];
            $guest = $row['guest'];
            $host_select_item = $row['host_select_item'];
            $guest_select_item = $row['guest_select_item'];
            $game_result = $row['game_result'];
        }
        // set up grid order
        if ($username == $host){
            $grid_order = $host_grid_order;   
        }else{
            $grid_order = $guest_grid_order;   
        }
        $grid_order_arr = explode('|', $grid_order);
        $grid_item_arr = array();
        for ($i = 0; $i < count($grid_order_arr); $i ++){
            $grid_item_id = $grid_order_arr[$i];
            $query = "SELECT * FROM grid_item WHERE grid_item_id={$grid_item_id};";
            $result = mysqli_query($dbc, $query);
            while ($row = mysqli_fetch_array($result)){
                $grid_item = $row['grid_item'];
                array_push($grid_item_arr, $grid_item);
            }
        }
        // generate grid in html
        echo '<ul class="turn-grid">';
        for ($i = 0; $i < count($grid_item_arr); $i ++){
            $grid_item = $grid_item_arr[$i];
            if ($i % 3 == 0){
                echo "<li class='turn-grid-row'>
                        <ul class='turn-grid-row-list'>";
            }
            if ($grid_order_arr[$i] == $host_select_item){
               echo "<li class='correct'><h1>{$grid_item}</h1></li>";
            }else if ($grid_order_arr[$i] == $guest_select_item){
                if ($game_result == 'matched'){
                    echo "<li class='correct'><h1>{$grid_item}</h1></li>";
                }else{
                    echo "<li class='incorrect'><h1>{$grid_item}</h1></li>";   
                }
            }else{
                echo "<li><h1>{$grid_item}</h1></li>";
            }
            if ($i % 3 == 2){
                echo "</ul></li>";
            }
        }
        echo '</ul>';
        mysqli_close($dbc);
    }

    /*
     * This function gets summary of a turn from DB, and displays it out via HTML
     * it also registered the next and prev bttn
     *
     * @param $game_id: game's id
     * @param $turn_num: turn number
     */
    function getTurnSummary($game_id, $turn_num){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT turn.* FROM game
                    INNER JOIN game_has_turn
                        ON game.game_id=game_has_turn.game_id
                    INNER JOIN turn
                        ON turn.turn_id=game_has_turn.turn_id
                    WHERE game.game_id={$game_id} AND turn.turn_number={$turn_num};";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $turn_num = $row['turn_number'];
            $host = $row['host'];
            $guest = $row['guest'];
            $game_result = $row['game_result'];
            echo "<h4>Turn</h4>
                  <h5><strong>Turn#: </strong>{$turn_num}</h5>
                  <h5><strong>Result: </strong>{$game_result}</h5>
                  <h5><strong>Sender: </strong>{$host}</h5>
                  <h5><strong>Recipient: </strong>{$guest}</h5>
                  <h5><strong>Message: </strong></h5>";
        }
        echo "<script>
                  $('.prev-turn .icon-arrow-left').off('click');
                  $('.prev-turn .icon-arrow-left').click(changeTurnClick);
                  $('.next-turn .icon-arrow-right').off('click');
                  $('.next-turn .icon-arrow-right').click(changeTurnClick);
              </script>";
        mysqli_close($dbc);
    }

    function getPagination($game_id, $username){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT COUNT(*) AS cnt FROM game_has_turn WHERE game_id={$game_id};";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $cnt = $row['cnt'];
        }
        echo "<ul>";
        for ($i = 0; $i < $cnt; $i ++){
            $content = $i + 1;
            echo "<li data-gameid={$game_id} data-turnnum={$content} data-username={$username}>{$content}</li>";
        }
        echo "</ul>";
        echo "<script>
                $('.turn-pagination ul li').each(function(){
                    $(this).off('click');
                    $(this).click(changeTurnClick);
                });
              </script>";
        mysqli_close($dbc);
    }
?>