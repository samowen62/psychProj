<?php
    defined('ROOT') or define('ROOT', '/home/yan/public_html/Psycho-Project');

    require_once(ROOT . '/include/user_functions.php');
?>
<div id="st-container" class="st-container">
    <nav class="st-menu st-effect" id="menu">
        <div class="user_pic"></div>
        <div class="user_name">
            <h5><?php echo getCurrentUser(); ?></h5>
        </div>
        <ul>
            <li>
                <span class="option_word subtitle">Main</span>
            </li>
            <li>
                <a class="" href="/~yan/Psycho-Project/dashboard.php">
                    <span class="icon icon-home"></span>
                    <span class="option_word">Dashboard</span>
                </a>
            </li>
            <li>
                <a class="" href="#">
                    <span class="icon icon-cog"></span>
                    <span class="option_word">Settings</span>
                </a>
            </li>
            <li>
                <span class="option_word subtitle">Activity</span>
            </li>
            <li>
                <a class="" href="#">
                    <span class="icon icon-users"></span>
                    <span class="option_word">Community</span>
                </a>
            </li>
            <li>
                <a class="" href="/~yan/Psycho-Project/message.php">
                    <span class="icon icon-bubbles"></span>
                    <span class="option_word">Messaging</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="st-pusher">
        <div class="st-content">
            <div class="st-content-inner">
                <nav id="p_header" class="global-navbar navbar navbar-default" role="navigation">
                    <div class="icon-reorder menu-icon-area" data-effect="st-effect" id="menu-btn"></div>
                    <div class="nav-option-area">
                        <div class="nav-option icon-search"></div>
                        <div class="nav-option icon-calendar"></div>
                        <form id="logout-form" method="post" action="api/logout_api.php">
                            <button type="submit" class="nav-option icon-exit"></button>
                        </form>
                    </div>
                </nav>
        