<?php
// Load database connection info
include('db.conf.php');

// Connect to the SQL database
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql_queries = array(
	"amount" =>
		"Select Distinct
		  crunchbase.investments.company_market,
		  (crunchbase.investments.raised_amount_usd - (Select MIN(crunchbase.investments.raised_amount_usd)
		From
		  crunchbase.investments
		Where
		  crunchbase.investments.company_market != \"\" And
		  crunchbase.investments.raised_amount_usd > 0)) / ((Select MAX(crunchbase.investments.raised_amount_usd) From
		  crunchbase.investments
		Where
		  crunchbase.investments.company_market != \"\" And
		  crunchbase.investments.raised_amount_usd > 0)  - (Select MIN(crunchbase.investments.raised_amount_usd) From
		  crunchbase.investments
		Where
		  crunchbase.investments.company_market != \"\" And
		  crunchbase.investments.raised_amount_usd > 0 )) as \"amount\"
		From
		  crunchbase.investments
		Where
		  crunchbase.investments.company_market != \"\" And
		  crunchbase.investments.raised_amount_usd > 0
		Order By
		  amount Desc",

	"time_since_last" =>
		"Select Distinct
		  crunchbase.investments.company_market,
		  crunchbase.investments.funded_at as \"time_since_last\"
		From
		  crunchbase.investments
		Where
		  crunchbase.investments.company_market != \"\" And
		  crunchbase.investments.raised_amount_usd > 0
		Group By
		  crunchbase.investments.company_market, crunchbase.investments.funded_at
		Order By
		  crunchbase.investments.funded_at Desc"
);


$sql_results = [];
foreach($sql_queries as $query_name => $sql_query) {
	//Query db and add it to the results array
	$sql_results[$query_name] = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
}



$markets = [];
foreach($sql_results as $name => $sql_result) {
	while($row = $sql_result->fetch_assoc()) {
		if (!isset($markets[$row['company_market']][$name])) {
			$markets[$row['company_market']][$name] = $row[$name];
		}
	}
}


$returnString = "[";
$curRow = 1;
foreach($markets as $name => $values) {
	$now = new DateTime();
	$lastFunding = new DateTime($values['time_since_last']);
	$dateDiff = $now->diff($lastFunding);
	$values['time_since_last'] = $dateDiff->format("%m");

	$returnString .= "{\"market_id\": \"$curRow\", \"market\": \"$name\", ";
	$curVal = 1;
	foreach ($values as $name => $value) {
		$returnString .= "\"$name\": \"$value\"";
		if ($curVal != count($values)) {
			$returnString .= ", ";
		}
		$curVal++;
	}

	$returnString .= "}";
	if ($curRow != count($markets)) {
		$returnString .= ",";
	}
	$curRow++;
}
$returnString .= "]";

$dataset = json_decode($returnString);

// echo '<pre>'.print_r($dataset,true).'</pre>';

foreach ($dataset as $row) {
	$sql_query = "INSERT INTO crunchbase.markets (crunchbase.markets.name, crunchbase.markets.normalized_amount, crunchbase.markets.time_since_last) VALUES (";
	$sql_query .= "\"" . $row->market . "\", ";
	$sql_query .= $row->amount . ", ";
	$sql_query .= $row->time_since_last . ");";
	
	$result = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
}

// Close the database connection
$mysqli->close();

// echo $returnString;
?>