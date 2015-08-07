$(document).ready(function() {

   	// Create our graph from the data table and specify a container to put the graph in
   	createGraph('#data-table', '.chart');

   	// Here be graphs
   	function createGraph(data, container) {
      	// Declare some common variables and container elements
      	var bars = [];
		var figureContainer = $('<div id="figure"></div>');
		var graphContainer = $('<div class="graph"></div>');
		var barContainer = $('<div class="bars"></div>');
		var data = $(data);
		var container = $(container);
		var chartData;
		var chartYMax;
		var columnGroups;

		// Timer variables
		var barTimer;
		var graphTimer;

	    // Create the table data object
	    var tableData = {
	    	// Get numerical data from table cells
		   	chartData: function() {
			    // Loop through column groups, adding bars as we go
				$.each(columnGroups, function(i) {
				   	// Create bar group container
				   	var barGroup = $('<div class="bar-group"></div>');
				   	// Add bars inside each column
				   	for (var j = 0, k = columnGroups[i].length; j < k; j++) {
				    	// Create bar object to store properties (label, height, code, etc.) and add it to array
				      	// Set the height later in displayGraph() to allow for left-to-right sequential display
				      	var barObj = {};
				      	barObj.label = this[j];
				      	barObj.height = Math.floor(barObj.label / chartYMax * 100) + '%';
				      	barObj.bar = $('<div class="bar fig' + j + '"><span>' + barObj.label + '</span></div>').appendTo(barGroup);
				      	bars.push(barObj);
				   	}
				   	// Add bar groups to graph
				   	barGroup.appendTo(barContainer);
				});
		   	},

		   // Get heading data from table caption
		   chartHeading: function() {
		      …
		   },
		   // Get legend data from table body
		   chartLegend: function() {
		      …
		   },
		   // Get highest value for y-axis scale
		   chartYMax: function() {
		      …
		   },

		   	// Get y-axis data from table cells
		   	yLegend: function() {
		   		// Add y-axis to graph
				var yLegend   = tableData.yLegend();
				var yAxisList   = $('<ul class="y-axis"></ul>');
				$.each(yLegend, function(i) {
				   var listItem = $('<li><span>' + this + '</span></li>')
				      .appendTo(yAxisList);
				});
				yAxisList.appendTo(graphContainer);
		   	},

		   // Get x-axis data from table header
		   xLegend: function() {
		      …
		   },
		   	
		   	// Sort data into groups based on number of columns
		   	columnGroups: function() {
		   		var columnGroups = [];
			  	// Get number of columns from first row of table body
			   	var columns = data.find('tbody tr:eq(0) td').length;
			   	for (var i = 0; i < columns; i++) {
			      	columnGroups[i] = [];
			      	data.find('tbody tr').each(function() {
			        	columnGroups[i].push($(this).find('td').eq(i).text());
			      	});
			   	}
			   	return columnGroups;
		   	}
	    }

	    // Useful variables to access table data
	      …

	    // Construct the graph
	      …

	    // Set the individual heights of bars
	    function displayGraph(bars) {
	       …
	    }

	    // Reset the graph's settings and prepare for display
	    function resetGraph() {
	       …
	       displayGraph(bars);
	    }

	    // Helper functions
	      …

	    // Finally, display the graph via reset function
	   	resetGraph();
   	}
});