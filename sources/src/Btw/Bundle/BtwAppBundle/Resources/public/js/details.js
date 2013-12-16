!function (ng, Module) {

	/** @const */ var DETAILS_PATH = 'btw_app_ajax_year_results';
	/** @const */ var CLOSEST_PATH = 'btw_app_closest';

	Module.controller('DetailsController', ['$scope', '$http', 'election', 'ALL_STATES', 'ALL_CONSTITUENCIES',
	function ($scope, $http, election, ALL_STATES, ALL_CONSTITUENCIES) {

		// Scope Exports

		$scope.loading = 0;
		$scope.state = ALL_STATES;
		$scope.constituency = ALL_CONSTITUENCIES;

		$scope.closestUrl = closestUrl;

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

		function closestUrl (party) {
			return Routing.generate(CLOSEST_PATH, { partyId : party });
		}

	}]);

	Module.filter('signed', ['$filter', function ($filter) {
		return function (num, digits) {
			return (num > 0 ? '+' : '') + $filter('number')(num, digits);
		}
	}]);

}(angular, BTW);
