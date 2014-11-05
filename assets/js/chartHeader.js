(function($){
	'use strict';

	var chartHeader = function(el, options){
		var self = this;
		self.options = options;
		self.element = el;
		self.$element = $(el);

		// Create dictionary of elements, variables and paths
		self.elements = {};
		self.elements.$chartHeaderContainer = self.$element.find(self.options.chartHeaderContainer);
		self.elements.$chartHeaderButtonImport = self.$element.find(self.options.chartHeaderButtonImport);
		self.elements.$chartHeaderButtonImportLoading = self.$element.find(self.options.chartHeaderButtonImportLoading);
		self.elements.$chartHeaderButtonImportSuccess = self.$element.find(self.options.chartHeaderButtonImportSuccess);
		self.elements.$chartHeaderInputSearch = self.$element.find(self.options.chartHeaderInputSearch);
		self.paths = {};
		self.paths.ajaxImportData = self.options.baseUrl + self.options.ajaxPathImportData;
		self.paths.ajaxSearch = self.options.baseUrl + self.options.ajaxPathSearch;

		// On init
		// Buttons
		self.elements.$chartHeaderButtonImport.on('click', function(e){
			e.preventDefault();

			self.importData();
		});
		self.elements.$chartHeaderButtonImportSuccess.on('click', function(e){
			e.preventDefault();

			self.refreshPage();
		});

		// Autocomplete on search
		self.elements.$chartHeaderInputSearch.autocomplete({
			source: self.paths.ajaxSearch,
			minLength: 0,
			select: function( event, ui ){
				$('.chartFiltersContainer').chartFilters('clearFilters');
				$('.chartFiltersContainer').chartFilters('selectFilters', ui.item.type, ui.item.id);
				$('.chartFiltersContainer').chartFilters('submitFilters');
			}
		});
	};

	// Functionality
	/**
	 * showGraphs 		Displays graphs
	 */
	chartHeader.prototype.importData = function(){
		var self = this;

		$.ajax({
			dataType: "json",
			url: self.paths.ajaxImportData,
			beforeSend: function(){
				// Loading
				self.elements.$chartHeaderButtonImport.hide();
				self.elements.$chartHeaderButtonImportLoading.show();
			},
			success: function(response){
				// Complete
				self.elements.$chartHeaderButtonImportLoading.hide();
				self.elements.$chartHeaderButtonImportSuccess.show();
				window.setTimeout(self.refreshPage, 500);
			}
		});
	}

	/**
	 * refreshPage 		Reload currently viewed page
	 */
	chartHeader.prototype.refreshPage = function(){
		location.reload();
	}

	$.fn.chartHeader = function(action){
		var args, result, tmp;
		args = Array.prototype.slice.call(arguments, 1);

		this.each(function(){
			var $this, plugin, data, options;
			$this = $(this);
			plugin = $this.data('chartHeader');
			data = $this.data();
			options = $.extend(true, {}, $.fn.chartHeader.defaults, plugin instanceof  chartHeader && plugin.options, data, typeof action === 'object' && action);

			if( (plugin instanceof  chartHeader) === false || typeof action === 'object'){
				plugin = new chartHeader(this, options);
				$this.data('chartHeader', plugin);
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

	$.fn.chartHeader.defaults = {
		// Element selectors
		'chartHeaderContainer' : '.chartHeaderContainer',
		'chartHeaderButtonImport' : '.chartHeaderImport',
		'chartHeaderButtonImportLoading' : '.chartHeaderImportLoading',
		'chartHeaderButtonImportSuccess' : '.chartHeaderImportSuccess',
		'chartHeaderInputSearch' : '.chartHeaderSearch',
		// Ajax paths
		'ajaxPathImportData' : 'index.php/ajax/importData',
		'ajaxPathSearch' : 'index.php/ajax/search'
	};
}(jQuery));
$(document).ready(function(){
	'use strict';
	$('.chartHeaderContainer').chartHeader();
});