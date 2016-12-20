app.controller("StatisticsController", function($q, $scope, $http, SpeciesService, VisitService) {

	//Variables
	$scope.statistics = [];
	var ukupno_posjeta = 0;
	//Functions
	$scope.getStatistics = function () {
		var _species = [];
		var _users = [];
		SpeciesService.getSpecies().then( function(species) {
			var promises = species.map(function(specimen) {
				return VisitService.visitCount(specimen.species_id).then(function(count) {
					ukupno_posjeta += count.count;
					$scope.statistics.push({
							name: specimen.name,
							visits : count.count,
							percentage : 0.0
						});
					});
			});
			$q.all(promises).then(function () {
				var tmp = $scope.statistics;
				for (var i = 0; i < tmp.length ; i++) {
					tmp[i].percentage = parseFloat(tmp[i].visits*100/ukupno_posjeta).toFixed(2);
				}
			});

		})
	}

	//Init
	$scope.getStatistics();
});