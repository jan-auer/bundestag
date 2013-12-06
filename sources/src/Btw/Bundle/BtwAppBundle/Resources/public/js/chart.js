/**
 * Creates a "Semi Circle Donut" chart at the specified position.
 *
 * @param {String} selector A jQuery selector for the target element.
 * @param {Array}  data     An array containing all data points for the chart.
 */
function createChart(selector, data) {

	// Build the chart
	$(selector).highcharts({
		chart : {
			backgroundColor     : 'transparent',
			plotBackgroundColor : null,
			plotBorderWidth     : null,
			plotShadow          : false
		},

		title : false,

		tooltip: {
			pointFormat: '{series.name}: <b>{point.y} ({point.percentage:.1f}%)</b>'
		},

		plotOptions : {
			pie: {
				dataLabels: {
					enabled: false
				},
				showInLegend: true,
				startAngle: -90,
				endAngle: 90,
				center: ['50%', '90%']
			}
		},

		series : [
			{
				type : 'pie',
				name : 'Sitze',
				innerSize: '75%',
				size : '180%',
				data : data
			}
		]
	});

}
