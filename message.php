<!doctype html>
<html lang="en">
    <head>
		<link href="css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link href="css/header.css" rel="stylesheet" type="text/css"/>
		<link href="css/message.css" rel="stylesheet" type="text/css"/>

        <title>Psycho Project</title>
		<script type="text/javascript" src="js/jQuery/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="js/jquery.address.js"></script>
		<script type="text/javascript" src="js/message.js"></script>
		<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="js/header.js"></script>
	        <script type="text/javascript" src="js/drawfigure.js"></script>
	</head>
    <?php
        defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');
        require_once(ROOT . '/header.php');
        require_once(ROOT . '/include/user_functions.php');
        require_once(ROOT . '/include/login_functions.php');
        if (user_isLogged()){
            // header("Location: http://sapir.psych.wisc.edu/Psycho-Project/dashboard.php");
        }else{
            header("Location: http://sapir.psych.wisc.edu/Psycho-Project/login.php");
        }
    ?>
    <div class="slider">
        <div class="slides">
            <div id="message-start-wrapper" class="slide">
                <div class="message-guide">
                    <h3>Step1: Choose to create a new game or join others' games</h3>
                </div>
                <div class="message-start">
                    <div class="start-btn-group">
                        <a href="#message-wait-wrapper"  class="special-anchor-direct btn btn-info psy-btn btn-start icon-arrow-right" data-slide="message-wait-wrapper" data-username=<?php echo getCurrentUser(); ?>>
                            Create New Game</a>
                        <a href="#message-join-wrapper" class="special-anchor-direct btn btn-info psy-btn btn-join icon-arrow-right" data-slide="message-join-wrapper" data-username=<?php echo getCurrentUser(); ?> data-community=<?php echo getCurrentUserCommunityName(); ?>>Join Others</a>
                    </div>
                </div> 
            </div>
            <div id="message-wait-wrapper" class="slide">
                <div class="message-guide">
                    <h3>Step2: Choose to create a new game or join others' games</h3>
                </div>
                <div class="message-wait">
                    <div class="message-wait-panel panel panel-info">
                        <div class="panel-heading">Waiting for another player...</div>
                        <div class="panel-body">
                            <h4>You have just created a new puzzle game</h4>
                            <h5>&nbsp;&nbsp;&nbsp;&nbsp;Please wait for another player to join your game. If no one joins in in 60 seconds, we will redirect you back to Step1. Once someone joins your game, you will be directed to the game panel immediately</h5>
                        </div>
                    </div>
                    <a href="#message-start-wrapper" class="special-anchor-direct btn btn-info psy-btn btn-back btn-back-from-wait icon-arrow-left" data-slide="message-start-wrapper" data-username=<?php echo getCurrentUser(); ?>>Back</a>
                </div>
            </div>
            <div id="message-join-wrapper" class="slide">
                <div class="message-guide">
                    <h3>Step2: Pick and join an existing game</h3>
                </div>
                <div class="message-join">
                    <div class="message-join-panel panel panel-info">
                        <div class="panel-heading">Available games in  <?php echo 'in <strong>' . getCurrentUserCommunityName() . '</strong>'; ?></div>
                        <div class="panel-body">
                            <ul class="available-user-list">
                                <?php 
                                    $user_list = getAvailableGameInCommunity(getCurrentUserCommunityName());
                                    if (mysqli_num_rows($user_list) == 0){
                                        echo "<li><a href='#'>No available games now</a></li>";
                                    }else{
                                        while ($row = mysqli_fetch_array($user_list)){
                                            $username = $row['username'];
                                            if ($username != getCurrentUser()){
                                                echo "<li><a href='#'>{$username}</a></li>";
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <a href="#message-start-wrapper" class="special-anchor-direct btn btn-info psy-btn btn-back btn-back-from-join icon-arrow-left" data-slide="message-start-wrapper" data-username=<?php echo getCurrentUser(); ?>>Back</a>
                </div>
            </div>
            <div id="message-play-wrapper" class="slide">
                <div class="message-play-guide" data-hostname="" data-guestname="" data-turn="">
                    <h3></h3>
                    <h4></h4>
                </div>
                <div class="message-play">
                    <div class="grid-panel panel panel-info">
                        <div class="panel-heading">Grid</div>
                        <div class="panel-body">
                        </div>
                    </div>
                    <div class="btn-panel">
                        <div class="turn-num-wrapper">
                        </div>
                        <a href="#message-start-wrapper" class="special-anchor-direct btn btn-info psy-btn btn-back btn-back-from-play icon-arrow-left" data-slide="message-start-wrapper" data-username=<?php echo getCurrentUser(); ?>>
                                Quit
                        </a>
                        <a class="btn btn-info psy-btn btn-next-turn" data-username=<?php echo getCurrentUser(); ?>>Start</a>
                        <!--div class="view-panel panel panel-info">
                            <div class="panel-heading">Messaging</div>
                            	<div class="panel-body" style="padding:7px !important">
					<div style="border-bottom:1px solid #ddd">
						<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/701.png" />
						<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/702.png" />
				 		<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/704.png" />
		            			<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/706.png" />
                                 		<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/710.png" />
                                 		<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/713.png" />
                                 		<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/714.png" />
						<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/734.png" />
                                 		<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/735.png" />
                                 		<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/740.png" />
                                 		<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/725.png" />
						<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/92.png" />
                                		<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/71.png" />
						<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/322.png" />
						<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/426.png" />
						<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/471.png" />
						<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/463.png" />	
						<img src="http://sapir.psych.wisc.edu/wp-content/uploads/emojis/emoji-extractor/images/160x160/278.png" />
					</div>
				</div>
                        </div-->
                    </div>
                    <div class="input-panel panel panel-info">
                        <div class="panel-heading">Input Window<span id="toggle" style="padding-left:40px" onclick="toggle()">Switch to Drawing</span><span id="time-left" style="padding-left:20px"></span></div>
                        <div class="panel-body">
                        </div>
			<canvas style="background-color:white;display:none;" height="408" width="447" id="myCanvas"></canvas>
			<iframe id="iframeId" src="http://sapir.psych.wisc.edu/~yan/Psycho-Project/violent-theremin/"  style="width: 447px;height: 408px;"></iframe> 
                    </div>
                </div>
                
            </div>
        </div>    
        <!-- end of slides -->
    </div>
    <!-- end of slider -->
</html>
