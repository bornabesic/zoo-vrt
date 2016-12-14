app.controller("SpeciesController", function($scope, $stateParams, SpeciesService, MapService, HierarchyService, VisitService){
	
	//Vars
	$scope.visited_initial=false
	$scope.visited=false
	var species_id = $stateParams["species_id"] //dohvati id iz URL-a
	SpeciesService.getSpeciesById(species_id).then(function(result){
		$scope.species = result

		HierarchyService.getSpeciesHierarchy($scope.species.species_id).then(function(result){
			$scope.hierarchy=result;
			$scope.$apply()
		})

		VisitService.checkVisit(localStorage["user_id"], $scope.species.species_id).then(function(result){
			$scope.visited=result.visited
			$scope.visited_initial=result.visited
			$scope.$apply()
		})

		MapService.setDot($scope.species.location_x, $scope.species.location_y)
		/*MapService.dot.x=
		MapService.dot.y=*/
	})

	//Functions
	$scope.registerVisit = function(){
		if($scope.visited_initial==false){
			$scope.visited_initial=true
			VisitService.registerVisit(localStorage["user_id"], $scope.species.species_id).then(function(result){

			})
		}
	}
	

})