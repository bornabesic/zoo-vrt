app.controller("AnimalsController", function($scope, $state, $stateParams, AnimalsService, AdoptService, MapService){

	MapService.setDot(null, null)

	//
	$scope.animals = [];
	var species_id = $stateParams["species_id"] //dohvati id iz URL-a

	$scope.noAnimals=false;

	//functions
	function getAnimals(species_id){

		AnimalsService.getAnimals(species_id).then(function(result) {
			$scope.animals=result;

			AdoptService.getAdopted(localStorage['user_id']).then(function(result) {
				var adopted = result
				var animals = $scope.animals
				for(var i=0; i<animals.length; i++){
					animals[i].adopted=false
					for(var j=0; j<adopted.length; j++){
						if(animals[i].animal_id==adopted[j].animal_id){
							animals[i].adopted=true
							console.log(animals[i].name + " je posvojen")
							break;
						}
					}
				}

				if($scope.animals.length<=0){
					$scope.noAnimals=true;
					console.log("noAnimals")
				}

				if(!$scope.$$phase) {
					$scope.$apply();
				}
			})
		})
	}

	function adoptAnimal(animal){
		AdoptService.adopt(localStorage["user_id"], animal.animal_id, localStorage["email"], localStorage["first_last_name"], localStorage["city"]).then(function(){
			animal.adopted=true

			if(!$scope.$$phase) {
					$scope.$apply();
				}
		})
	}

	$scope.toggleFacts = function(animal){
		var facts = $("#facts_"+animal.animal_id);
		if(!facts.is(":visible")) facts.show()
		else facts.hide()
	}
	
	$scope.adoptionDialog = function(animal){
		BootstrapDialog.show({
		title: animal.name,
	    message: 'Odabirom opcije \'Potvrdi\' postajete posvojitelj ove jedinke. Informacije o uplati će biti dostavljene na Vašu adresu e-pošte.',
	    buttons: [{
	   		label: 'Potvrdi',
	        action: function(dialog) {
	        	adoptAnimal(animal)
	        	dialog.close()
	        }
	    },
	    {
	   		label: 'Zatvori',
	        action: function(dialog) {
	        	dialog.close()
	        }
	    }]
		});
	}

	//init
	getAnimals(species_id);
})