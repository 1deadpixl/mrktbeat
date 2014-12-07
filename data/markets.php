<?php
// Load database connection info
include('data/db.conf.php');

// Connect to the SQL database
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}



$url_query = isset($_POST["q"]) ? $_POST["q"] : $_GET["q"]; //q = comma-seperated list of markets

$markets = explode(",",urldecode($url_query));

$sql_queries = [];

foreach ($markets as $index => $market) {
	$sql_queries[ucwords($market)] =
		"Select
		  crunchbase.investments.funded_at,
		  Sum(crunchbase.investments.raised_amount_usd) As `raised_amt`
		From
		  crunchbase.investments
		Where crunchbase.investments.company_market = \"" . $market . "\" 
		Group By
		  crunchbase.investments.funded_at
		Order By
		  crunchbase.investments.funded_at";
}


$sql_results = [];
foreach($sql_queries as $market => $sql_query) {
	//Query db and add it to the results array
	$sql_results[$market] = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
}


//for prefilling TokenInput field
$tokenInputData = "[";
$curRow = 1;
foreach ($markets as $index => $market) {
	$sql_query = "Select
				  crunchbase.markets.id,
				  crunchbase.markets.name
				From
				  crunchbase.markets
				Where
				  crunchbase.markets.name = \"$market\"";

	$result = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
	$tokenInputData .= json_encode($result->fetch_assoc());
	if ($curRow != count($markets)) {
		$tokenInputData .= ",";
	}
	$curRow++;
}
$tokenInputData .= "]";

// Close the database connection
$mysqli->close();


// Parse and format the data
$chartsData = "[";

$curResult = 1;
foreach($sql_results as $market => $sql_result) {
	if($sql_result->num_rows > 0) {
		$chartsData .= "{name: \"$market\", zIndex: $curResult, data: ["; 
		$curRow = 1;
		foreach($sql_result->fetch_all() as $row) {
			$dateArr = explode("-",$row[0]);
			$chartsData .= "[Date.UTC(" . $dateArr[0] . ", " . $dateArr[1] . ", " . $dateArr[2] . "), " . $row[1] . "]";
			if ($curRow != $sql_result->num_rows) {
				$chartsData .= ",";
			}
			$curRow++;
		}
		$chartsData .= "]}";
		if ($curResult != count($sql_results)) {
			$chartsData .= ",";
		}
	}
	$curResult++;
}

$chartsData .= "]";


?>