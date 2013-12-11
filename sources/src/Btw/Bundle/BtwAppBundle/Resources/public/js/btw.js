!function (ng) {

	var Module = ng.module('btw', []);

	Module.controller('DetailsController', ['$scope', '$http', function ($scope, $http) {

		// Scope Exports

		$scope.loading = 0;
		$scope.state = 0;
		$scope.constituency = 0;

		// Events

		$scope.$watch('year', loadStates);
		$scope.$watch('state', loadConstituencies);
		$scope.$watch(watchSelection, loadChartData);

		// Methods

		function loadStates(year) {
			load('btw_app_ajax_states', { year : year }, function (states) {
				$scope.states = states;
			});
		}

		function loadConstituencies(state) {
			$scope.constituency = 0;
			if (!state) return;

			load('btw_app_ajax_constituencies', { 'stateId' : state }, function (constituencies) {
				$scope.constituencies = constituencies;
			});
		}

		function watchSelection() {
			return $scope.state + '|' + $scope.constituency;
		}

		function loadChartData() {
			var data = { year : $scope.year, stateId : $scope.state || 0, constituencyId : $scope.constituency || 0 };
			load('btw_app_ajax_results', data, function (data) {
				$scope.data = data;
			});
		}

		function load(path, data, success) {
			$scope.loading++;

			var url = Routing.generate(path, data);
			$http.get(url).success(function (response) {
				$scope.loading--;
				(success || ng.noop)(response);
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
