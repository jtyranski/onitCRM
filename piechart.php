<!doctype html>
<html lang="en">
<head>
<title>Pie Chart</title>
<meta charset="utf-8">
<script src="piechart.js"></script>
<!--[if IE]><script src="excanvas.js"></script><![endif]-->
<style>
</style>
<script>
function createPieChart(prospects,candidates,clients) {
	// convert variables from % to degrees
	prospects_deg = prospects * 360;
	candidates_deg = candidates * 360;
	clients_deg = clients * 360;

	// create a PieChart.
	var pieChart = new PieChart( "piechart", 
		{
			includeLabels: true, 
			data: [prospects_deg, candidates_deg, clients_deg],
			labels: [prospects*100 + "%", candidates*100 + "%", clients*100 + "%"],
			colors: [
            	["#ff0000", "#ff0000"],  // red (prospects)
            	["#ffff00", "#ffff00"],  // yellow (candidates)
            	["#07ff02", "#07ff02"]   // green (clients)
			]
		}
	);

	//
	// nothing appears until you call draw().
	//
	pieChart.draw();

/*
 * If you want to draw the labels separately, you can set
 * includeLabels to false, and call drawLabel() for each
 * pie chart segment.
 *
	for (var i = 0; i < pieChart.labels.length; i++) {
		pieChart.drawLabel(i);
	}
 */

/*
 * If you want to select a segment to highight it, you can
 * call select() for a given segment. Here's a little snippet
 * that animates selecting each segment.
 *
	var segment = 0;
	function nextSegment() {
		pieChart.select(segment);
		segment++;
		if (segment < pieChart.data.length) {
			setTimeout(nextSegment, 1000);
		}
	}
	setTimeout(nextSegment, 1000);
 */

}
</script>
<?php
// Total should add to 100%
$clients = 10 / 100;
$candidates = 25 / 100;
$prospects = 1 - $clients - $candidates;  // prospects are what is left
?>
</head>
<body onload="createPieChart(<?=$prospects?>,<?=$candidates?>,<?=$clients?>)">

<canvas id="piechart" width="200" height="200">
</canvas>

</body>
</html>
