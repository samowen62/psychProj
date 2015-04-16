<?php
    class Community{
        public $_id;
        public $_name;
        public $_capacity;
        public $_user_cnt;
        
        function __construct($id, $name, $capacity, $user_cnt){
            $this->_id = $id;
            $this->_name = $name;
            $this->_capacity = $capacity;
            $this->_user_cnt = $user_cnt;
        }
    }

?>