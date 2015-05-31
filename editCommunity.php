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


		if(isset($_POST["submit"])) {
			//if(isset($_POST["username"]) && isset($_POST['community_id'])){


			//}else{
				if(isset($_POST["community"])){
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
					$update = "UPDATE community SET capacity = {$_POST['Cap'.$_POST["community"]]} WHERE name = '{$_POST["community"]}'";
					$dbc->query($update);
					mysqli_close($dbc);
				}

				if(isset($_POST["user"]) && isset($_POST["comm_id"]) && $_POST['user'] != "none"){
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                	                $check = "SELECT count(*) FROM community_has_user WHERE username='{$_POST['user']}' AND community_id='{$_POST['comm_id']}'";
					$num = mysqli_query($dbc,$check);
					$count = mysqli_fetch_array($num);
					if(intval($count[0]) == 0){
						$insert = "INSERT INTO community_has_user VALUES ('{$_POST["user"]}', '{$_POST["comm_id"]}')";
                        	                $dbc->query($insert);
					}                                
					mysqli_close($dbc);
				}
			//}
		}

		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $query = "SELECT community.name, community.capacity, community_has_user.username, community.id FROM community 
                                LEFT OUTER JOIN community_has_user ON community.id = community_has_user.community_id";
                $res = mysqli_query($dbc, $query);

                while($comm = mysqli_fetch_array($res))
                        $communities[] = $comm;

                $query = "SELECT username FROM user";
                $res = mysqli_query($dbc, $query);
                while($user = mysqli_fetch_array($res))
                        $users[] = $user;


                mysqli_close($dbc);



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
				<form action="editCommunity.php" accept-charset="utf-8" method="post" enctype="multipart/form-data">
                                	<div class="panel panel-info activity-detail">
						<div class="panel-heading">Edit the Communities Below</div>
                                		<?php 
							//print_r($communities);
							$name = '';
							$i = 1;
							echo "<div>";

							foreach($communities as $k => $v){
								if($name != $v[0]){
									echo "</div><div class='comm' id='row{$i}'><span class='title' onclick='changeComm(\"{$v[0]}\",{$i},{$v[3]})'>{$v[0]}</span><span class='cap'>Capacity:<input name='Cap{$v[0]}' value='{$v[1]}' type='text'/></span>";	
									$name = $v[0];
									$i++;
								}
								if($v[2] != '')
									echo "<span id='user{$i}' class='user'>{$v[2]}<a href='javascript:void(0)' style='color: red;margin-left: 3px;' onclick='deleteUser(\"{$v[2]}\",\"{$v[3]}\",$i)'>X</a></span>";
							}
							
							echo "</div>";
						?>
					</div>
					<div style="width:682px; height: 30px; margin-left:100px">
							<div style="float:left; width:33%">
								<span id="c">Add User to Community</span>:
							</div>
							<div style="float:left; width:10%">
                                	                	<select name="user">
                                        				<option value="none">-</option>
						        	<?php
									foreach($users as $k => $u)
					
										echo "<option value='{$u[0]}'>{$u[0]}</option>";
								?>
								</select>

							</div>
							<div style="float:left; width:30%;margin-bottom:25px">
                                                	        <input class="btn" style=" background-color: #bce8f1" type="submit" value="Save" name="submit">
                                                	</div>
                                       		<input type="text" style="display:none" id="hidden" name="community" />
						<input type="text" style="display:none" id="hidden_id" name="comm_id" />
                                		<input style='display:none' name='username' id="del_user" type='text'/>
                                                <input style='display:none' name='community_id' id="del_comm_id" type='text'/> 
					</div>
				</form>

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
	<script type="text/javascript">
		function changeComm(val, row, id){
			$(".comm").css('background-color','white');
			$("#row"+row).css('background-color','#00cc00');
			$('#c').text("Add User to "+val);
			$('#hidden').val(val);
			$('#hidden_id').val(id);
		}

		function deleteUser(user, comm, row){
			$('#user' + row).hide();
			$.ajax({
                                type: 'POST',
                                url: "http://sapir.psych.wisc.edu/~yan/Psycho-Project/api/activity_api.php",
                //              cache: false,
                                data: {
                                        user: user,
                                        community: comm,
                                        action: 'delete_user'
                                }
                        }).done(function(data){
                                console.log(data);
                        });

		}
	</script>
	</body>
</html>
