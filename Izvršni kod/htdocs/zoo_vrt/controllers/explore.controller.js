app.controller("ExploreController", function($scope, $location, SpeciesService, VisitService, MapService){

	MapService.setDot(null, null)

	// Variables

	$scope.showTopButton=false;

	var species = [];
	$scope.result = [];

	var recommendation=[]
	var number_of_recommendations=4;

	var box =  document.getElementsByClassName("content")[0];
	var searchBar = document.getElementById("search-bar");

	//Functions

	function createTable(arr, size) {
		if(size<=0) return arr;

		var newArr = [];
			for (var i=0; i<arr.length; i+=size) {
				newArr.push(arr.slice(i, i+size));
			}
		return newArr;
	}

	function containsSpecies(_species, _specie){
		for(var i=0; i<_species.length; i++){
			if(_species[i].species_id==_specie.species_id) return true;
		}
		return false;
	}

	function recommendSpecies(){
		var recursion_limit=100;
		recommendation=[]

		recommendSpecie(number_of_recommendations, recursion_limit);
	}

	/*  
		Rekurzija za preporuku
		- i je broj preostalih vrsta za preporuku,
		- drugi broj limitira rekurziju na najviše limit poziva (ukoliko se dogodi da ne može nać dovoljno vrsti)
	*/
	function recommendSpecie(i, limit){
		if(i<=0 || limit<=0){

			if(recommendation.length<=0){ // ako nemreš nać vrste koje korisnik nije posjetio, napuni nasumično
				var to_go=number_of_recommendations;
				var loopLimit=100;

				while(to_go>0 && loopLimit>0){
					var index = Math.floor(Math.random()*species.length);
					if(!containsSpecies(recommendation, species[index])){
						recommendation.push(species[index])
						to_go--;
					}
					loopLimit--;
				}

				$scope.recommendTable=createTable(recommendation, number_of_recommendations-to_go)
			}
			else{
				$scope.recommendTable=createTable(recommendation, number_of_recommendations-i)
			}

			if(!$scope.$$phase) {
				$scope.$apply();
			}
			
			return;
		}

		var specie=null;
		var loopLimit=100;
		while(loopLimit-->0){
			var index = Math.floor(Math.random()*species.length);
			var specie = species[index];
			if(!containsSpecies(recommendation, specie)) break;
		}
		if(containsSpecies(recommendation, specie)) return;

		if(specie!=null){

			VisitService.checkVisit(localStorage["user_id"], specie.species_id).then(function(result){
				if(!result.visited){
					recommendation.push(specie)
					recommendSpecie(i-1, limit-1)
				}
				else{
					recommendSpecie(i, limit-1)
				}
			})
		}


	}

	$scope.filterSpecies = function(){
		$scope.result=[]
		for(var i=0; i<species.length; i++){
			if(species[i].name.toLowerCase().indexOf($scope.search_species.toLowerCase()) != -1)
				$scope.result.push(species[i])
		}
		$scope.table = createTable($scope.result, 3);	
	}

	$scope.shouldShowRecommendation = function(){
		return recommendation.length>0 && !$scope.search_species;
	}

	box.onscroll = function(){
		$scope.showTopButton = box.scrollTop>0;

		if(!$scope.$$phase) {
			$scope.$apply();
		}
	}

	$scope.scrollResultsToTop = function(){
		box.scrollTop = 0;
		searchBar.focus()
	}

	//Init
	SpeciesService.getSpecies().then(function(result){
		species=result;
		$scope.result=result;
		$scope.table = createTable($scope.result, 3);	
		
		if(!$scope.$$phase) {
			$scope.$apply();
		}		

		recommendSpecies()
	})

})