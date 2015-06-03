<!doctype html>
<html lang="en">
	<head>
		<link href="css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link href="css/header.css" rel="stylesheet" type="text/css"/>
		<link href="css/dashboard.css" rel="stylesheet" type="text/css"/>

        <title>Psycho Project</title>
		<script type="text/javascript" src="js/jQuery/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="js/jQuery/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="js/header.js"></script>
		<script type="text/javascript" src="js/dashboard.js"></script>
	</head>
	<body>
		<?php
            defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');
			require_once(ROOT . '/header.php');
            require_once(ROOT . '/include/user_functions.php');
            require_once(ROOT . '/include/login_functions.php');

            if (user_isLogged()){
                // header("Location: http://sapir.psych.wisc.edu/Psycho-Project/dashboard.php");
		//if(!currentUser()['admin'])
		//	 header("Location: http://sapir.psych.wisc.edu/~yan/Psycho-Project/dashboard.php");
            }else{
                header("Location: http://sapir.psych.wisc.edu/~yan/Psycho-Project/login.php");
            }
		?>
                
                        <div class="dashboard-wrapper">
                            <section class="dashboard-main">
                                <div class="dashboard-status">
                                    <div class="status-item">
                                        <div id="status-users"class="status-content status-icon">
                                            <span class="icon-users2"></span>
                                        </div>
                                        <div class="status-content">
                                            <h3><?php echo getUserNumber(); ?></h3>
                                            <h6>REGISTERED USERS</h6>
                                        </div>
                                    </div>
                                    <div class="status-item">
                                        <div id="status-stat"class="status-content status-icon">
                                            <span class="icon-signal"></span>
                                        </div>
                                    </div>
                                    <div class="status-item">
                                        <div id="status-msg"class="status-content status-icon">
                                            <span class="icon-comment-alt"></span>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel panel-info activity-detail">
                                    <div class="panel-heading">Activity Detail</div>
                                    <div class="panel-body">
                                        <div class="prev-turn">
                                            <div data-username="" data-gameid="" data-turnnum="" class="icon-arrow-left"></div>
                                        </div>
                                        <div class="current-turn">
                                            <div class="turn-grid-wrapper">
                                                
                                            </div>
                                            <div class="turn-summary-wrapper">
                                                
                                            </div>
                                        </div>
                                        <div class="next-turn">
                                            <div class="icon-arrow-right"></div>
                                        </div>
                                        <div class="turn-pagination">
                                            
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section class="dashboard-activity">
                                <div class="activity-wrapper">
                                    <header class="activity-header">
                                        <h5>LATEST ACTIVITIES</h5>
                                    </header>
                                    <ul class="activity-list">
                                    </ul>
                                </div>
                            </section>
                        </div>
                    </div> <!-- st-content-inner -->
                </div> <!-- st-content -->
            </div> <!-- st-pusher -->
        </div> <!-- st-container -->
	</body>
</html>
