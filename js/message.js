var turn = 0;
var siteurl_dash = "http://sapir.psych.wisc.edu/~yan/Psycho-Project/";

var msg_time_out = 5000;
var mouse_x = 0;
var mouse_y = 0;
var send_msg = '';
var toggle_state = 0;


/*
 * This function process host's selcetion
 */
function hostSelectsItem(){
	console.log('host selecting');
	$('#myCanvas').hide();
	var canvas = document.getElementById('myCanvas');
        var context = canvas.getContext('2d');	
	context.clearRect ( 0 , 0 , canvas.width, canvas.height );
    // var siteurl_dash = 'http://sapir.psych.wisc.edu/Psycho-Project/';
    var url = siteurl_dash + 'api/game_api.php';
    var host = $('#message-play-wrapper .message-play-guide').attr('data-hostname');
    var guest = $('#message-play-wrapper .message-play-guide').attr('data-guestname');
    var selection = $(this).attr('data-itemid');
     $('.grid_list .grid_row .grid_row_list .grid_item').off('click');
    // console.log(host + ' ' + guest + ' ' + selection);
    $.ajax({
        type: 'POST',
        url: url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            selection: selection,
            action: 'host_select'
        }
    }).done(function(data){
         // console.log(data);
         // once success, update the game instructions
         $('#message-play-wrapper .message-play-guide h4').text('Please start sending message by clicking the start button, and then move your mouse inside the input panel.');
         // append start button in panel-body
         var body = $('.input-panel .panel-body');
         var strt_btn = $('<div class=\"btn btn-info btn-strt-msg\" style="z-index: 10;" data-container=\"body\" data-toggle=\"popover\" data-placement=\"bottom\" data-content=\"Once you clicked the button, message will be sent immediately according to the position of your mouse, you will have 5 seconds to send the message.\">Start</div>');
         // append the "start" button and register event for it
         body.append(strt_btn);
         $(strt_btn).hover(startBtnHover, startBtnLeave);
         $(strt_btn).click(startBtnClick);

    }).fail(function( jqXHR, textStatus ){
        alert( 'Request failed: ' + textStatus );
    });
}

function guestSelectsItem(){
	console.log('selecting');
    var siteurl_dash = 'http://sapir.psych.wisc.edu/~yan/Psycho-Project/';
    var url = siteurl_dash + 'api/game_api.php';
    var host = $('#message-play-wrapper .message-play-guide').attr('data-hostname');
    var guest = $('#message-play-wrapper .message-play-guide').attr('data-guestname');
    var selection = $(this).attr('data-itemid');
    $('.grid_list .grid_row .grid_row_list .grid_item').off('click');
    $.ajax({
            type: 'POST',
            url: url,
            cache: false,
            data: {
                host: host,
                guest: guest,
                selection: selection,
                action: 'guest_select'
            }
        }).done(function(data){
             // console.log(data);
             var data_arr = data.split(' ');
             // console.log(data_arr);
             if (turn != 20){
                $('.btn-next-turn').css({'opacity':'1', 'z-index':'1'});
                $('.btn-next-turn').click(buttonNextTurnClick);
             }else{
                finishGamePOST(url, host, guest);
             }
             if (data_arr[0] == 'matched'){
                $('[data-itemid=\"' + data_arr[1] + '\"]').css('background-color', '#86C166');
                $('[data-itemid=\"' + data_arr[2] + '\"]').css('background-color', '#86C166');
                if (turn != 20){
                    $('#message-play-wrapper .message-play-guide h4').text('Your guess is correct. Please click start to start next turn.');
                }else{
                    $('#message-play-wrapper .message-play-guide h4').text('Your guess is correct. Game over, please quit. Thank you.');
                }
             }else{
                $('[data-itemid=\"' + data_arr[1] + '\"]').css('background-color', '#86C166');
                $('[data-itemid=\"' + data_arr[2] + '\"]').css('background-color', '#E87A90');
                if (turn != 20){
                    $('#message-play-wrapper .message-play-guide h4').text('Your guess is incorrect. Please click start to start next turn.');
                }else{
                    $('#message-play-wrapper .message-play-guide h4').text('Your guess is incorrect. Game over, please quit. Thank you.');
                }
             }

        }).fail(function( jqXHR, textStatus ){
            alert( 'Request failed: ' + textStatus );
        });
}

/*
 * Hover event for "start messaging" button
 */
function startBtnHover(){
    $(this).popover('show');
    $('#myCanvas').hide();
}

/*
 * Leave event for "start messaging" button
 */
function startBtnLeave(){
    $(this).popover('hide');
    $('#myCanvas').hide();
}

/*
 * Click event for "start messaging" button
 */
function startBtnClick(){
    $(this).popover('hide');
    $('#myCanvas').hide();
    $(this).removeAttr('data-container');
    $(this).removeAttr('data-placement');
    $(this).removeAttr('data-toggle');
    $(this).removeAttr('data-content');
    $(this).remove();
    var siteurl_dash = 'http://sapir.psych.wisc.edu/~yan/Psycho-Project/';
    var x_pos_zero = $('.input-panel .panel-body').offset().left;
    var y_pos_zero = $('.input-panel .panel-body').offset().top + 408;
    var host = $('#message-play-wrapper .message-play-guide').attr('data-hostname');
    var guest = $('#message-play-wrapper .message-play-guide').attr('data-guestname');
    var game_url = siteurl_dash + 'api/game_api.php';
    $('.input-panel.panel.panel-info').css('background-color', 'transparent')
    $('#myCanvas').css("display","block");
    	clickX = new Array();
	clickY = new Array();
	clickDrag = new Array();
	document.getElementById("iframeId").contentWindow.gainNode.connect(document.getElementById("iframeId").contentWindow.audioCtx.destination);
	hostRecordMsg(x_pos_zero, y_pos_zero, game_url, host, guest);
	send_msg = toggle_state + '';
}

document.onmousedown = mouseDown;
document.onmouseup = mouseUp;

var mouseState = 0;
var clickX = new Array();
var clickY = new Array();
var clickDrag = new Array();

function mouseDown(ev) {
    mouseState = 1;
}

function mouseUp(ev) {
    mouseState = 0;
}

/*
 * This function records message host sends out
 *
 * @param x_pos_zero: value corresponds to zero position in x direction
 * @param y_pos_zero: value corresponds to zero position in y direction
 * @param gam_url: url to post this request
 * @param host: host
 * @param guest: guest
 */
function hostRecordMsg(x_pos_zero, y_pos_zero, game_url, host, guest){
    if (msg_time_out > 0){
	if(toggle_state == 1){
		$('#myCanvas').css('cursor','crosshair');
        	var msg_X = mouse_x - x_pos_zero;
        	var msg_Y = (408 - (y_pos_zero - mouse_y));
        	var msg = mouseState == 1 ? '(' + msg_X + ',' + msg_Y + ')' : '(-1,-1)';

		var canvas = document.getElementById('myCanvas');
                var context = canvas.getContext('2d');
                context.clearRect ( 0 , 0 , canvas.width, canvas.height );

		clickX.push(msg_X);
		clickY.push(msg_Y);
		clickDrag.push(mouseState == 1);
		redraw(context, canvas);

        //$('.view-panel .panel-body').text(msg);
        	$('#time-left').text('Time Left: ' + msg_time_out/1000 + ' sec');
        	msg_time_out -= 50;
        	send_msg += msg;
	}else{
		//if(mouseState == 0)
		//	document.getElementById("iframeId").contentWindow.gainNode.disconnect(document.getElementById("iframeId").contentWindow.audioCtx.destination);
		//else
		//	document.getElementById("iframeId").contentWindow.gainNode.connect(document.getElementById("iframeId").contentWindow.audioCtx.destination);

		var freq = document.getElementById("iframeId").contentWindow.oscillator.frequency.value;
		var vol = document.getElementById("iframeId").contentWindow.gainNode.gain.value;	
		console.log(freq,vol);

		$('#time-left').text('Time Left: ' + msg_time_out/1000 + ' sec');
		msg_time_out -= 50;
		send_msg += '(' + freq + ','+vol+')';
	}
        setTimeout(hostRecordMsg, 50, x_pos_zero, y_pos_zero, game_url, host, guest);
    }else{
	$('#myCanvas').css('cursor','auto');
        $('#time-left').text('Time Left: ' + msg_time_out/1000 + ' sec');
        document.getElementById("iframeId").contentWindow.gainNode.disconnect(document.getElementById("iframeId").contentWindow.audioCtx.destination);
	msg_time_out = 5000;
        hostSendMsgPOST(game_url, host, guest);
    }
}

/*
 * This function redraws the canvas as its being produced
 */

function redraw(context, canvas){
  context.clearRect(0, 0, context.canvas.width, context.canvas.height); // Clears the canvas
  
  context.strokeStyle = "#333";
  context.lineJoin = "round";
  context.lineWidth = 7;
	
  for(var i=0; i < clickX.length; i++) {		
    context.beginPath();
    if(clickDrag[i] && i){
      context.moveTo(clickX[i-1], clickY[i-1]);
     }
     context.lineTo(clickX[i], clickY[i]);
     context.closePath();
     context.stroke();
  }
}

/*
 * Toggles theremin and drawing
 */
//var toggle_state = 0;
 function toggle(){
	if(toggle_state == 0){
		toggle_state = 1;
		$('#toggle').text('Switch to Theremin');
		$('iframe').hide();
		$('#myCanvas').show();
	}else{
                toggle_state = 0;
                $('#toggle').text('Switch to Drawing');
                $('iframe').show();
                $('#myCanvas').hide();
        }

 }

/*
 * This function lets host sends out a message
 *
 * @param x_pos_zero: value corresponds to zero position in x direction
 * @param y_pos_zero: value corresponds to zero position in y direction
 * @param gam_url: url to post this request
 * @param host: host
 * @param guest: guest
 */
function hostSendMsgPOST(game_url, host, guest){
    $.ajax({
        type: 'POST',
        url: game_url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            msg: send_msg,
            action: 'host_send_msg'
        }
    }).done(function(data){
        var siteurl_dash = 'http://sapir.psych.wisc.edu/~yan/Psycho-Project/';
        var host = $('#message-play-wrapper .message-play-guide').attr('data-hostname');
        var guest = $('#message-play-wrapper .message-play-guide').attr('data-guestname');
        var game_url = siteurl_dash + 'api/game_api.php';

        $('#message-play-wrapper .message-play-guide h4').text('Please wait for ' + guest + '\'s guess.');
        send_msg = '';
        hostCheckResultPOST(host, guest, game_url);
    }).fail(function( jqXHR, textStatus ){
        alert( 'Request failed: ' + textStatus );
    });
}
/*
 * function for host to check guest's guest
 *
 * @param host: host
 * @param guest: guest
 * @param url: the url to post this request
 */
function hostCheckResultPOST(host, guest, url){
    $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data: {
                host: host,
                guest: guest,
                action: "host_check_result"
            }
        }).done(function(data){
             //console.log(data);
             var data_arr = data.split(' ');
             //console.log(data_arr);
             if (data_arr[0] == 'matched' || data_arr[0] == 'unmatched'){
                 if (data_arr[0] == 'matched'){
                    $('[data-itemid=\"' + data_arr[1] + '\"]').css('background-color', '#86C166');
                    $('[data-itemid=\"' + data_arr[2] + '\"]').css('background-color', '#86C166');
                    if (turn != 20){
                        $('#message-play-wrapper .message-play-guide h4').text(guest + "'s guess was correct, please wait for him to start to start the next turn.");
                    }else{
                        $('#message-play-wrapper .message-play-guide h4').text(guest + "'s guess was correct. Game over, please quit. Thank you.");
                    }
                 }else{
                    $('[data-itemid=\"' + data_arr[1] + '\"]').css('background-color', '#86C166');
                    $('[data-itemid=\"' + data_arr[2] + '\"]').css('background-color', '#E87A90');
                    if (turn != 20){
                        $('#message-play-wrapper .message-play-guide h4').text(guest + "'s guess was incorrect, please wait for him to start to start the next turn.");
                    }else{
                        $('#message-play-wrapper .message-play-guide h4').text(guest + "'s guess was incorrect. Game over, please quit. Thank you.");
                    }
                 }
                 if (turn != 20){
                    guestStartsNewTurn(guest, host);
                 }else{
                    finishGamePOST(url, host, guest);   
                 }
             }else{
                 setTimeout(hostCheckResultPOST, 1000, host, guest, url);
             }
        }).fail(function( jqXHR, textStatus ){
            alert( "Request failed: " + textStatus );
        });
}

/*
 * event for button that starts next turn
 */
function buttonNextTurnClick(){
    $(this).css({"opacity" : "0", "z-index" : "-1"});
    $(this).off("click");
    var guest = $("#message-play-wrapper .message-play-guide").attr("data-guestname");
    var host = $("#message-play-wrapper .message-play-guide").attr("data-hostname");
    hostStartNewTurn(guest, host);
}

/*
 * Host starts a new turn by calling this function
 *
 * @param host: host
 * @param guest: guest
 */
function hostStartNewTurn(host, guest){
    var game_api_url = siteurl_dash + "api/game_api.php";
    $("#message-play-wrapper .message-play-guide").attr("data-guestname", guest);
    $("#message-play-wrapper .message-play-guide").attr("data-hostname", host);
    $("#message-play-wrapper .message-play-guide h4").text("Please select an item first.");
    //$('.view-panel .panel-body').text("");
    $('#myCanvas').hide();
    turn = turn + 1;
    $.ajax({
        type: "POST",
        url: game_api_url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            turn : turn,
            action: "start_game"
        }
    }).done(function(data){
        console.log(data);
        hostRetrieveGridPOST(game_api_url, host, guest);
        getCurrentTurnPOST(game_api_url, host, guest);
    }).fail(function( jqXHR, textStatus ){
        alert( "Request failed: " + textStatus );
    });
}

/*
 * Host and guest could call this function to get current turn number
 * 
 * @param host: host
 * @param guest: guest
 * @param url: url to post ths request
 */
function getCurrentTurnPOST(url, host, guest){
    var game_api_url = siteurl_dash + "api/game_api.php";
    $.ajax({
        type: "POST",
        url: game_api_url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            action: "get_turn_num"
        }
    }).done(function(data){
         // console.log(data);
         $(".turn-num-wrapper").text("Turn " + data);
         $("#message-play-wrapper .message-play-guide").attr("data-turn", data);
         turn = parseInt(data);
         
    }).fail(function( jqXHR, textStatus ){
        alert( "Request failed: " + textStatus );
    });
}

/*
 * Guest starts a new turn by calling this function
 *
 * @param host: host
 * @param guest: guest
 */
function guestStartsNewTurn(host, guest){
    var game_api_url = siteurl_dash + "api/game_api.php";
    //$('.view-panel .panel-body').text("");
    $('#myCanvas').hide();
    $.ajax({
        type: "POST",
        url: game_api_url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            action: "guest_start_turn"
        }
    }).done(function(data){
        //console.log('guestStartsNewTurn' + data);
        if (data == '1'){
            guestRetrieveGridPOST(game_api_url, host, guest);
            getCurrentTurnPOST(game_api_url, host, guest);
            $("#message-play-wrapper .message-play-guide").attr("data-guestname", guest);
            $("#message-play-wrapper .message-play-guide").attr("data-hostname", host);
            $('#message-play-wrapper .message-play-guide h4').text("Please wait for " + host + " to select an item and send you a message.");
            $(".view-panel .panel-heading").text("Msg received");
            guestRecMessagePOST(game_api_url, host, guest);
        }else{
            setTimeout(guestStartsNewTurn, 1000, host, guest);   
        }
    }).fail(function( jqXHR, textStatus ){
        alert( "Request failed: " + textStatus );
    });
}


/*
 * Host receive a grid by calling this function
 *
 * @param url: url to post this request
 * @param host: host
 * @param guest: guest
 */
function hostRetrieveGridPOST(url, host, guest){
    var game_api_url = siteurl_dash + "api/game_api.php";
    $.ajax({
        type: "POST",
        url: game_api_url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            action: "host_retrieve_grid"
        }
    }).done(function(data){
         console.log(data);
         if (data == "no grid found"){
            setTimeout(hostRetrieveGridPOST, 1000, url, host, guest);    
         }else{
            $('.grid-panel .panel-body').html(data);
            $(".view-panel .panel-heading").text("Msg Sent");
         }

    }).fail(function( jqXHR, textStatus ){
        alert( "Request failed: " + textStatus );
    });
}

/*
 * ajax call to create a new game
 *
 * @param url: url to post this request
 * @param host: host
 * @param guest: guest
 */
function startGamePOST(url, host, guest){
	var canvas = document.getElementById('myCanvas');
        var context = canvas.getContext('2d');
        context.clearRect ( 0 , 0 , canvas.width, canvas.height );

    var game_api_url = siteurl_dash + "api/game_api.php";
    turn = turn + 1;
    $.ajax({
        type: "POST",
        url: game_api_url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            turn : turn,
            action: "start_game"
        }
    }).done(function(data){
        console.log(data);
        guestRetrieveGridPOST(url, host, guest);
        getCurrentTurnPOST(url, host, guest);
        $("#message-play-wrapper .message-play-guide").attr("data-guestname", guest);
        $("#message-play-wrapper .message-play-guide").attr("data-hostname", host);
        $(".view-panel .panel-heading").text("Msg received");
        guestRecMessagePOST(url, host, guest);
    }).fail(function( jqXHR, textStatus ){
        alert( "Request failed: " + textStatus );
    });
}

/*
 * Guest receive a grid by calling this function
 *
 * @param url: url to post this request
 * @param host: host
 * @param guest: guest
 */
function guestRetrieveGridPOST(url, host, guest){
    var game_api_url = siteurl_dash + "api/game_api.php";
    $.ajax({
        type: "POST",
        url: game_api_url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            action: "guest_retrieve_grid"
        }
    }).done(function(data){
         console.log(data);
         if (data == "no grid found"){
            setTimeout(guestRetrieveGridPOST, 1000, url, host, guest);    
         }else{
            $('.grid-panel .panel-body').html(data); 
		//put canvas here
            //$('.input-panel .panel-body').html(data);
         }
    }).fail(function( jqXHR, textStatus ){
        alert( "Request failed: " + textStatus );
    });
}

/*
 * Host receive message by keep calling this function
 *
 * @param url: url to post this request
 * @param host: host
 * @param guest: guest
 */
function guestRecMessagePOST(url, host, guest){
    var game_api_url = siteurl_dash + "api/game_api.php";
    $.ajax({
        type: "POST",
        url: game_api_url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            action: "guest_rec_msg"
        }
    }).done(function(data){
        if (data == "not received"){
            setTimeout(guestRecMessagePOST, 1000, url, host, guest);
        }else{
		var state = parseInt(data.substring(0,1));
		//console.log(data.substring(0,1),state,data);
		data = data.substring(1);
		if(state == 1){
                	toggle_state = 1;
                	$('#toggle').text('Switch to Theremin');
                	$('iframe').hide();
                	$('#myCanvas').show();
        	}else{
                	toggle_state = 0;
                	$('#toggle').text('Switch to Drawing');
                	$('iframe').show();
                	$('#myCanvas').hide();
        	}
		console.log(state, toggle_state);
	
		data = data.split(')');
            	data = data.join();
            	data = data.split(',(');
            	data[0] = data[0].replace('(','');
            	data[data.length - 1] = data[data.length - 1].substring(0, data[data.length - 1].length - 1);;
           	console.log(data);

		if(toggle_state == 1){
           		var canvas = document.getElementById('myCanvas');
	      		var context = canvas.getContext('2d');
			context.clearRect ( 0 , 0 , canvas.width, canvas.height );
	      		var point;
			var i;
	
			context.lineWidth = 7;
			context.strokeStyle = '#333';		

	
	      		clickX = new Array();
			clickY = new Array();
			clickDrag = new Array();
			for(i = 0; i < data.length; i++){
	      			point = data[i].split(',');
				clickX.push(point[0]);
                		clickY.push(point[1]);
                		clickDrag.push(point[0] != -1);
			}
	      
			for(i = 0; i < data.length; i++)
				setTimeout(guestReDraw, 50 * i, context, canvas, i);
		}else{
			document.getElementById("iframeId").contentWindow.gainNode.connect(document.getElementById("iframeId").contentWindow.audioCtx.destination);
			console.log('theremin');
			data = smooth(data);	
			play(data);
		}
		console.log('returned');
		$('.grid_list .grid_row .grid_row_list .grid_item').each(function(){
                        $(this).click(guestSelectsItem);
                });

            $("#message-play-wrapper .message-play-guide h4").text("Please make your guess now");
        }
    }).fail(function( jqXHR, textStatus ){
        alert( "Request failed: " + textStatus );
    });
}

/*
 *
 */
function guestReDraw(context, canvas, end){
  context.clearRect(0, 0, context.canvas.width, context.canvas.height); // Clears the canvas

  context.strokeStyle = "#333";
  context.lineJoin = "round";
  context.lineWidth = 7;
  for(var i=0; i < clickX.length && i < end; i++) {

	if(i > 0 && clickX[i - 1] != -1){
   		context.beginPath();
    		if(clickDrag[i] && i){
      			context.moveTo(clickX[i-1], clickY[i-1]);
     		}
     		context.lineTo(clickX[i], clickY[i]);
     		context.closePath();
     		context.stroke();
	}
  }
}



/*
 * Makes data smoother for better play back
 * from 100 to 300 pts
 */
function smooth(data){
	var newData = new Array();
	var i = 1;	

	for(i; i < data.length; i++){
		prev = data[i - 1].split(',');
		curr = data[i].split(',');
		newData.push([prev[0] * 1.0, prev[1] * 1.0]);
		newData.push([prev[0] * .67 + curr[0] * .33, prev[1] * .67 + curr[1] * .33]);
		newData.push([prev[0] * .33 + curr[0] * .67, prev[1] * .33 + curr[1] * .67]);
	}
	return newData;
}

/*
 * Plays back the theremin sound
 */
function play(data){
	var j, point;
	for(j = 0; j < data.length; j ++){
		point = data[j];
		setTimeout(playBack, 15 * j, point[0], point[1], j);
	}	
}

function playBack(freq, vol, j){
	document.getElementById("iframeId").contentWindow.oscillator.frequency.value = freq;
        document.getElementById("iframeId").contentWindow.gainNode.gain.value = vol;
	if(j == 290)
		document.getElementById("iframeId").contentWindow.gainNode.disconnect(document.getElementById("iframeId").contentWindow.audioCtx.destination);

}


function finishGamePOST(url, host, guest){
    $.ajax({
        type: "POST",
        url: url,
        cache: false,
        data: {
            host: host,
            guest: guest,
            action: "finish_game"
        }
    }).done(function(data){
        console.log(data);

    }).fail(function( jqXHR, textStatus ){
        // alert( "Request failed: " + textStatus );
    });
}

$(document).ready(function ($) {
   
    /*var no_touch_flag = 0;
    var defaultEvent = "";
    
    if (Modernizr.touch){
        defaultEvent = "tap";
	} else {
        no_touch_flag = 1; 
        defaultEvent = "click";
    }
    
    $(document).on('touchmove', function(e) {
        e.preventDefault();
    });*/
        
    /**
    *
    * FSS - Full Screen Sliding Website Plugin
    * URL: http://www.codecanyon.net/user/skyplugins
    * Version: 1.1
    * Author: Sky Plugins
    * Author URL: http://www.codecanyon.net/user/skyplugins
    *
    * Modified by Art Chen (http://rakugaki.me)
    *
    */
    
    var sliderSettings = {
        homeButton: '.home-button',
        prevButton: '.prev-button',
        nextButton: '.next-button',
        transition: 'horizontal',
        transitionSpeed: 500,
        easing: 'easeInOutQuint'
    };

    
    var slider = $(".slider");
    var slideContainer = slider.children('div.slides');
    var slides = slideContainer.children('div.slide');
    var numSlides = slides.length;
    var introAnimationPlayed = false;
    var gameStarted = false;
    
    function initSlider() {

        initFullScreen();

        height = $(window).height() - 60;
        width = $(window).width();

        slider.css({
            height: height,
            width: width,
            position: 'relative',
            overflow: 'hidden'
        });

        slideContainer.css('position', 'absolute');

        slides.css({
            position: 'relative',
            width: width,
            height: height,
            overflow: 'auto'
        });
        
        if (sliderSettings.transition == 'horizontal') {
            slideContainer.css('width', numSlides * width);
            slides.css('float', 'left');
        }

        initKeyboardNavigation();

        initAddressPlugin();

        initSpecialAnchors(slider);

        initSpecialButtons(slider);
	$('#myCanvas').hide();
    }
    
    function initAddressPlugin() {
        
        $.address.init(function(event) {
            $.address.autoUpdate(false);
        }).change(function(event) {
            var id = event.value;
            id = event.value.substring(1);

            // Go to home if the id is null
            if (id != '' && typeof(id) != 'undefined') {
                if (slides.filter('#' + id).size() > 0) {
                    selectSlideById(id);
                } else {
                    gotoHome();
                }
            } else {
                gotoHome();
            }
        });
    }
    
    function initFullScreen() {

        $('html, body').css({
            margin: 0,
            padding: 0,
            overflow: 'hidden'
        });

        $(window).resize(function() {

            height = $(window).height() - 60;
            width = $(window).width();

            slider.css({
                width: width,
                height: height
            });
            slides.css({
                width: width,
                height: height
            });

            var currentSlideIndex = getCurrentSlide().index();

            if (currentSlideIndex != -1) {
                if (sliderSettings.transition == 'horizontal') {
                    slideContainer.css({width: numSlides * width});
                    slider.stop().scrollLeft(currentSlideIndex * width);
                } else {
                    slider.stop().scrollTop(currentSlideIndex * height);
                }
                
            }
        });

    }
    
    function initSpecialAnchors(context) {

        $('.special-anchor-direct').on("click", function(event) {
            var href = $(this).attr('data-slide');
            $.address.value(href);
            $.address.update();
            return false;
        });
    }

    function initSpecialButtons(context) {

        $(sliderSettings.homeButton + ".special-anchor").on("click", function(event) {
            gotoHome();
        });
        
        $( sliderSettings.prevButton + ".special-anchor").on("click", function(event){
            selectPrevious();
        });
        
        $(sliderSettings.nextButton + ".special-anchor").on("click", function(event){
            selectNext();
        });
    }

    var initKeyboardNavigation = function() {

        $(document).keydown(function(event) {
            /* Check visibility of popup modal */
            var checkModal = function(){
                var isModalVisible = false;
                $('.md-modal').each(function(){
                    /* if modal visible, prevent fss keyboard events */
                    if ($(this).css("visibility") == "visible"){
                        isModalVisible = true;
                    }
                });
                return !isModalVisible;
            } 
            
            if (event.target.type !== 'textarea' &&
                event.target.type !== 'text' &&
                checkModal()
            ) { 
                var keyCode = event.keyCode || event.which;

                switch (keyCode) {
                    case 37: // Up arrow
                        // selectPrevious();
                    break;
                    case 39: // Down arrow
                        // selectNext();
                        break;
                    default:
                        break;
                }
            }
        });
    }

    function selectSlideById(id, callback) {

        var index = slides.filter("#" + id).index();
        selectSlideByIndex(index, callback);

    }

    function gotoHome(callback) {

        $.address.value(slides.eq(0).attr('id'));
        $.address.update();

        if (callback) {
            callback();
        }

    }

    function selectNext(callback) {

        var index = getCurrentSlide().index();

        if (++index == numSlides) {
            index = 0;
        }

        $.address.value(slides.eq(index).attr('id'));
        $.address.update();

        if (callback) {
            callback();
        }

    }

    function selectPrevious(callback) {

        var index = getCurrentSlide().index();

        if (--index < 0) {
            index = numSlides - 1;
        }

        $.address.value(slides.eq(index).attr('id'));
        $.address.update();

        if (callback) {
            callback();
        }

    }

    function selectSlideByIndex(index, callback) {

        var current = getCurrentSlide();

        if (index != current.index() && index != -1) {

            getPrevSlide().removeClass('prev');
            current.removeClass('current').addClass('prev');
            slides.eq(index).addClass('current');
            
            if (!introAnimationPlayed) {
                var speedBuffer = sliderSettings.transitionSpeed;
                sliderSettings.transitionSpeed = 0;
                introAnimationPlayed = true;
            }

            switch (sliderSettings.transition) {
                case 'vertical': 
                    slideVertical(callback);
                    break;
                default:
                case 'none':
                case 'horizontal': 
                    slideHorizontal(callback);
                    break;
            }
            
            if (typeof(speedBuffer) != 'undefined') {
                sliderSettings.transitionSpeed = speedBuffer;
            }

        } else {
            
            if (callback) {
                callback();
            }
        }

    }
    
    function slideHorizontal(callback) {

        slider.stop().animate({
            scrollLeft: getCurrentSlide().index() * width
        }, sliderSettings.transitionSpeed, sliderSettings.easing, function() {
            
            if (callback) {
                callback();
            }
        });

    }

    function slideVertical(callback) {

        slider.stop().animate({
            scrollTop: getCurrentSlide().index() * height
        }, sliderSettings.transitionSpeed, sliderSettings.easing, function() {

            if (callback) {
                callback();
            }
        });

    }

    function getCurrentSlide() {

        return slides.filter('div.current');

    }

    function getPrevSlide() {

        return slides.filter('div.prev');

    }
    
    initSlider();

    /**************************/
    /***** Start customize JS */
    
    var username = "";
    var waitTime = 60;
    var joining = false;
    
    /*
     * create button event
     */
    function createButtonClick(){
        var game_api_url = siteurl_dash + "api/game_api.php";
        username = $(this).attr("data-username");
        waitTime = 60;
        //console.log(username);
        createGamePOST(game_api_url, username);
    }
    
    /*
     * ajax call to create a new game
     */
    function createGamePOST(url, username){
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data: {
                username: username,
                action: "create"
            }
        }).done(function(data){
            // success
            hostPollGame();
        }).fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
        });
    }
    
    /*
     * poll game status by host during waiting
     */
    function hostPollGame(){
        var game_api_url = siteurl_dash + "api/game_api.php";
        $.ajax({
            type: "POST",
            url: game_api_url,
            cache: false,
            data: {
                username: username,
                action: "host_poll"
            }
        }).done(function(data){
            // success
            // console.log(data);
            if (waitTime > 0 && data == "noUser"){
                console.log(data);
                $("#message-wait-wrapper .message-guide h3").text("Step 2: wait for another user to join, " + waitTime + " secs left");
                setTimeout(hostPollGame, 1000);
                // update wait time
                waitTime = waitTime - 1;
            }else if (waitTime == 0 && data == "noUser") {
                var href="message-start-wrapper";
                $.address.value(href);
                $.address.update();
                deleteGamePOST(game_api_url, username);
            }else{
                var href="message-play-wrapper";
                var host=$('.message-start .start-btn-group .btn-start').attr('data-username');
                // console.log(host + " " + data);
                $("#message-play-wrapper .message-play-guide").attr("data-guestname", data);
                $("#message-play-wrapper .message-play-guide").attr("data-hostname", host);
                $("#message-play-wrapper .message-play-guide h3").text(data + " joined your game.");
                $("#message-play-wrapper .message-play-guide h4").text("Please select an item first.");
                $.address.value(href);
                $.address.update();
                hostRetrieveGridPOST(game_api_url, host, data);
                getCurrentTurnPOST(game_api_url, host, data);
            }
        }).fail(function( jqXHR, textStatus ) {
                // alert( "Request failed: " + textStatus );
        });
    }
    
    /*
     * back button event
     */
    function backFromWaitButtonClick(){
        var game_api_url = siteurl_dash + "api/game_api.php";
        username = $(this).attr("data-username");
        // console.log(username);
        waitTime = 0;
        deleteGamePOST(game_api_url, username);
    }
    
    /*
     * ajax call to delete a game
     */
    function deleteGamePOST(url, username){
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data: {
                username: username,
                action: "delete"
            }
        }).done(function(data){
            // success
		$('#myCanvas').hide();
        }).fail(function( jqXHR, textStatus ) {
                alert( "Request failed: " + textStatus );
        });
    }
    
    /*
     * click event for join button in start slide
     */
    function joinButtonClick(){
        var game_api_url = siteurl_dash + "api/game_api.php";
        var username = $(this).attr("data-username");
        var community_name = $(this).attr("data-community");
        joining = true;
        guestPollGame(game_api_url, username, community_name);
    }
    
    /*
     * poll game status by guest during joining
     */
    function guestPollGame(url, username, community_name){
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data: {
                username: username,
                community: community_name,
                action: "guest_poll"
            }
        }).done(function(data){
            // console.log(data);
            $(".available-user-list").html(data);
            $(".user-option").click(userOptionClick);
            initSpecialAnchors(slider);
            if (joining){
                setTimeout(guestPollGame, 1000, url, username, community_name);    
            }
        }).fail(function( jqXHR, textStatus ){
            // alert( "Request failed: " + textStatus );
        });
    }
    
    /*
     * click event for back button in join slide
     */
    function backFromJoinButtonClick(){
        joining = false;
    }
    
    /*
     * click event for options in available game list
     */
    function userOptionClick(){
        var game_api_url = siteurl_dash + "api/game_api.php";
        var guest = $(this).attr("data-guestuser");
        var host = $(this).attr("data-hostuser");
        guestJoinGamePOST(game_api_url, host, guest);
        $("#message-play-wrapper .message-play-guide h3").text("You joined " + host + "'s game.");
        $("#message-play-wrapper .message-play-guide h4").text("Please make your guess after " + host + " selects an item and sends you message. ");
        $(".view-panel .panel-heading").text("View received mesages");
    }
    
    /*
     * ajax call to join a guest to a game
     */
    function guestJoinGamePOST(url, host, guest){
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data: {
                host: host,
                guest: guest,
                action: "guest_join"
            }
        }).done(function(data){
            // console.log(data);
            startGamePOST(url, host, guest);
            
        }).fail(function( jqXHR, textStatus ){
            // alert( "Request failed: " + textStatus );
        });
    }
    
    function quitGamePOST(url, host, guest){
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data: {
                host: host,
                guest: guest,
                action: "quit_game"
            }
        }).done(function(data){
            // console.log(data);
            
        }).fail(function( jqXHR, textStatus ){
            // alert( "Request failed: " + textStatus );
        });
    }
    
    function backFromPlayButtonClick(){
        var game_api_url = siteurl_dash + "api/game_api.php";
        var guest = $("#message-play-wrapper .message-play-guide").attr("data-guestname");
        var host = $("#message-play-wrapper .message-play-guide").attr("data-hostname");
        if (turn != 20){
            quitGamePOST(game_api_url, host, guest);   
        }else{
            
        }
    }
    
    /* bind click event to create button in start slide */
    $(".btn-start").click(createButtonClick);
    /* bind click event to join button in start slide */
    $(".btn-join").click(joinButtonClick);
    /* bind click event to back button in wait slide */
    $(".btn-back-from-wait").click(backFromWaitButtonClick);
    /* bind click event to back button in join slide */
    $(".btn-back-from-join").click(backFromJoinButtonClick);
    /* bind click event to back button in play slide */
    $(".btn-back-from-play").click(backFromPlayButtonClick);
});


