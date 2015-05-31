<!doctype html>
<html lang="en">
	<head>
		<link href="css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link href="css/header.css" rel="stylesheet" type="text/css"/>
		<link href="css/dashboard.css" rel="stylesheet" type="text/css"/>
		<link href="css/editGrid.css" rel="stylesheet" type="text/css"/>

        <title>Psycho Project</title>
		<script type="text/javascript" src="js/jQuery/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="js/jQuery/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="js/header.js"></script>
		<script type="text/javascript" src="js/editgrid.js"></script>
	</head>
	<body>
		<?php
            defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');
			require_once(ROOT . '/header.php');
            require_once(ROOT . '/include/user_functions.php');
            require_once(ROOT . '/include/login_functions.php');
	    require_once(ROOT . '/include/constant.php');	

		$imgLoc = "http://sapir.psych.wisc.edu/wp-content/uploads/";

            if (user_isLogged()){
                // header("Location: http://sapir.psych.wisc.edu/Psycho-Project/dashboard.php");
            }else{
                header("Location: http://sapir.psych.wisc.edu/~yan/Psycho-Project/login.php");
            }

		//$target_dir = ROOT."/uploads/";
		$target_dir = "/var/www/wp-content/uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$err_msg = '';
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
    			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    			if($check !== false) {
        			$uploadOk = 1;
    			} else {
        			$err_msg =  "File is not an image.";
        			$uploadOk = 0;
    			}
		}

		if (file_exists($target_file)) {
    			$err_msg =  "Sorry, file already exists.";
    			$uploadOk = 0;
		}

		if ($_FILES["fileToUpload"]["size"] > 2000000) {
    			$err_msg = "Sorry, your file is too large.";
    			$uploadOk = 0;
		}

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
 	   	//	$err_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    			$uploadOk = 0;
		}

		$success_msg = '';
		if ($uploadOk != 0) {
    			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        			$success_msg = "File ". basename( $_FILES["fileToUpload"]["name"]). " uploaded.";
    				storeImg($_FILES["fileToUpload"]["name"]);
			} else {
        			$err_msg = "Sorry, there was an error uploading your file.";
    			}
		}


		function storeImg($name){	
			$num = $_POST['num'];
			if($num < 0 || $num > 9)
				return;

			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
		 	$query = "UPDATE grid SET item".$num." = '". basename($_FILES["fileToUpload"]["name"])."' WHERE grid_id = '1'";	
			mysqli_query($dbc, $query);
			mysqli_close($dbc);
		}

                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $query = "SELECT * FROM grid WHERE grid_id = '1'";
                $res = mysqli_query($dbc, $query);
		$grid = mysqli_fetch_array($res);
                mysqli_close($dbc);
		
		echo "<div style='display:none' id='fff'>".$err_msg."</div>";
		//echo var_dump($grid);
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
					<div class="panel-heading">Select a Picture. Preferably a png with no background</div>
					<ul class='grid_list' data-gridid='$grid_id'>
                    			<li class='grid_row'>
                            			<ul class='grid_row_list'>
                                			<li class='grid_item' id='1'><img src="<?php echo $imgLoc.$grid['item1'];?>" alt="item 1"/></li>
                   					<li class='grid_item' id='2'><img src="<?php echo $imgLoc.$grid['item2'];?>" alt="item 2"/></li>
                    					<li class='grid_item' id='3'><img src="<?php echo $imgLoc.$grid['item3'];?>" alt="item 3"/></li>
						</ul>
					</li>
					<li class='grid_row'>
                                                <ul class='grid_row_list'>
                                                        <li class='grid_item' id='4'><img src="<?php echo $imgLoc.$grid['item4'];?>" alt="item 4"/></li>
                                                        <li class='grid_item' id='5'><img src="<?php echo $imgLoc.$grid['item5'];?>" alt="item 5"/></li>
                                                        <li class='grid_item' id='6'><img src="<?php echo $imgLoc.$grid['item6'];?>" alt="item 6"/></li>
                                                </ul>
                                        </li>
					<li class='grid_row'>
                                                <ul class='grid_row_list'>
                                                        <li class='grid_item' id='7'><img src="<?php echo $imgLoc.$grid['item7'];?>" alt="item 7"/></li>
                                                        <li class='grid_item' id='8'><img src="<?php echo $imgLoc.$grid['item8'];?>" alt="item 8"/></li>
                                                        <li class='grid_item' id='9'><img src="<?php echo $imgLoc.$grid['item9'];?>" alt="item 9"/></li>
                                                </ul>
                                        </li>

            				</ul>
                                </div>
				<div style="width:682px; height: 30px; margin-left:100px">
                                        <form action="editGrid.php" method="post" enctype="multipart/form-data">
						<div style="float:left; width:25%">
							Change Pictures:
						</div>
                                                <div style="float:left; width:75%">
                                                	<input style="float:left" type="file" name="fileToUpload" id="fileToUpload">
                                                	<input class="btn" style=" background-color: #bce8f1" type="submit" value="Upload" name="submit">
							<a class="btn" style="  background-color: #d6d6d6;"  id="prev" onclick="toggle(0)"  href="#" />Previous</a>
							<a class="btn" style="  background-color: #d6d6d6;"  id="next" onclick="toggle(1)"  href="#" />Next</a>
						</div>
						<!--div style="float:left; width:10%">
                                                	<select name="num">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                	</select>

						</div-->
						<input style="display:none" type="text" name="num" id="num" />
                                        </form>
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
