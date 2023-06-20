<?php

require "condb.php";


$uid = $_POST["username"];

$mysqli_query = "SELECT * FROM app_users where id = $uid";

$result = mysqli_query($conn,$mysqli_query);

     if(mysqli_num_rows($result)>0){
          $row = mysqli_fetch_assoc($result); 
          $name =$row["name"];
          $contact_number =$row["contact_number"];

            $data = array('Session is valid',$uid,$name,$contact_number);
            echo json_encode($data);
     }
     else{
          $response = "Data is invalid!";
     }


?>