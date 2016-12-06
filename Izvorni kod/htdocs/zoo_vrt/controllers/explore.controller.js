app.controller("ExploreController", function($scope, SpeciesService, MapService){

	var species = [];
	$scope.result = [];

	MapService.dot.x=null
	MapService.dot.y=null

	//Functions
	function getSpecies(){
		var post_obj = SpeciesService.getSpecies();
		post_obj.then(function(result){
			species=result;
			console.log(result)
			$scope.result=result;			
			$scope.$apply();
		})
	}

	$scope.filterSpecies = function(){
		$scope.result=[]
		for(var i=0; i<species.length; i++){
			if(species[i].name.indexOf($scope.search_species) !== -1)
				$scope.result.push(species[i])
		}
	}

	//Init
	getSpecies();

})