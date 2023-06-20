<?php

require "condb.php";

session_start();

$contact = $_POST["contact"];
$password = $_POST["password"];

//$contact = "09089430851";
//$password = "1234";

$mysqli_query = "SELECT * FROM app_users where contact_number like '$contact' and password like '$password'";

$result = mysqli_query($conn,$mysqli_query);

	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result); 
		$name =$row["name"];
		$id =$row['id'];
		$_SESSION['uid'] = $row["id"];
		$uid = $_SESSION['uid'];

		if(isset($_SESSION['uid'])) {
		  // Session is valid, return data to app
		  $data = array('Session is valid',$id,null,null);
		  echo json_encode($data);
		} else {
		  // Session is invalid, return error message to app
		  $data = array('error' => 'Session is invalid');
		  echo json_encode($data);
		}
	}
	else{
		$response = "Data is invalid!";
	}

?>