$(document).ready(function () {
  $.ajax({
    dataType: "json",
    url: "http://10.0.16.136/dataviz.info/data/home.php",
    success: drawCircles
  });

});

    function drawCircles(JSONdata) {
      var pxMax = 200; // pixels
      var svg = d3.select("svg");
      var circle = svg.selectAll("circle");
      var w = 1920;
      var h = 1080;
      var padding = 30;
      var nodes = d3.range(JSONdata.length).map(function() { return {radius: Math.random() * 12 + 30};});

      
      var root = nodes[0];
       root.radius = 0;
       root.fixed = true;
      
      var node = svg.selectAll('.node')
       .data(JSONdata)
       .enter().append("g")
       .attr('class', 'node')
       // .call(force.drag);

    
        node.append("circle")
        .attr("market", function(d){ return d.market })
        // .attr("r", function(d) { return Math.sqrt(d); })
        .attr("r", function(d) { return d.amount * pxMax; })
        .attr("cy", function(d, i) { return (h / (i * Math.random())) })
        .attr("cx", function(d, i) { return (w / (i * Math.random())) })
        .attr("class", function(d) { 
          var c = "";
          if (d.time_since_last <= 3) {c = "red"} 
          else if (d.time_since_last> 3 && d.time_since_last <= 6) {c = "violet"}
          else if (d.time_since_last > 6 && d.time_since_last <= 9) {c = "mediumvioletred"}
          else if (d.time_since_last > 9 && d.time_since_last <= 12) {c = "violet"}
          else if (d.time_since_last > 12 && d.time_since_last <= 18) {c = "blueviolet"}
          else if (d.time_since_last > 18) {c = "indigo"}
          return c;
         })
        .attr("class", function(d) {return "default"; });
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

  //   force.on("tick", function(e) {
  //     var q = d3.geom.quadtree(nodes),
  //     i = 0,
  //     n = nodes.length;
 
  //     while (++i < n) {
  //       q.visit(collide(nodes[i]));
  //   }
 
  // // svg.selectAll("circle")
  // //     .attr("cx", function(d) { return d.x; })
  // //     .attr("cy", function(d) { return d.y; });

  // function collide(node) {
  //   var r = node.radius + 16,
  //       nx1 = node.x - r,
  //       nx2 = node.x + r,
  //       ny1 = node.y - r,
  //       ny2 = node.y + r;
  //   return function(quad, x1, y1, x2, y2) {
  //     if (quad.point && (quad.point !== node)) {
  //       var x = node.x - quad.point.x,
  //           y = node.y - quad.point.y,
  //           l = Math.sqrt(x * x + y * y),
  //           r = node.radius + quad.point.radius;
  //       if (l < r) {
  //         l = (l - r) / l * .5;
  //         node.x -= x *= l;
  //         node.y -= y *= l;
  //         quad.point.x += x;
  //         quad.point.y += y;
  //       }
  //     }
  //     return x1 > nx2
  //         || x2 < nx1
  //         || y1 > ny2
  //         || y2 < ny1;
  //   };
  // }  