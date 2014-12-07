<?php
// Load database connection info
include('db.conf.php');

// Connect to the SQL database
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}


$market = ucwords($_GET["q"]); //q = a market

$sql_queries = array(
	"Top Companies" =>
	"Select
	  crunchbase.rounds.company_name as 'name',
	  sum(crunchbase.rounds.raised_amount_usd) as 'y'
	From
	  crunchbase.rounds
	Where
	  crunchbase.rounds.company_market = \"$market\"
	Group By
	  crunchbase.rounds.company_name
	Order By
	  y Desc
	Limit
	  10",

		

	"Top Investors" =>
	"Select
	  crunchbase.investments.investor_name as 'name',
	  sum(crunchbase.investments.raised_amount_usd) as 'y'
	From
	  crunchbase.investments
	Where
	  crunchbase.investments.company_market = \"$market\"
	Group By
	  crunchbase.investments.investor_name
	Order By
	  y Desc
	Limit
	  10");


$sql_results = [];
foreach($sql_queries as $query_name => $sql_query) {
	//Query db and add it to the results array
	$sql_results[$query_name] = $mysqli->query($sql_query) or die($mysqli->error.__LINE__);
}

// Close the database connection
$mysqli->close();


// Parse and format the data
$chartsData = [];

$curResult = 1;
foreach($sql_results as $name => $sql_result) {
	$chartsData[$name] = "[";
	if($sql_result->num_rows > 0) {
		$curRow = 1;
		foreach($sql_result->fetch_all() as $row) {
			$chartsData[$name] .= "{ name: \"$row[0]\", y: $row[1] }";
			if ($curRow != $sql_result->num_rows) {
				$chartsData[$name] .= ",";
			}
			$curRow++;
		}
	}
	$chartsData[$name] .= "]";
	$curResult++;
}
?>