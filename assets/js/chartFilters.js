(function($){
	'use strict';

	var chartFilters = function(el, options){
		var self = this;
		self.options = options;
		self.element = el;
		self.$element = $(el);

		// Create dictionary of elements, variables and paths
		self.elements = {};
		self.elements.$filtersContainer = self.$element.find(self.options.filtersContainer);
		self.elements.$filtersRow = self.$element.find(self.options.filtersRow);
		self.elements.$filtersTab = self.$element.find(self.options.filtersTab);
		self.elements.$filtersButtonToggle = self.$element.find(self.options.filtersButtonToggle)
		self.elements.$filtersInputLandlordGroup = self.$element.find(self.options.filtersLandlordGroup);
		self.elements.$filtersInputLandlord = self.$element.find(self.options.filtersLandlord);
		self.elements.$filtersInputStartYear = self.$element.find(self.options.filtersStartYear);
		self.elements.$filtersInputEndYear = self.$element.find(self.options.filtersEndYear);
		self.elements.$filtersButtonSubmit = self.$element.find(self.options.filtersButtonSubmit);
		// Plugin variables
		self.filters = {};

		// On Init
		// Establish chosen multiselects
		self.elements.$filtersInputLandlordGroup.chosen({
			width: '100%'
		});
		self.elements.$filtersInputLandlord.chosen({
			width: '100%'
		});
		self.elements.$filtersInputStartYear.chosen({
			width: '100%',
			allow_single_deselect: true,
			disable_search_threshold: 10
		});
		self.elements.$filtersInputEndYear.chosen({
			width: '100%',
			allow_single_deselect: true,
			disable_search_threshold: 10
		});
		// Buttons
		self.elements.$filtersButtonToggle.on('click', function(){
			self.toggleFilters();
		});
		self.elements.$filtersButtonSubmit.on('click', function(){
			self.getFilters();
			self.submitFilters();
		});
		// Populate filters
		self.getFilters();


	};

	// Functionality
	/**
	 * redrawTable Show/Hide Filters
	 */
	chartFilters.prototype.toggleFilters = function(){
		var self = this;

		self.elements.$filtersRow.slideToggle();
	};

	/**
	 * getFilters 		Get and set filter values
	 */
	chartFilters.prototype.getFilters = function(){
		var self = this;

		var filters = {};
		filters.landlordGroup = self.elements.$filtersInputLandlordGroup.chosen().val();
		filters.landlord = self.elements.$filtersInputLandlord.chosen().val();
		filters.startYear = self.elements.$filtersInputStartYear.chosen().val();
		filters.endYear = self.elements.$filtersInputEndYear.chosen().val();
		if(filters.startYear === ''){
			filters.startYear = null;
		}
		if(filters.endYear === ''){
			filters.endYear = null;
		}
		self.filters = filters;
	}

	/**
	 * clearFilters  		Clear selected values from filter inputs
	 */
	chartFilters.prototype.clearFilters = function(){
		var self = this;

		self.elements.$filtersInputLandlordGroup.val('').trigger('chosen:updated');
		self.elements.$filtersInputLandlord.val('').trigger('chosen:updated');
		self.elements.$filtersInputStartYear.val('').trigger('chosen:updated');
		self.elements.$filtersInputEndYear.val('').trigger('chosen:updated');
	}

	/**
	 * selectFilters 		Select specific filters
	 * @param  string type 	Accepts 'landlord' or 'group'
	 * @param  numeric id 	ID of the entity to select
	 */
	chartFilters.prototype.selectFilters = function(type, id){
		var self = this;

		if(type === 'landlord'){
			self.elements.$filtersInputLandlord.val(id).trigger('chosen:updated');
		}else if(type === 'group'){
			self.elements.$filtersInputLandlordGroup.val(id).trigger('chosen:updated');
		}
		self.getFilters();
	}

	/**
	 * submitFilters 		Pass filters to createCharts plugin to draw charts
	 */
	chartFilters.prototype.submitFilters = function(){
		var self = this;

		$('.chartDrawContainer').chartDraw('createCharts', self.filters);
	}

	$.fn.chartFilters = function(action){
		var args, result, tmp;
		args = Array.prototype.slice.call(arguments, 1);

		this.each(function(){
			var $this, plugin, data, options;
			$this = $(this);
			plugin = $this.data('chartFilters');
			data = $this.data();
			options = $.extend(true, {}, $.fn.chartFilters.defaults, plugin instanceof  chartFilters && plugin.options, data, typeof action === 'object' && action);

			if( (plugin instanceof  chartFilters) === false || typeof action === 'object'){
				plugin = new chartFilters(this, options);
				$this.data('chartFilters', plugin);
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

	$.fn.chartFilters.defaults = {
		// Element selectors
		'filtersContainer' : '.chartFiltersContainer',
		'filtersRow' : '.chartFilters',
		'filtersTab' : '.chartFiltersTab',
		'filtersButtonToggle' : '.chartFiltersToggle',
		'filtersLandlordGroup' : '.chartFiltersLandlordGroup',
		'filtersLandlord' : '.chartFiltersLandlord',
		'filtersStartYear' : '.chartFiltersStartYear',
		'filtersEndYear' : '.chartFiltersEndYear',
		'filtersButtonSubmit' : '.chartFiltersSubmit'
	};
}(jQuery));
$(document).ready(function(){
	'use strict';
	$('.chartFiltersContainer').chartFilters();
});