app.controller("AnimalsController", function($scope, $stateParams, AnimalsService){

	$scope.animals = [];
	var species_id = $stateParams["species_id"] //dohvati id iz URL-a

	//fnction
	function getAnimals(species_id){

		AnimalsService.getAnimals(species_id).then(function(result) {
			$scope.animals=result;
			$scope.$apply();
		})
	}
	


	//init
	getAnimals(species_id);


	
})