<?php
include("data/tops.php");
include("header.php");
?>
	<div id="top_cos" style="height: 400px"></div>
	<div id="top_invs" style="height: 400px"></div>
	<script language="javascript">
		$(document).ready(function () {
		    $('#top_cos').highcharts({
		    	chart: {
		    		type: 'column'
		    	},
		    	legend: {
		    		enabled: false
		    	},
		    	title: {
		    		text: "Top Companies in <?= $market; ?>" 
		    	},
		    	xAxis: {
		    		type: "category"
		    	},
		        series: [{
		        	name: "Total Raised",
		        	data: <?= $chartsData["Top Companies"]; ?>
		        }]
		    });

		    $('#top_invs').highcharts({
		    	chart: {
		    		type: 'column'
		    	},
		    	legend: {
		    		enabled: false
		    	},
		    	title: {
		    		text: "Top Investors in <?= $market; ?>" 
		    	},
		    	xAxis: {
		    		type: "category"
		    	},
		        series: [{
		        	name: "Total Invested",
		        	data: <?= $chartsData["Top Investors"]; ?> }]
		    });
<?php
	include("footer.php");
?>