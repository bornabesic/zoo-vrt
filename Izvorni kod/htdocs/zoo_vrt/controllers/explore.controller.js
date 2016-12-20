app.controller("ExploreController", function($scope, $location, SpeciesService, MapService){

	var species = [];
	$scope.result = [];

	MapService.setDot(null, null)

	//Functions

	function createTable(arr, size) {
		var newArr = [];
			for (var i=0; i<arr.length; i+=size) {
				newArr.push(arr.slice(i, i+size));
			}
		return newArr;
	}

	function getSpecies(){
		var post_obj = SpeciesService.getSpecies();
		post_obj.then(function(result){
			species=result;
			$scope.result=result;
			$scope.table = createTable($scope.result, 3);	
			
			if(!$scope.$$phase) {
				$scope.$apply();
			}		
		})
		
	}

	$scope.filterSpecies = function(){
		$scope.result=[]
		for(var i=0; i<species.length; i++){
			if(species[i].name.toLowerCase().indexOf($scope.search_species.toLowerCase()) != -1)
				$scope.result.push(species[i])
		}
		$scope.table = createTable($scope.result, 3);	
	}

	//Init
	getSpecies();

})