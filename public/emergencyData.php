<?php

require "condb.php";

$sql = "SELECT id, team_name, contact_number FROM emergencyteam";

$result = mysqli_query($conn,$sql);

$rows = array();


while ($row = mysqli_fetch_assoc($result)) {
     $rows[] = $row;
}

echo json_encode($rows);

?>