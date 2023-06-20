<?php

require "condb.php";
session_start();


$current_timestamp = time();
$current_date_time = date('Y-m-d H:i:s', $current_timestamp);

if ($conn) {
    if(!empty($_POST['picture'])){
        $path = 'images/'.date("d-m-Y"). '-'.time().'-'.rand(10000,100000).'.jpeg';
        
        $name = $_POST['name'];
        $lat = $_POST['latitude'];
        $lang = $_POST['longitude'];
        $desc = $_POST['description'];
        $uid = $_POST['user_id'];

        if (file_put_contents($path,
            base64_decode($_POST['picture']))) {
            $sql = "INSERT INTO cases (case_name, latitude, longitude, description, file_name, status,created_by,created_at) 
                            VALUES ('".$name."','".$lat."','".$lang."','".$desc."','".$path."','0','".$uid."','".$current_date_time."')";
            if (mysqli_query($conn,$sql)) {
                    echo 'success';
            }else{
                echo 'failed';
            }                
            
        }else{
            echo 'Failed to upload';
        }
    }else{
        echo 'No image found'. $uid;
    }

}else{
    echo "Database not connected";
}

?>