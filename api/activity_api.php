<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/activity_functions.php');

    if (isset($_POST['action'])){
        if (isset($_POST['username']) && $_POST['action'] == "get_all_activities"){
            displayAllActivities($_POST['username']);
        }
        if(isset($_POST['game_id']) && isset($_POST['turn_num']) && $_POST['action'] == "get_turn_summary"){
            getTurnSummary($_POST['game_id'], $_POST['turn_num']);
        }
        if(isset($_POST['game_id']) && isset($_POST['turn_num']) && isset($_POST['username']) && $_POST['action'] == "get_turn_grid"){
            getTurnGrid($_POST['game_id'], $_POST['turn_num'], $_POST['username']);
        }
        if(isset($_POST['game_id']) && isset($_POST['username']) && $_POST['action'] == "get_pagination"){
            getPagination($_POST['game_id'], $_POST['username']);
        }
    }
?>