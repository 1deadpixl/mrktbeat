<? include 'header.php'; ?>

<body>
<!-- query sql database for all markets -->
<!-- bind data to svg in d3js file  -->
  <!-- <svg width="1920" height="1080">
  </svg>
 -->
  <div>
    <form action="data/markets.php" method="get">
      <input type="text" id="mrktbeat_search" name="q"/>
      <input type="button" value="Feel the Beat" />
    </form>
  </div>
<!-- <script src="js/circlesd3.js"></script> -->
<script src="js/circlestest3.js"></script>
</body>
<? include 'footer.php'; ?>