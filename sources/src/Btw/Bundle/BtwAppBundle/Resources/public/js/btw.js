!function (ng) {

	/** @const */ var STATES_PATH         = 'btw_app_ajax_states';
	/** @const */ var CONSTITUENCIES_PATH = 'btw_app_ajax_constituencies';
	/** @const */ var DETAILS_PATH        = 'btw_app_ajax_results';

	/** @const */ var ALL_STATES         = { id : 0, name : 'Alle' };
	/** @const */ var ALL_CONSTITUENCIES = { id : 0, name : 'Alle' };

	var Module = ng.module('btw', []);

	Module.controller('DetailsController', ['$scope', '$http', function ($scope, $http) {

		// Scope Exports

		$scope.loading = 0;
		$scope.state = 0;
		$scope.constituency = 0;

		// Events

		$scope.$watch('year', loadStates);
		$scope.$watch('state', loadConstituencies);
		$scope.$watch(watchSelection, loadDetails);

		// Methods

		function loadStates(year) {
			load(STATES_PATH, { year : year }, function (states) {
				states.unshift(ALL_STATES);
				$scope.states = states;
			});
		}

		function loadConstituencies(state) {
			$scope.constituency = 0;
			$scope.constituencies = [ ALL_CONSTITUENCIES ];
			if (!state) return;

			load(CONSTITUENCIES_PATH, { 'stateId' : state }, function (constituencies) {
				constituencies.unshift(ALL_CONSTITUENCIES);
				$scope.constituencies = constituencies;
			});
		}

		function watchSelection() {
			return $scope.state + '|' + $scope.constituency;
		}

		function loadDetails() {
			var data = { year : $scope.year, stateId : $scope.state || 0, constituencyId : $scope.constituency || 0 };
			load(DETAILS_PATH, data, function (data) {
				$scope.title = data.scope;
				$scope.chart = data.chart;
				$scope.location = data.location;
				$scope.parties = data.parties;
				$scope.members = data.members;
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
			scope : { config : '=btwChart' },

			link : function (scope, element, attrs) {
				var chart = createChart(element, scope.data);
				scope.$watch('config', function (config) {
					config = config || {};
					chart.series[0].setData(config.data, false);
					chart.series[0].update({ name : config.type }, false);
					chart.redraw(true);
				});
			}
		};
	});

	Module.directive('bsTooltip', function () {
		return {
			link : function (scope, element, attrs) {
				element.tooltip({ title : attrs.bsTooltip });
			}
		};
	});

}(angular);
