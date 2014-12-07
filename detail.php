<?php
include("data/detail.php");
?>
<html>
<head>
	<title>MrktBeat</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
</head>
<body>
	<div id="container" style="height: 800px"></div>
	<script language="javascript">
		$(document).ready(function () {
		    $('#container').highcharts({
		    	chart: {
		            type: 'pie'
		        },
		        title: {
		            text: "<?= $chartTitle; ?>"
		        },
		         plotOptions: {
		            pie: {
		                shadow: false,
		                center: ['50%', '50%']
		            }
		        },
		        tooltip: {
		            valueSuffix: '%'
		        },
		        dataLabels: {
	                formatter: function () {
	                    // display only if larger than 1
	                    return this.y > 1 ? '<b>' + this.point.value + ':</b> ' + this.y + '%'  : null;
	                }
	            },
		        series: [{
		        	name: "Percent of Total",
		            innerSize: '38%',
		        	data: <?= $chartsData; ?>
		        }]
		    });
		});
	</script>
</body>
</html>