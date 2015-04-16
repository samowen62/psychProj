<?php
    class Users{
        public $_id;
        public $_username;
        public $_game_cnt;
        
        function __construct($id, $username, $game_cnt){
            $this->_id = $id;
            $this->_username = $username;
            $this->_game_cnt = $game_cnt;
        }
    }

?>