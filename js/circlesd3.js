$(document).ready(function () {
  $.ajax({
    dataType: "json",
    url: "home_test.php",
    success: drawCircles
  });

});

    function drawCircles(JSONdata) {
      console.log("drawingCircles");
      var pxMax = 200; // pixels
      var width = 1920;
      var height = 800;
      var svg = d3.select("body").append("svg")
          .attr("width", width)
          .attr("height", height);
      // how will this get parsed?
      var data = JSONdata.slice();

      // var force = d3.layout.force()
      //     .nodes(data.markets)
      //     .size(width,height)
      //     .charge([-100])
      //     .start();

      var circle = svg.selectAll("circle")
        .data(data)
        .enter().append("circle")
        .attr("market", function(d){ return "#" + d.market })
        // .attr("r", function(d) { return Math.sqrt(d); })
        .attr("r", function(d) { return d.amount * pxMax; })
        .attr("cy", 60)
        .attr("cx", function(d, i) { return i * 100 + 30;})
        // .attr("class", function(d) { 
        //   var c = "";
        //   if (d.time_since_last <= 3) {c = "red"} 
        //   else if (d.time_since_last> 3 && d.time_since_last <= 6) {c = "violet"}
        //   else if (d.time_since_last > 6 && d.time_since_last <= 9) {c = "mediumvioletred"}
        //   else if (d.time_since_last > 9 && d.time_since_last <= 12) {c = "violet"}
        //   else if (d.time_since_last > 12 && d.time_since_last <= 18) {c = "blueviolet"}
        //   else if (d.time_since_last > 18) {c = "indigo"}
        //   return c;
        //  })
        .style("fill", function(d,i){
          return colors(i);
        })
        .attr("class", function(d) {return "default"; });
        // .call(force.drag);


    }
    
    function removeCircleColorClass(name) {
      var circleSelected = d3.select("#" + name);
          circleSelected.classed("default", false);
    }
    
    $(document).keyup(function(evt) {
      if (evt.keyCode == 32) {
        var userInput = $("#mrktbeat_search").text
        // select the circle with d.name that matches the userInput
        // then select its id
        $("#mrktbeat_search").tokenInput("data/search.php", {
          theme: "facebook"
        });
      }
    });  