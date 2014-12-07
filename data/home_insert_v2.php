<?php
// Load database connection info
include('db.conf.php');

// Connect to the SQL database
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$sql_query = "Select
			  crunchbase.rounds.company_market,
			  SUM(crunchbase.rounds.raised_amount_usd) as total,
			  MAX(crunchbase.rounds.funded_at) as last_funding
			From
			  crunchbase.rounds
			Group By
			  crunchbase.rounds.company_market
			Order By
			  Total Desc";

$sql_result = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);


$markets = [];
$timeDiff = [];
$curRow = 1;
$minVal = 0;
$maxVal = 0;
while($row = $sql_result->fetch_object()) {
	if ($maxVal == 0) {
		$maxVal = $row->total;
	}
	$markets[$row->company_market] = $row->total;
	if ($curRow == $sql_result->num_rows) {
		$minVal = $row->total;
	}


	$now = new DateTime();
	$lastFunding = new DateTime($row->last_funding);
	$dateDiff = $now->diff($lastFunding);
	$timeDiff[$row->company_market] = $dateDiff->format("%m");
}

$normalizedAmts = [];
foreach ($markets as $name => $total) {
	$normVal = round(($total - $minVal) / ($maxVal - $minVal), 10);
	if ($normVal < 0.0001)	$normVal = 0;
	$normalizedAmts[$name] = $normVal;
}

foreach ($normalizedAmts as $market => $normVal) {
	$sql_query = "INSERT INTO crunchbase.markets (crunchbase.markets.name, crunchbase.markets.normalized_amount, crunchbase.markets.time_since_last) VALUES (\"$market\", $normVal, $timeDiff[$market]);";
	// echo $sql_query;
	// $sql_query = "UPDATE crunchbase.markets SET crunchbase.markets.normalized_amount = $normVal WHERE crunchbase.markets.name = \"$market\";";
	$sql_result = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
}
exit();


foreach ($normalizedAmts as $market => $normVal) {
	$sql_query = "UPDATE crunchbase.markets SET crunchbase.markets.normalized_amount = $normVal WHERE crunchbase.markets.name = \"$market\";";
	$sql_result = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
}

exit();


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