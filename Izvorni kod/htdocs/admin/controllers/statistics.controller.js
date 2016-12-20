app.controller("StatisticsController", function($scope, $http, UserService, SpeciesService, VisitService){

	//Variables
	$scope.statistics = [];

	//Functions
	$scope.getStatistics = function () {
		var species = [];
		SpeciesService.getSpecies().then(function(result) {
			species=result;
			$scope.$apply();
		});
		var users = [];
		UserService.getUsers().then(function(result) {
			users = result;
			$scope.$apply();
			for (var i = 0; i < species.length; i++) {
				var spec = {
					name: species[i].name,
					visits : 0
				};
				for(var j = 0; j < users.length; j++) {
					VisitService.checkVisit(users[j].user_id, species[i].species_id)
						.then(function(result) {
							if(result.visited) {
								spec.visits++;
							}
							$scope.$apply();
						});
				}
				$scope.statistics.push(spec);
			}
		});
	}

	//Init
	$scope.getStatistics(); 
});