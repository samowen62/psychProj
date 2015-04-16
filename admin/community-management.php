<!doctype html>
<html lang="en">
	<head>
		<link href="../css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link href="../css/header.css" rel="stylesheet" type="text/css"/>
		<link href="css/community-management.css" rel="stylesheet" type="text/css"/>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

        <title>Psycho Project</title>
		<script type="text/javascript" src="../js/jQuery/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="../js/jQuery/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="../js/header.js"></script>
		<script type="text/javascript" src="js/community-management.js"></script>
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
                            <section class="community-panel">
                                <div class="community-add-head">
                                    <span class="add-icon icon-circle-plus"></span>
                                    <span class="add-text">Add</span>
                                </div>
                                <div class="community-add-content">
                                    <div>
                                        <input class="form-control" placeholder="Community Name" 
                                               data-bind="event: {
                                                            keyup: communityNameChange
                                                          }"
                                               id="newCommunityName"/>
                                    </div>
                                    <div>
                                        <input class="form-control" placeholder="Community Capacity"
                                               data-bind="event: {
                                                            keyup: newCapacityChange
                                                          }"
                                               id="newCapacity"/>
                                    </div>
                                    <button class="btn btn-default add-btn"
                                        data-bind="enable: (currentIsRoot() && validCommunityName() == 1 && validCapacity() == 1),
                                                   event:{
                                                        click: addCommunity
                                                   }">Add
                                    </button>
                                </div> 
                                <div class="community-delete-head" data-bind="event:{
                                                                            click: currentIsRoot() ? multiDelete : function(){ showPrompt('info', 'You do not have permission to perform this operation'); }
                                                                          }">
                                    <span class="delete-icon icon-circle-minus"></span>
                                    <span class="delete-text">Delete Selected</span>
                                </div>
                                <div class="community-info-head">
                                    <span class="info-icon icon-info-large-outline"></span>
                                    <span class="info-text">Note</span>
                                </div>
                                <div class="community-info-content">
                                    <h4>Only Root administrator can perform operations in this page, others can only view information here.</h4>    
                                </div>
                            </section>
                            <section class="community-main">
                                <table class="">
                                    <thead>
                                        <th class="head-check icon-check"></th>
                                        <th class="head-id">ID</th>
                                        <th class="head-name">Name</th>
                                        <th class="head-capacity">Capacity</th>
                                        <th class="head-user-cnt">User #</th>
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
                                            <td class="head-id" data-bind="text: id"></td>
                                            <td class="head-name" data-bind="text: name"></td>
                                            <td class="head-capacity" data-bind="text: capacity"></td>
                                            <td class="head-user-cnt" data-bind="text: user_cnt"></td>
                                            <td class="head-option">
                                                <div class="community-delete" data-bind="event:{
                                                                                        click: $parent.currentIsRoot() ? $parent.singleDelete : function(){ event.stopPropagation(); showPrompt('info', 'You do not have permission to perform this operation'); }
                                                                                    }">
                                                    <span class="icon-trash"></span>
                                                    <span class="delete-txt">DELETE</span>
                                                </div>
                                                <div class="community-edit" data-bind="event:{
                                                                                        click: $parent.currentIsRoot() ? $parent.edit : function(){ event.stopPropagation(); showPrompt('info', 'You do not have permission to perform this operation'); }
                                                                                    }">
                                                    <span class="icon-edit">
                                                        <form class="edit-form" action="edit-community.php" method="post">
                                                            <input name="community-name" 
                                                                   type="hidden" 
                                                                   data-bind="attr:{
                                                                                value: name
                                                                                  }"/>
                                                            <input name="community-capacity" 
                                                                   type="hidden" 
                                                                   data-bind="attr:{
                                                                                value: capacity
                                                                                  }"/>
                                                        </form>
                                                    </span>
                                                    <span class="edit-txt">EDIT
                                                        <form class="edit-form" action="edit-community.php" method="post">
                                                            <input name="community-name" 
                                                                   type="hidden" 
                                                                   data-bind="attr:{
                                                                                value: name
                                                                                  }"/>
                                                            <input name="community-capacity" 
                                                                   type="hidden" 
                                                                   data-bind="attr:{
                                                                                value: capacity
                                                                                  }"/>
                                                        </form>
                                                    </span>
                                                </div>
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