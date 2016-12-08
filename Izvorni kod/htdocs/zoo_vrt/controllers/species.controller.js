app.controller("SpeciesController", function($scope, $stateParams, SpeciesService, MapService, HierarchyService, VisitService){
	
	//Vars
	var species_id = $stateParams["species_id"] //dohvati id iz URL-a
	$scope.species = SpeciesService.getSpeciesById(species_id)

	$scope.visited_initial=false
	$scope.visited=false

	//Functions
	function init(){
			MapService.dot.x=$scope.species.location_x
			MapService.dot.y=$scope.species.location_y

			HierarchyService.getSpeciesHierarchy($scope.species.species_id).then(function(result){
				$scope.hierarchy=result;
				$scope.$apply()
			})

			VisitService.checkVisit(localStorage["user_id"], $scope.species.species_id).then(function(result){
				$scope.visited=result.visited
				$scope.visited_initial=result.visited
				$scope.$apply()
			})
	}

	$scope.registerVisit = function(){
		if($scope.visited_initial==false){
			VisitService.registerVisit(localStorage["user_id"], $scope.species.species_id).then(function(result){

			})
		}
	}

	//Init
	if($scope.species==null){ // dohvati vrste iz baze
		SpeciesService.getSpecies().then(function(result){
			$scope.species = SpeciesService.getSpeciesById(species_id)

			init()
		})
	}
	else init()

	

})