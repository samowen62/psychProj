<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/game_functions.php');

    if (isset($_POST['username']) && isset($_POST['action'])){
        if ($_POST['action'] == "create"){
            createGame($_POST['username']);
        }
        if ($_POST['action'] == "delete"){
            deleteGame($_POST['username']);   
        }
        if ($_POST['action'] == "host_poll"){
            hostPollGame($_POST['username']);
        }
    }

    if (isset($_POST['community']) && isset($_POST['action'])){
        if ($_POST['action'] == "guest_poll"){
            echo guestPollGame($_POST['community']);   
        }
    }

    if (isset($_POST['host']) && isset($_POST['guest']) && isset($_POST['action'])){
        if ($_POST['action'] == "guest_join"){
            guestJoinGame($_POST['host'], $_POST['guest']);   
        }
        if ($_POST['action'] == "start_game"){
            startGame($_POST['host'], $_POST['guest'], $_POST['turn']);   
        }
        if ($_POST['action'] == "host_retrieve_grid" || $_POST['action'] == "guest_retrieve_grid"){
            retrieveGrid($_POST['host'], $_POST['guest'], $_POST['action']);
        }
        if ($_POST['action'] == "get_turn_num"){
            getCurrentTurnNum($_POST['host'], $_POST['guest']);   
        }
        if ($_POST['action'] == 'guest_rec_msg'){
            guestRetrieveMessage($_POST['host'], $_POST['guest']);   
        }
        if ($_POST['action'] == 'host_check_result'){
            hostChecksResult($_POST['host'], $_POST['guest']);   
        }
        if ($_POST['action'] == 'guest_start_turn'){
            guestStartsTurn($_POST['host'], $_POST['guest']);
        }
        if ($_POST['action'] == 'quit_game'){
            quitGame($_POST['host'], $_POST['guest']);
        }
        if ($_POST['action'] == 'finish_game'){
            finishGame($_POST['host'], $_POST['guest']);
        }
    }

    if (isset($_POST['host']) && isset($_POST['selection']) && isset($_POST['guest']) && isset($_POST['action'])){
        
        if ($_POST['action'] == "host_select"){
            hostSelectsItem($_POST['host'], $_POST['guest'], $_POST['selection']);   
        }
        if ($_POST['action'] == "guest_select"){
            guestSelectsItem($_POST['host'], $_POST['guest'], $_POST['selection']);   
        }
    }

    if (isset($_POST['host']) && isset($_POST['guest']) && isset($_POST['msg']) && isset($_POST['action'])){
        if ($_POST['action'] == 'host_send_msg'){
            hostSendMessage($_POST['host'], $_POST['guest'], $_POST['msg']);
        }
    }
?>