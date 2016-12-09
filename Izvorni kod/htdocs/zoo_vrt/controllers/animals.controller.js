app.controller("AnimalsController", function($scope, $stateParams, AnimalsService){

	//DIJALOG

	//
	$scope.animals = [];
	var species_id = $stateParams["species_id"] //dohvati id iz URL-a

	//functions
	function getAnimals(species_id){

		AnimalsService.getAnimals(species_id).then(function(result) {
			$scope.animals=result;
			$scope.$apply();
		})
	}

	function adoptAnimal(animal){
		alert("Adopted "+animal.name+"!")
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