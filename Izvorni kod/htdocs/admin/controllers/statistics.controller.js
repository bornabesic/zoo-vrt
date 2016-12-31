app.controller("StatisticsController", function($q, $scope, $http, SpeciesService, VisitService) {

	//Variables
	$scope.statistics = [];
	var total_visit_count = 0;

	//Functions
	function getStatistics () {
		SpeciesService.getSpecies().then( function(species) {
			var promises = species.map(function(specimen) {
				return VisitService.visitCount(specimen.species_id).then(function(count) {
					total_visit_count += count.count;
					$scope.statistics.push({
						name: specimen.name,
						visits : count.count,
						percentage : 0.0
					});

					if(!$scope.$$phase) {
						$scope.$apply();
					}

				});
			});
			$q.all(promises).then(function () {
				var tmp = $scope.statistics;
				for (var i = 0; i < tmp.length ; i++) {
					var percentage;
					if(total_visit_count==0) percentage = 0
					else percentage = tmp[i].visits*100/total_visit_count

					tmp[i].percentage=parseFloat(percentage).toFixed(2)
				}

				// sortiraj vrste po broju posjeta
				tmp.sort(function(a,b){
					return b.visits-a.visits;
				})
			});

		})
	}

	//Init
	getStatistics();
});