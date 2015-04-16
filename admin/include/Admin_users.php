<?php
    class Admin_users{
        public $_id;
        public $_username;
        public $_root;
        
        function __construct($id, $username, $root){
            $this->_id = $id;
            $this->_username = $username;
            $this->_root = $root;
        }
    }

    function getCurrentUser($username){
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * FROM admin WHERE username='{$username}';";
        $result = mysqli_query($dbc, $query);
        while ($row = mysqli_fetch_array($result)){
            $admin_id = $row['admin_id'];
            $username = $row['username'];
            $root = $row['root'];
            // echo "<tr><td>{$admin_id}</td><td>{$username}</td</tr>";
            $admin_user = new Admin_users($admin_id, $username, $root);
        }
        mysqli_close($dbc);
        echo json_encode($admin_user);
        mysqli_close($dbc);
    }
?>