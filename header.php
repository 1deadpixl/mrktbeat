<!DOCTYPE html>
<head>
	<title>MrktBeat</title>
	<link rel="stylesheet" href="styles/mrktbeat.css">
	<link rel="stylesheet" href="styles/token-input.css" type="text/css">
    <link rel="stylesheet" href="styles/token-input-mrktbeat.css" type="text/css">
    <link rel="stylesheet" href="styles/highcharts.css" type="text/css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="src/highcharts-4.0.1.js"></script>
    <script src="src/jquery.tokeninput.js"></script>
</head>
<body>
	<header>
		<div id="logo"></div>
		<div id="search_form">
			<form action="markets.php" method="POST">
	            <input type="text" id="demo-input" name="q" size="100"/>
	            <input type="submit" value="Submit" style="display:none;" />
	        </form>
	    </div>
		<div class="x-split-button">
		  <button class="x-button x-button-main">Hi, Allison!</button>
		  <button class="x-button x-button-drop">&#9660;</button>
		  <ul class="x-button-drop-menu">
		    <li>
		      <a href="#">Item - 1</a>
		    </li>
		    <li>
		      <a href="">Item - 2</a>
		    </li>
		    <li>
		      <a href="">Long Item - 3</a>
		    </li>
		  </ul>
		</div>
	</header>

