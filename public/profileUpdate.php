<?php

require "condb.php";

$name = $_POST["name"];
$cnum = $_POST["username"];
$password = $_POST["password"];
$uid = $_POST["extra"];

$mysqli_query = "UPDATE app_users SET name = '$name', contact_number = '$cnum', password = '$password' WHERE id = $uid";

$result = mysqli_query($conn,$mysqli_query);

     if($result){
            $data = array('Profile updated',$uid,$name,$contact_number);
            echo json_encode($data);
     }
     else{
          $response = "Data is invalid!";
     }

echo "why";

?>