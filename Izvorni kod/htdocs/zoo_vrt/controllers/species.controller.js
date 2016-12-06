app.controller("SpeciesController", function($scope, $stateParams, SpeciesService, MapService){
	
	//Vars
	var species_id = $stateParams["species_id"] //dohvati id iz URL-a
	$scope.species = SpeciesService.getSpeciesById(species_id)


	//Init
	if($scope.species==null){ // dohvati vrste iz baze
		SpeciesService.getSpecies().then(function(result){
			$scope.species = SpeciesService.getSpeciesById(species_id)
			MapService.dot.x=$scope.species.location_x
			MapService.dot.y=$scope.species.location_y
			$scope.$apply()
		})
	}
	else{
		MapService.dot.x=$scope.species.location_x
		MapService.dot.y=$scope.species.location_y
	}

})