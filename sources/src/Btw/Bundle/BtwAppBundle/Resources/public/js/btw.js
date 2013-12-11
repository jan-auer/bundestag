!function (ng) {

	var Module = ng.module('btw', []);

	Module.controller('DetailsController', ['$scope', '$http', function ($scope, $http) {

		// Scope Exports

		$scope.state = 0;
		$scope.constituency = 0;

		// Events

		$scope.$watch('year', loadStates);
		$scope.$watch('state', loadConstituencies);
		$scope.$watch(watchSelection, loadChartData);

		// Methods

		function loadStates(year) {
			var url = Routing.generate('btw_app_ajax_states', { year : year });
			$http.get(url).success(function (states) {
				$scope.states = states;
			});
		}

		function loadConstituencies(state) {
			$scope.constituency = 0;
			if (!state) return;

			var url = Routing.generate('btw_app_ajax_constituencies', { 'stateId' : state });
			$http.get(url).success(function (constituencies) {
				$scope.constituencies = constituencies;
			});
		}

		function watchSelection() {
			return $scope.state + '|' + $scope.constituency;
		}

		function loadChartData() {
			var url = Routing.generate('btw_app_ajax_results', { year : $scope.year, stateId : $scope.state || 0, constituencyId : $scope.constituency || 0 });
			$http.get(url).success(function (data) {
				$scope.data = data;
			});
		}

	}]);

	Module.directive('btwChart', function () {
		return {
			scope : { data : '=btwChart' },

			link : function (scope, element, attrs) {
				var chart = createChart(element, scope.data);

				scope.$watch('data', function (data) {
					chart.series[0].setData(data);
				});
			}
		};
	});

}(angular);
