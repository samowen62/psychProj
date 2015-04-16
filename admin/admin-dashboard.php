<!doctype html>
<html lang="en">
	<head>
		<link href="../css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link href="../css/header.css" rel="stylesheet" type="text/css"/>
		<link href="css/admin-dashboard.css" rel="stylesheet" type="text/css"/>

        <title>Psycho Project</title>
		<script type="text/javascript" src="../js/jQuery/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="../js/jQuery/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="../js/header.js"></script>
		<script type="text/javascript" src="js/admin-dashboard.js"></script>
		<script type="text/javascript" src="js/knockout-3.1.0.js"></script>
	</head>
	<body>
		<?php
            defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');
			require_once(ROOT . '/admin/admin-header.php');
            // require_once(ROOT . '/include/user_functions.php');
            require_once(ROOT . '/admin/include/login_functions.php');
            require_once(ROOT . '/admin/include/dashboard_functions.php');

            if (user_isLogged()){
                // header("Location: http://sapir.psych.wisc.edu/Psycho-Project/dashboard.php");
            }else{
                header("Location: http://sapir.psych.wisc.edu/~yan/Psycho-Project/admin/admin-login.php");
            }
		?>
                
                        <div class="dashboard">
                            
                        </div>
                    </div> <!-- st-content-inner -->
                </div> <!-- st-content -->
            </div> <!-- st-pusher -->
        </div> <!-- st-container -->
	</body>
</html>