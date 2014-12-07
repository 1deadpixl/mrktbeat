var svg = d3.select("svg");

// var circle = svg.selectAll("circle").data([32, 57, 112, 293]);
// // circle.attr("cx", function() { return Math.random() * 720; });

// var circleEnter = circle.enter().append("circle");

// circle.attr("r", function(d) { return Math.sqrt(d); });

// circle.attr("cx", function(d, i) { return i * 100 + 30; });

// circleEnter.attr("cy", 60);
// circleEnter.attr("cx", function(d, i) { return i * 100 + 30; });
// circleEnter.attr("r", function(d) { return Math.sqrt(d); });

var max = 100 // pixels
// var amountFn = function(d) {return d.amount};
var circle = svg.selectAll("circle")
// data = {market: name, amount: integer, recent: value}

              .data([4, 25, 1000])
              .enter().append("circle")
              .attr("r", function(d) { return Math.sqrt(d); })
              // .attr("r", function(d) { return d.amount * max; })
              .attr("cy", 60).attr("cx", function(d, i) { return i * 100 + 30; });
              




// if sp
//   keyPressed().style("fill", function(d) { // <== Add these
//                   if (d.hotness <= 3) {return "red"} 
//                   else if (d.hotness > 3 && d.hotness <= 6) {return "violet"}
//                   else if (d.hotness > 6 && d.hotness <= 9) {return "mediumvioletred"}
//                   else if (d.hotness > 9 && d.hotness <= 12) {return "violet"}
//                   else if (d.hotness > 12 && d.hotness <= 18) {return "blueviolet"}
//                   else if (d.hotness > 18 {return "indigo"}
//                ;}))

// $(function() {
//   $(document).keyup(function(evt) {
//     if (evt.keyCode == 32) {
//       space = false;
//     }
//   }).keydown(function(evt) {
//     if (evt.keyCode == 32) && user inpu {
//       space = true;

//     }
//   });
// }

// // if user input = 