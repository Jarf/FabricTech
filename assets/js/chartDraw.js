(function($){
	'use strict';

	var chartDraw = function(el, options){
		var self = this;
		self.options = options;
		self.element = el;
		self.$element = $(el);

		// Create dictionary of elements, variables and paths
		self.elements = {};
		self.elements.$chartDrawContainer = self.$element.find(self.options.drawContainer);
		self.elements.$chartDrawTemplate = self.$element.find(self.options.chartDrawTemplate);
		self.elements.$chartDrawGraphsContainer = self.$element.find(self.options.chartDrawGraphsContainer);
		self.paths = {};
		self.paths.ajaxGetData = self.options.baseUrl + self.options.ajaxPathGetData;
		self.chartData = {};
	};

	// Functionality
	/**
	 * drawChart  				Draw chart
	 * @param  object 	data 	Data gathered from filters
	 */
	chartDraw.prototype.createCharts = function(filters){
		var self = this;

		var data = {};
		if(typeof filters === 'object'){
			self.getData(filters);
		}else{
			return false;
		}
	};

	/**
	 * getData 					Get data for charts
	 * @param  object 			Filter data
	 */
	chartDraw.prototype.getData = function(filters){
		var self = this;

		if(typeof filters === 'object'){
			$.ajax({
				type: "POST",
				dataType: "json",
				data: filters,
				url: self.paths.ajaxGetData,
				beforeSend: function(){
					self.hideGraphs();
				},
				success: function(response){
					self.chartData = self.formatData(response);
					self.drawCharts();
					self.showGraphs();
				}
			});
		}
	}

	/**
	 * hideGraphs 		Hides the currently displayed graphs
	 */
	chartDraw.prototype.hideGraphs = function(){
		var self = this;

		// self.elements.$chartDrawGraphsContainer.slideUp();
	}

	/**
	 * showGraphs 		Displays graphs
	 */
	chartDraw.prototype.showGraphs = function(){
		var self = this;

		// self.elements.$chartDrawGraphsContainer.slideDown();
	}

	/**
	 * formatData 				Formats data into chartjs compatible format
	 * @param  object data 		Data returned from getData ajax call
	 * @return object 			Chart data indexed by group name and landlord name
	 */
	chartDraw.prototype.formatData = function(data){
		var self = this;

		var formattedData = {};
		if(typeof data === 'object'){
			var fillColor = 'rgba(220,220,220,0.5)';
			var strokeColor = 'rgba(220,220,220,0.8)';
			var highlightFill = 'rgba(220,220,220,0.75)';
			var highlightStroke = 'rgba(220,220,220,1)';
			$.each(data, function(index, data){
				var title = data.GroupName + ' > ' + data.Name;
				var labels = [];
				var datasetEst = [];
				var datasetAct = [];
				// Piece together labels and datasets
				$.each(data.data, function(year, quarter){
					$.each(quarter, function(quarternum, costs){
						labels.push(year + ' Q' + quarternum);
						datasetEst.push(costs.CostsEstimated);
						datasetAct.push(costs.CostsActual);
					});
				});
				// Create data for each chart using group and name as index
				formattedData[title] = {
					labels: labels,
					datasets: [
						{
							label: 'Estimated Costs',
							fillColor: fillColor,
							strokeColor: strokeColor,
							highlightFill: highlightFill,
							highlightStroke: highlightStroke,
							data: datasetEst
						},
						{
							label: 'Actual Costs',
							fillColor: fillColor,
							strokeColor: strokeColor,
							highlightFill: highlightFill,
							highlightStroke: highlightStroke,
							data: datasetAct
						}
					]
				};
			});
		}
		return formattedData;
	}

	chartDraw.prototype.drawCharts = function(){
		var self = this;

		self.elements.$chartDrawGraphsContainer.html('');
		var template = self.elements.$chartDrawTemplate;
		var i = 0;
		var html = '';
		// Produce HTML for graph containers (not a huge fan of JS DOM manipulation)
		$.each(self.chartData, function(title, data){
			var chartTitle = 'chart' + i;
			i++;
			self.chartData[title].chartTitle = chartTitle;
			var chart = template[0].outerHTML.replace('{chartName}', chartTitle).replace('{chartTitle}', title).replace('chartDrawTemplate ', '');
			html += chart;
		});
		// Populate 
		self.elements.$chartDrawGraphsContainer.html(html);
		// Create charts
		$.each(self.chartData, function(title, data){
			var $container = self.$element.find('.' + self.chartData[title].chartTitle);
			var ctx = $container[0].getContext('2d');
			var width = $container.parents('.panel-body').width();
			ctx.canvas.width = width;
			var chart = new Chart(ctx).Bar(data);
		});
	}

	$.fn.chartDraw = function(action){
		var args, result, tmp;
		args = Array.prototype.slice.call(arguments, 1);

		this.each(function(){
			var $this, plugin, data, options;
			$this = $(this);
			plugin = $this.data('chartDraw');
			data = $this.data();
			options = $.extend(true, {}, $.fn.chartDraw.defaults, plugin instanceof  chartDraw && plugin.options, data, typeof action === 'object' && action);

			if( (plugin instanceof  chartDraw) === false || typeof action === 'object'){
				plugin = new chartDraw(this, options);
				$this.data('chartDraw', plugin);
			}

			if(typeof action === 'string' && typeof plugin[action] === 'function'){
				tmp = plugin[action].apply(plugin, args);

				if(result === undefined){
					result = tmp;
				}
			}
		});

		return result === undefined ? this : result;
	};

	$.fn.chartDraw.defaults = {
		// Element selectors
		'chartDrawContainer' : '.chartDrawContainer',
		'chartDrawTemplate' : '.chartDrawTemplate',
		'chartDrawGraphsContainer' : '.chartDrawGraphsContainer',
		// Ajax paths
		'ajaxPathGetData' : 'index.php/ajax/getData'
	};
}(jQuery));
$(document).ready(function(){
	'use strict';
	$('.chartDrawContainer').chartDraw();
});