<?php
include ("data/markets.php");
include ("header.php");
?>
	<div id="legend"></div>
	<div id="chart_container">
	</div>
	<script language="javascript">
		function drawLegend(event) {
			var maxWidth = 500;
			var maxValue = 0;
			for(var i = 0; i < this.series.length; i++) {
				if (this.series[i].points.length > maxValue) {
					maxValue = this.series[i].points.length;
				}
			}
			for(var i = 0; i < this.series.length; i++) {

				console.log(this.series[i]);

				var thisNormalizedPtCount = this.series[i].points.length / maxValue;
				var thisLabel = $('<div/>', { id: this.series[i].name })
									.appendTo($('#legend'))
									.append($('<div/>', { style: "background-color: "+this.series[i].color+"; width: " + maxWidth * thisNormalizedPtCount + "px", "data-series": i, id: 'bar'}));
				thisLabel
					.append($('<span/>', { html: this.series[i].name, id: "name" }))
					.append($('<span/>', { html: this.series[i].points.length + " investments", id: "records"}));
				
				thisLabel.children('#bar').bind('mouseover', function(event) {
					var legendItem = $(event.target);
					var chart = $('#chart_container').highcharts();
					var thisSeries = chart.series[legendItem.data("series")];
					legendItem.data('origZ',thisSeries.options.zIndex);
					thisSeries.update({zIndex: 99});
					legendItem.addClass('selected');
					legendItem.siblings('div').removeClass('selected');
				});

				thisLabel.children('#bar').bind('mouseout', function(event) {
					var legendItem = $(event.target);
					var chart = $('#chart_container').highcharts();
					var thisSeries = chart.series[legendItem.data("series")];
					legendItem.removeClass('selected');
					thisSeries.update({zIndex: legendItem.data('origZ')});
				});

				thisLabel.children('#bar').click(function(event) {
					window.location = "http://" + window.location.hostname + "/dataviz.info/" + "tops.php?q=" + $(event.target).parent().attr('id');
				});
			}
		}

		$(document).ready(function () {
		    $('#chart_container').highcharts({
		    	chart: {
		    		type: 'areaspline',
		    		zoomType: 'x',
		    		events: { load: drawLegend }
		    	},
		    	title: {
		    		text: null
		    	},
		    	legend: {
		    		enabled: false
		    	},
		        xAxis: {
		        	type: 'datetime',
		            minRange: 14 * 24 * 3600000 // fourteen days
		        },

		        series: <?= $chartsData; ?>
		    });
		    
<?php include("footer.php"); ?>