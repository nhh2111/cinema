<?php

$conn = new mysqli('localhost','root','','usersform');
if(!$conn){
    echo "false" . mysqli_connect_error();
}

?>