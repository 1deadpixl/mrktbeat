<?php
// Connect to the SQL database
$DB_HOST = 'localhost';
$DB_NAME = 'crunchbase';
$DB_USER = 'crunchbase';
$DB_PASS = 'DuyQTCyNdYXdb4s7';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}



$url_query = $_GET["q"]; //q = comma-seperated list of markets

// echo '<pre>'.print_r(parse_url($url_query),true).'</pre>';
$markets = explode(",",parse_url($url_query)['path']);

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
	echo "<pre>" . $sql_query . "</pre>";
	//Query db and add it to the results array
	$sql_results[$market] = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
}

// Close the database connection
$mysqli->close();

echo '<pre>'.print_r($sql_results,true).'</pre>';

$chartsData = "[";

$curResult = 1;
foreach($sql_results as $market => $sql_result) {
	if($sql_result->num_rows > 0) {
		$chartsData .= "{name: '" . $market . "', data: "; 
		$curRow = 1;
		foreach($sql_result->fetch_all() as $row) {
			$dateArr = explode("-",$row[0]);
			$chartsData .= "[Date.UTC(" . $dateArr[0] . ", " . $dateArr[1] . ", " . $dateArr[2] . "), " . $row[1] . "]";
			if ($curRow != $sql_result->num_rows) {
				$chartsData .= ",";
			}
			$curRow++;
		}
		$chartsData .= "}";
		if ($curResult != count($sql_results)) {
			$chartsData .= ",";
		}
	}
	$curResult++;
}

// if($sql_result->num_rows > 0) {
// 	while($row = $sql_result->fetch_assoc()) {
// 		echo '<pre>'.print_r($row,true).'</pre>';
// 	}
// } else {
// 	echo 'NO RESULTS';	
// }

// if($sql_result->num_rows > 0) {
// 	$curRow = 1;
// 	foreach($sql_result->fetch_all() as $row) {
// 		$dateArr = explode("-",$row[0]);
// 		$chartsData .= "[Date.UTC(" . $dateArr[0] . ", " . $dateArr[1] . ", " . $dateArr[2] . "), " . $row[1] . "]";
// 		if ($curRow != $sql_result->num_rows) {
// 			$chartsData .= ",";
// 		}
// 		$curRow++;
// 	}
// } else {
// 	echo "No results";
// }

$chartsData .= "]";

echo $chartsData;
?>