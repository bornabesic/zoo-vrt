app.controller("AnimalsController", function($scope, AnimalsService, SpeciesService, HierarchyService){

	// Variables
	var class_name="Mammalia";
	var class_id=null;

	var classes=[];
	var orders=[];
	var families=[];

	var species=[];

	$scope.mammalSpecies=[];
	$scope.animals=[];

	$scope.new_animal = {
		name: null,
		species_id: null,
		age: null,
		sex: null,
		photo: null,
		birth_location: null,
		arrival_date: null,
		interesting_facts: null,
	}

	// Functions

	function isPhotoChosen (animal){
			if(!animal.photo){
			alert("Nije odabrana slika.");
			return false;
		}
		return true;
	}

	function isSexChosen (animal){
			if(!animal.sex){
			alert("Nije odabran spol.");
			return false;
		}
		return true;
	}

	function isDropdownChosen (animal){
			if(!animal.species_id){
			alert("Nije odabrana vrsta jedinke.");
			return false;
		}
		return true;
	}


	$scope.registerAnimal = function(){
		if(isDropdownChosen($scope.new_animal) && isSexChosen($scope.new_animal) && isPhotoChosen($scope.new_animal)){
			AnimalsService.registerAnimal($scope.new_animal).then(function(result){
				location.reload();
			})
		}
	}


	$scope.deleteAnimal = function(animal){
		AnimalsService.deleteAnimal(animal).then(function(result){
			location.reload();
		})
	}

	$scope.toggleEditAnimal = function(animal){
		var form = $("#form_"+animal.animal_id)
		if(!form.is(":visible")) {
			form.show()
		}
		else form.hide()
	}
	
	$scope.updateAnimal = function(animal){
		AnimalsService.updateAnimal(animal).then(function(result){
			location.reload();
		})
	}

	$scope.containsSpecies = function (mammalSpecie, animals){
		//console.log(mammalSpecie);
		//console.log(animals);
		for (var i = 0; i < animals.length; i++) {
			if(animals[i].species_id===mammalSpecie.species_id){
				return true;
			}
		}
		return false;
	}


	// Init
	// dohvati sve razrede
	HierarchyService.getClasses().then(function(result){
		classes=result;

		// nađi species id od Mammalia među dohvaćenim razredima
		for (var i = 0; i < classes.length; i++) {
			if(classes[i].name===class_name){
				class_id=classes[i].class_id;
				break;
			}
		}
		
		// dohvati sve redove na temelju razreda
		HierarchyService.getOrdersByParentClass(class_id).then(function(result){
			orders=result;

			// dohvati sve porodice na temelju reda
			var cats=0;
			for (var i = 0; i < orders.length; i++) {
				HierarchyService.getFamiliesByParentOrder(orders[i].order_id).then(function(result){
					families=families.concat(result);
					cats++;

					if(cats==orders.length){
						//dohvati sve vrste
						SpeciesService.getSpecies().then(function(result){
							species=result

							// filtriraj vrste tako da uzmeš samo sisavce
							var _mammalSpecies=[]
							for (var i = 0; i < species.length; i++) {
								for (var j = 0; j < families.length; j++) {
									if(families[j].family_id===species[i].family_id){
										_mammalSpecies.push(species[i]);
										break;
									}
								};
							};

							$scope.mammalSpecies=_mammalSpecies;

							// dohvati jedinke za uređivanje
							AnimalsService.getAnimals(null).then(function(result){
								$scope.animals=result;

								if(!$scope.$$phase)
									$scope.$apply();
							})
						})
					}

					
				})
			}

		})

	})

})