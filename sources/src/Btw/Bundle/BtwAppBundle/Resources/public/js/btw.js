!function (ng) {

	var Module = ng.module('btw', []);

	Module.controller('DetailsController', ['$scope', '$http', function ($scope, $http) {

		// Scope Exports

		$scope.state = 0;
		$scope.constituency = 0;

		// Events

		$scope.$watch('year', loadStates);
		$scope.$watch('state', loadConstituencies);

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

			var url = Routing.generate('btw_app_ajax_constituencies', { 'stateId': state });
			$http.get(url).success(function (constituencies) {
				$scope.constituencies = constituencies;
			});
		}

	}]);

}(angular);
