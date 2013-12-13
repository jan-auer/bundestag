!function (ng, Module) {

	/** @const */ var DETAILS_PATH = 'btw_app_ajax_year_results';

	Module.controller('DetailsController', ['$scope', '$http', 'election', 'ALL_STATES', 'ALL_CONSTITUENCIES',
	function ($scope, $http, election, ALL_STATES, ALL_CONSTITUENCIES) {

		// Scope Exports

		$scope.loading = 0;
		$scope.state = ALL_STATES;
		$scope.constituency = ALL_CONSTITUENCIES;

		// Events

		$scope.$watch('year', loadData);
		$scope.$watch('state', updateState);
		$scope.$watch(watchSelection, updateDetails);

		// Methods

		function loadData(year) {
			load(DETAILS_PATH, { year : year }, function (data) {
				election.load(data);
				$scope.states = election.getStates();
			});
		}

		function updateState(state) {
			$scope.constituencies = election.getConstituencies(state);
			$scope.constituency = ALL_CONSTITUENCIES;
		}

		function watchSelection() {
			return $scope.state.id + '|' + $scope.constituency.id;
		}

		function updateDetails() {
			if (!$scope.state || !$scope.constituency) return;
			$scope.context = $scope.state.id ? $scope.constituency.id ? $scope.constituency : $scope.state : election.getCountry();
			$scope.limit = false;
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

	Module.directive('bsTooltip', function () {
		return {
			link : function (scope, element, attrs) {
				element.tooltip({ title : attrs.bsTooltip });
			}
		};
	});

}(angular, BTW);
