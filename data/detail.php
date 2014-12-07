<?php
// Load database connection info
include('db.conf.php');

// Connect to the SQL database
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$type = $_GET["t"]; //t = c(ompany) | i(nvestor)
$name = ucwords(urldecode($_GET["q"])); //q = a company/investor name

switch($type) {
	case "c":
	$sql_query =
		"Select
		  crunchbase.investments.company_name,
		  crunchbase.investments.investor_name,
		  crunchbase.investments.funded_at,
		  crunchbase.investments.raised_amount_usd,
		  (crunchbase.investments.raised_amount_usd/(select sum(crunchbase.investments.raised_amount_usd) from crunchbase.investments where crunchbase.investments.company_name = \"" . $name . "\"))*100 As percent_total
		From
		  crunchbase.investments
		Where
		  crunchbase.investments.company_name = \"" . $name . "\" AND
		  crunchbase.investments.raised_amount_usd > 0
		Order By
		  crunchbase.investments.funded_at Desc";
	break;

	case "i":
	$sql_query =
		"Select                          
		  crunchbase.investments.investor_name,
		  crunchbase.investments.company_name,
		  crunchbase.investments.funded_at,
		  crunchbase.investments.raised_amount_usd,
		  (crunchbase.investments.raised_amount_usd/(select sum(crunchbase.investments.raised_amount_usd) from crunchbase.investments where crunchbase.investments.investor_name = \"" . $name . "\"))*100 As percent_total
		From
		  crunchbase.investments
		Where
		  crunchbase.investments.investor_name = \"" . $name . "\" AND
		  crunchbase.investments.raised_amount_usd > 0
		Order By
		  crunchbase.investments.funded_at Desc";
	break;
}

//Query db and store the results
$sql_result = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);

// Close the database connection
$mysqli->close();


// Parse and format the data
$results = [];
if($sql_result->num_rows > 0) {
	foreach($sql_result->fetch_all() as $row) {
		$results[$row[2]] = $row[4];
	}
}


$chartsData = "[";
$curRow = 1;
foreach($results as $date => $value) {
	$chartsData .= "{name: \"$date\", y: $value}";
	if ($curRow != count($results)) {
		$chartsData .= ",";
	}
	$curRow++;
}
$chartsData .= "]";

$chartTitle = "Investments" . ($type == 'c' ? " to " : " from ") . $name;
?>