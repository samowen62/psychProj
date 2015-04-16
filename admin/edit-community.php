<!doctype html>
<html lang="en">
	<head>
		<link href="../css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link href="../css/header.css" rel="stylesheet" type="text/css"/>
		<link href="css/edit-community.css" rel="stylesheet" type="text/css"/>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

        <title>Psycho Project</title>
		<script type="text/javascript" src="../js/jQuery/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="../js/jQuery/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="../js/header.js"></script>
		<script type="text/javascript" src="js/edit-community.js"></script>
		<script type="text/javascript" src="js/knockout-3.1.0.js"></script>
	</head>
	<body>
		<?php
            defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');
			require_once(ROOT . '/admin/admin-header.php');
            // require_once(ROOT . '/include/user_functions.php');
            require_once(ROOT . '/admin/include/login_functions.php');

            if (user_isLogged()){
                // header("Location: http://sapir.psych.wisc.edu/Psycho-Project/dashboard.php");
            }else{
                header("Location: http://sapir.psych.wisc.edu/~yan/Psycho-Project/admin/admin-login.php");
            }
		?>
                            <section class="edit-community-panel">
                                <div id="community-capacity">
                                    <span class="capacity-icon icon-head"></span>
                                    <span class="capacity-text" data-bind="text: capacityText"></span>
                                </div>
                                <div class="edit-community-capacity">
                                    <span class="edit-icon icon-edit"></span>
                                    <span class="edit-text">Edit Capacity</span>
                                </div>
                                <div class="edit-capacity-content">
                                    <div>
                                        <input class="form-control" placeholder="New Capacity" 
                                               data-bind="event: {
                                                            keyup: capacityChange
                                                          }"
                                               id="newCapacity"/>
                                    </div>
                                    <button class="btn btn-default change-btn"
                                        data-bind="enable: (currentIsRoot() && validCapacity() == 1),
                                                   event:{
                                                        click: updateCapacity
                                                   }">Change
                                    </button>
                                </div> 
                                <div class="migrate-users">
                                    <span class="migrate-icon icon-shuffle"></span>
                                    <span class="migrate-text">Migrate Users</span>
                                </div>
                                <div class="migrate-users-content">
                                    <select class="form-control"
                                            data-bind="options: migrateCommunityPool,
                                                       optionsText: 'optionText',
                                                       value: selectedCommunity,
                                                       optionsCaption: 'Choose...'">
                                        
                                    </select>
                                    <button class="btn btn-default migrate-btn"
                                        data-bind="enable: currentIsRoot(),
                                                   event:{
                                                        click: migrateUser
                                                   }">Migrate
                                    </button>
                                </div> 
                            </section>
                            <section class="edit-community-main">
                                <div class="community-name">
                                    Users in <?php echo $_POST['community-name']; ?>
                                </div>
                                <div class="community-capacity">
                                    <?php echo $_POST['community-capacity']; ?>
                                </div>
                                <table class="">
                                    <thead>
                                        <th class="head-check icon-check"></th>
                                        <th class="head-userid">User ID</th>
                                        <th class="head-username">username</th>
                                        <th class="head-gamenum">Game Count</th>
                                        <th class="head-option"></th>
                                    </thead>
                                    <tbody data-bind="foreach: currentPage">
                                        <tr data-bind="event:{
                                                        mouseover: $parent.entryHover,
                                                        mouseout: $parent.entryUnhover,
                                                        click: function(data, event) { $parent.singleSelect(data, event); }
                                                       },
                                                       css:{
                                                        hoveredEntry : hovered,
                                                        selectedEntry: selected
                                                       }">
                                            <td class="head-check icon-check" 
                                                data-bind="style:{
                                                            opacity: hovered() ? '1' : selected() ? '1' : '0'
                                                             },
                                                           css:{
                                                            selectedCheck: selected
                                                           },
                                                           event:{
                                                            click: $parent.multiSelect
                                                           }"></td>
                                            <td class="head-userid" data-bind="text: id"></td>
                                            <td class="head-username" data-bind="text: username"></td>
                                            <td class="head-gamenum" data-bind="text: game_cnt"></td>
                                            <td class="head-option">
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="pagination">
                                    <div class="pager">
                                        <span class="pre-page icon-chevron-left" 
                                              data-bind="event:{
                                                            click: currentPageNum() == 1 ? function(){} : prevPage
                                                         },
                                                         css:{
                                                            pre_page_disabled : currentPageNum() == 1
                                                         }"></span>
                                        <h4 class="page-num" data-bind="text: pageNum"></h4>
                                        <span class="next-page icon-chevron-right"
                                              data-bind="event:{
                                                            click: currentPageNum() == totalPage() ? function(){} : nextPage
                                                         },
                                                         css:{
                                                            next_page_disabled : currentPageNum() == totalPage()

                                                         }"></span>
                                    </div>
                                    <div class="page-detail" data-bind="text: itemNum"></div>
                                </div>
                            </section>
                        </div>
                    </div> <!-- st-content-inner -->
                </div> <!-- st-content -->
            </div> <!-- st-pusher -->
        </div> <!-- st-container -->
	</body>
</html>