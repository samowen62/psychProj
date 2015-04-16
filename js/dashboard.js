function getAllActivitiesPOST(url, username){
    $.ajax({
        type: "POST",
        url: url,
        cache: false,
        data: {
            username: username,
            action: "get_all_activities"
        }
    }).done(function(data){
        // console.log(data);
        $('.dashboard-activity .activity-wrapper .activity-list').html(data);
    }).fail(function(){

    });
}

function getPaginationPOST(url, game_id, username){
    $.ajax({
        type: "POST",
        url: url,
        cache: false,
        data: {
            game_id: game_id,
            username: username,
            action: "get_pagination"
        }
    }).done(function(data){
        console.log(data);
        $('.turn-pagination').css({"opacity":"1", "z-index":"1"});
        $('.turn-pagination').html(data);
    }).fail(function(){

    });
}

function getTurnSummaryPOST(url, game_id, turn_num){
    $.ajax({
        type: "POST",
        url: url,
        cache: false,
        data: {
            game_id: game_id,
            turn_num: turn_num,
            action: "get_turn_summary"
        }
    }).done(function(data){
        // console.log(data);
        $('.turn-summary-wrapper').html(data);
        $('.current-turn .turn-summary-wrapper').css({"opacity":"1", "z-index":"1"});
    }).fail(function( jqXHR, textStatus ){
        alert( 'Request failed: ' + textStatus );
    });
}

function getTurnGridPOST(url, game_id, turn_num, username){
    $.ajax({
        type: "POST",
        url: url,
        cache: false,
        data: {
            game_id: game_id,
            turn_num: turn_num,
            username:username,
            action: "get_turn_grid"
        }
    }).done(function(data){
        // console.log(data);
        $('.turn-grid-wrapper').html(data);
        $('.current-turn .turn-grid-wrapper').css({"opacity":"1"});
        $('.current-turn .turn-summary-wrapper').css({"opacity":"1"});
        $('.prev-turn .icon-arrow-left').attr("data-username", username);
        $('.prev-turn .icon-arrow-left').attr("data-gameid", game_id);
        $('.prev-turn .icon-arrow-left').attr("data-turnnum", parseInt(turn_num)-1);
        $('.prev-turn').css({"opacity":"1", "z-index":"1"});
        
        $('.next-turn .icon-arrow-right').attr("data-username", username);
        $('.next-turn .icon-arrow-right').attr("data-gameid", game_id);
        $('.next-turn .icon-arrow-right').attr("data-turnnum", parseInt(turn_num)+1);
        $('.next-turn').css({"opacity":"1", "z-index":"1"});
    }).fail(function( jqXHR, textStatus ){
        alert( 'Request failed: ' + textStatus );
    });
}

function changeTurnClick(){
    var username = $(this).attr("data-username");
    var game_id = $(this).attr("data-gameid");
    var turn_num = $(this).attr("data-turnnum");
    var url = 'http://sapir.psych.wisc.edu/~zhexuan/Psycho-Project/api/activity_api.php';
    if (turn_num >= 1 && turn_num <=20){
        $('.current-turn .turn-grid-wrapper').css({"opacity":"0"});
        $('.current-turn .turn-summary-wrapper').css({"opacity":"0"});
        $('.prev-turn').css({"opacity":"0", "z-index":"-1"});
        $('.next-turn').css({"opacity":"0", "z-index":"-1"});
        setTimeout(function(){getTurnSummaryPOST(url, game_id, turn_num)}, 500);
        setTimeout(function(){getTurnGridPOST(url, game_id, turn_num, username)}, 500);
    }
}

function activityClick(){
    var game_id = $(this).attr("data-gameid");
    var url = 'http://sapir.psych.wisc.edu/~zhexuan/Psycho-Project/api/activity_api.php';
    var username = $('#menu .user_name h5').text();
    $('.current-turn .turn-grid-wrapper').css({"opacity":"0"});
    $('.current-turn .turn-summary-wrapper').css({"opacity":"0"});
    $('.turn-pagination').css({"opacity":"0", "z-index":"-1"});
    $('.prev-turn').css({"opacity":"0", "z-index":"-1"});
    $('.next-turn').css({"opacity":"0", "z-index":"-1"});
    setTimeout(function(){getTurnSummaryPOST(url, game_id, 1);}, 500);
    setTimeout(function(){getTurnGridPOST(url, game_id, 1, username);}, 500);
    setTimeout(function(){getPaginationPOST(url, game_id, username);}, 500);
}

$(document).ready(function(){
    var url = "http://sapir.psych.wisc.edu/~zhexuan/Psycho-Project/api/activity_api.php";
    var username = $("#menu .user_name h5").text();
    $('.dashboard-wrapper').height($('.st-content-inner').height() - 60); 
    getAllActivitiesPOST(url, username);
});