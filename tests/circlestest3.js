$(document).ready(function () {
  $.ajax({
    dataType: "json",
    url: "home_test.php",
    success: getData
  });

});
// The largest node for each cluster.

var width = 960,
      height = 500,
      padding = 1.5, // separation between same-color circles
      clusterPadding = 6, // separation between different-color circles
      maxRadius = 12;
      pxMax = 100;

var clusters = new Array(m);

var svg = d3.select("body").append("svg")
      .attr("width", width)
      .attr("height", height);
  
  var nodes = d3.range(n).map(function() {
    var i = Math.floor(Math.random() * m),
        r = Math.sqrt((i + 1) / m * -Math.log(Math.random())) * maxRadius,
        d = {cluster: i, radius: r};
    if (!clusters[i] || (r > clusters[i].radius)) clusters[i] = d;
  return d;
  });

  var force = d3.layout.force()
      .nodes(nodes)
      .size([width, height])
      .gravity(.02)
      .charge(0)
      .on("tick", tick)
      .start();
  
  var m = 10; // number of distinct clusters
  var n;
  var circle = svg.selectAll("circle");

function getData(JSONdata) {
  n = JSONdata.length;

  
  var color = d3.scale.category10()
      .domain(d3.range(m));
      circle 
      // .data(JSONdata)
      .data(nodes)
      .enter().append("circle")
      .attr("r", function(d) { return d.amount * pxMax; })
      .style("fill", function(d) { return color(d.cluster); })
      .call(force.drag)
      .attr("cx", function(d, i) { return width / (i + padding)})
      .attr("cy", function(d, i) { return height / (i + padding)})
    
  }

  function tick(e) {
    circle
    .each(cluster(10 * e.alpha * e.alpha))
    .each(collide(.5))
    .attr("cx", function(d) { return d.x; })
    .attr("cy", function(d) { return d.y; });
  }

  function removeCircleColorClass(name) {
    var circleSelected = d3.select("#" + name);
      circleSelected.classed("default", false);
  }
  // Move d to be adjacent to the cluster node.
  function cluster(alpha) {
    return function(d) {
      var cluster = clusters[d.cluster];
      if (cluster === d) return;
      var x = d.x - cluster.x,
          y = d.y - cluster.y,
          l = Math.sqrt(x * x + y * y),
          r = d.radius + cluster.radius;
      if (l != r) {
        l = (l - r) / l * alpha;
        d.x -= x *= l;
        d.y -= y *= l;
        cluster.cx += x;
        cluster.cy += y;
      }
    };
  }
  // Resolves collisions between d and all other circles.
  function collide(alpha) {
    var quadtree = d3.geom.quadtree(nodes);
    return function(d) {
      var r = d.radius + maxRadius + Math.max(padding, clusterPadding),
          nx1 = d.x - r,
          nx2 = d.x + r,
          ny1 = d.y - r,
          ny2 = d.y + r;
      quadtree.visit(function(quad, x1, y1, x2, y2) {
        if (quad.point && (quad.point !== d)) {
          var x = d.x - quad.point.x,
              y = d.y - quad.point.y,
              l = Math.sqrt(x * x + y * y),
              r = d.radius + quad.point.radius + (d.cluster === quad.point.cluster ? padding : clusterPadding);
          if (l < r) {
            l = (l - r) / l * alpha;
            d.x -= x *= l;
            d.y -= y *= l;
            quad.point.x += x;
            quad.point.y += y;
          }
        }
        return x1 > nx2 || x2 < nx1 || y1 > ny2 || y2 < ny1;
      });
    };
  }

    
 

// $(document).keyup(function(evt) {
  //   if (evt.keyCode == 32) {
  //     var userInput = $("#mrktbeat_search").text
  //     // select the circle with d.name that matches the userInput
  //     // then select its id
  //     $("#mrktbeat_search").tokenInput("data/search.php", {
  //       theme: "facebook"
  //     });
  //   }
  // });
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