!function (ng, Module) {

	window.createChart = createChart;

	/**
	 * Creates a "Semi Circle Donut" chart at the specified position.
	 *
	 * @param {String} selector A jQuery selector for the target element.
	 * @param {Array}  data     An array containing all data points for the chart.
	 *
	 * @return {Chart} The Highcharts configuration object.
	 */
	function createChart(selector, data) {

		// Build the chart
		return $(selector).highcharts({
			chart : {
				backgroundColor     : 'transparent',
				plotBackgroundColor : null,
				plotBorderWidth     : null,
				plotShadow          : false
			},

			title : false,

			tooltip : {
				followPointer : false,
				headerFormat  : '',
				pointFormat   : '<b>{point.label}</b><br/>{point.desc} ({point.percentage:.1f}%)'
			},

			plotOptions : {
				pie : {
					dataLabels   : {
						enabled : false
					},
					showInLegend : true,
					startAngle   : -90,
					endAngle     : 90,
					center       : ['50%', '90%']
				}
			},

			series : [
				{
					type      : 'pie',
					innerSize : '75%',
					size      : '180%',
					data      : data
				}
			]
		}).highcharts();

	}

	function convertChartData(data) {
		if (!data) return [];
		return $.map(data, function (d) {
			return {
				name  : d.party.abbr,
				color : d.party.color,
				seats : d.seats,
				y     : d.votes,
				label : d.party.name,
				desc  : d.seats ? d.seats + ' Sitze' : ''
			}
		});
	}

	Module.directive('btwChart', function () {
		return {
			scope : { config : '=btwChart' },

			link : function (scope, element, attrs) {
				var chart = createChart(element, scope.data);
				scope.$watch('config', function (data) {
					chart.series[0].setData(convertChartData(data), false);
					chart.redraw(true);
				});
			}
		};
	});

}(angular, BTW);
