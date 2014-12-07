<?php
// Load database connection info
include('db.conf.php');

// Connect to the SQL database
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql_query = "Select * From crunchbase.markets";

$sql_result = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);

// Close the database connection
$mysqli->close();

$returnString = "[";
$curRow = 1;
while ($row = $sql_result->fetch_assoc()) {
	$returnString .= json_encode($row);
	if ($curRow != $sql_result->num_rows) {
		$returnString .= ",";
	}
	$curRow++;
}
$returnString .= "]";

echo $returnString;
?>