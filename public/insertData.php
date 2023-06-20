<?php
require "condb.php";

$name = $_POST["name"];
$contact = $_POST["contact"];
$password = $_POST["password"];

$checkExisting = "SELECT * FROM app_users where contact_number like '$contact'";
$result_check = mysqli_query($conn,$checkExisting);

if(mysqli_num_rows($result_check)>0){

		Print("Login Successful..Welcome ".$name);
}
else{
		$mysqli_query = "INSERT INTO app_users VALUES (NULL,'$name','$contact','$password');";

		$result = mysqli_query($conn,$mysqli_query);

		if($result){
			$data = array('Registed!',null,null,null);
		 	 echo json_encode($data);
		}
		else{
			print("NOT Successful"); 
		}
}




?>
