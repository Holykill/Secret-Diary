<?php
$link = mysqli_connect("DB connection", "info that", "you will", "not see");
        
        if(mysqli_connect_error()){
            
            die("Database Connection Error!");
            
        }

?>