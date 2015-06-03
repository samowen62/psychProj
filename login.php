<!doctype html>
<html lang="en">
	<head>
		<link href="css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link href="css/header.css" rel="stylesheet" type="text/css"/>
		<link href="index.css" rel="stylesheet" type="text/css"/>

        <title>Psycho Project</title>
		<script type="text/javascript" src="js/jQuery/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="js/jQuery/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="js/header.js"></script>
	</head>
	<body>
		<?php
			require_once('header.php');
		?>
                
                        <div class="panel panel-info login-panel">
                            <div class="panel-heading">User Login</div>
                            <div class="panel-body">
                                <div class="login-form-wrapper">
                                    <form method="post" action="api/login_api.php">
                                        <div class="form-group">
                                            <label class="login-label" for="username">Username</label>
                                            <input type="username" name="username" class="login-input form-control" value="<?php echo $_GET['username']; ?>" id="username" placeholder="Enter Username">
                                        </div>
                                        <div class="form-group">
                                            <label class="login-label" for="password">Password</label>
                                            <input type="password" name="password" class="login-input form-control" value="<?php echo $_GET['password']; ?>" id="password" placeholder="Enter Password">
                                        </div>
                                        <br/>
                                        <button type="submit" class="login-submit btn btn-default">Login</button>
                                        <a class="signup-link" href="signup.php">Not a member yet? Sign up here</a>
                                        <a class="admin-signin" href="admin/admin-login.php">Administrator Sign In</a>
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
