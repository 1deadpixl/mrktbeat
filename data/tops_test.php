<?php
// Load database connection info
include('db.conf.php');

// Connect to the SQL database
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}



$market = $_GET["q"]; //q = a market

$sql_queries = array(
	"Top Companies" =>
	"Select
	  crunchbase.investments.company_name,
	  crunchbase.investments.raised_amount_usd
	From
	  crunchbase.investments
	Where
	  crunchbase.investments.company_market = '" . $market . "'
	Group By
	  crunchbase.investments.company_name, crunchbase.investments.raised_amount_usd
	Order By
	  crunchbase.investments.raised_amount_usd Desc",

	"Top Investors" =>
	"Select
	  crunchbase.investments.investor_name,
	  crunchbase.investments.raised_amount_usd
	From
	  crunchbase.investments
	Where
	  crunchbase.investments.company_market = '" . $market . "'
	Group By
	  crunchbase.investments.raised_amount_usd, crunchbase.investments.company_name
	Order By
	  crunchbase.investments.raised_amount_usd Desc");


$sql_results = [];
foreach($sql_queries as $query_name => $sql_query) {
	//Query db and add it to the results array
	$sql_results[$query_name] = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
}

// Close the database connection
$mysqli->close();


// Parse and format the data

// foreach($sql_results as $name => $sql_result) {

// 	while($row = $sql_result->fetch_assoc()) {
		
// 	}
// }

// $chartsData = [];

$curResult = 1;
foreach($sql_results as $name => $sql_result) {
	$chartsData[$name] = "[";
	if($sql_result->num_rows > 0) {
		$curRow = 1;
		foreach($sql_result->fetch_all() as $row) {
			$chartsData[$name] .= "{name: \"" . $row[0] . "\", y: " . $row[1] . "}";
			if ($curRow != $sql_result->num_rows) {
				$chartsData[$name] .= ",";
			}
			$curRow++;
		}
	}
	$chartsData[$name] .= "]";
	$curResult++;
}

echo '<pre>'.print_r($chartsData,true).'</pre>';

?>