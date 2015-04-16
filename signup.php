<!doctype html>
<html lang="en">
	<head>
		<link href="css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link href="css/header.css" rel="stylesheet" type="text/css"/>
		<link href="css/signup.css" rel="stylesheet" type="text/css"/>

        <title>Psycho Project</title>
		<script type="text/javascript" src="js/jQuery/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="js/jQuery/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="js/header.js"></script>
		<script type="text/javascript" src="js/signup.js"></script>
	</head>
	<body>
		<?php
            defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');
			require_once(ROOT . '/header.php');
            require_once(ROOT . '/include/user_functions.php');
            require_once(ROOT . '/include/signup_functions.php');
		?>
                
                        <div class="panel panel-info signup-panel">
                            <div class="panel-heading">User Signup</div>
                            <div class="panel-body">
                                <div class="signup-form-wrapper">
                                    <form method="post" action="api/signup_api.php">
                                        <div class="form-group group_username">
                                            <label class="signup-label" for="username">Username</label>
                                            <input type="username" name="username" class="signup-input form-control" id="username" placeholder="Enter Username">
                                        </div>
                                        <div class="form-group group_password">
                                            <label class="signup-label" for="password">Password</label>
                                            <input type="password" name="password" class="signup-input form-control" id="password" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group group_confirm_password">
                                            <label class="signup-label" for="confirm_password">Confirm Password</label>
                                            <input type="password" name="confirm_password" class="signup-input form-control" id="confirm_password" placeholder="Confirm Password">
                                        </div>
                                        <br/>
                                        <select name="community" class="community-select form-control">
                                          <?php
                                            $all_rows = get_all_community_from_db();
                                            draw_community_select($all_rows);
                                          ?>
                                        </select>
                                        <button type="submit" class="signup-submit btn btn-default">Signup</button>
                                        <a class="login-link" href="login.php">Aleady signup? Login here</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- st-content-inner -->
                </div> <!-- st-content -->
            </div> <!-- st-pusher -->
        </div> <!-- st-container -->
	</body>
</html>