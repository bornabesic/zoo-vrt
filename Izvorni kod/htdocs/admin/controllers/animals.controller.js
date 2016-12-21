app.controller("AnimalsController", function($scope, AnimalsService, SpeciesService, HierarchyService){

	//Variables
	$scope.animals=[];
	$scope.new_animal = {
		name: null,
		species_id: null,
		age: null,
		photo_path: null,
		sex: null,
		birth_location: null,
		arrival_date: null,
		interesting_facts: null,
	}
/*	
	$scope.getClassID = function(class_name){
		var class_id=null;
		$scope.classes=[];
		var post_obj = HierarchyService.getClasses();
		post_obj.then(function(result){
			$scope.classes=result;
			
			for (var i = 0; i < $scope.classes.length; i++) {
				if($scope.classes[i].name===class_name){
					class_id=$scope.classes[i].class_id;
					break;
				}
			}
		$scope.$apply();
	})

		console.log($scope.classes);
		return class_id;
	}*/
/*
	$scope.getOrdersByParentClassID = function (parent_class_id){
		$scope.orders=[];
		var post_obj = HierarchyService.getOrdersByParentClass(parent_class_id);
		post_obj.then(function(result){
			$scope.orders=result;
			$scope.$apply();
			console.log($scope.orders);
		})
	}

	$scope.getFamiliesByParentOrders = function(){
		$scope.families=[];
		var post_obj;
		for (var i = 0; i < $scope.orders.length; i++) {

			post_obj = HierarchyService.getFamiliesByParentOrder($scope.orders[i].order_id);
			post_obj.then(function(result){
				$scope.families.push(result);
				$scope.$apply();
			})
		};	
	}

	$scope.getSpeciiesByParentFamilies = function(){
		$scope.species=[];
		var post_obj;
		for (var i = 0; i < $scope.families.length; i++) {

			post_obj = HierarchyService.getFamiliesByParentOrder($scope.families[i].family_id);
			post_obj.then(function(result){
				$scope.species.push(result);
				$scope.$apply();
			})
		};	
	}
*/



/*


	$scope.deleteAnimal = function(animal){
		var post_obj = AnimalsService.deleteAnimal(animal)
		post_obj.then(function(result){
			$scope.refreshAnimals()
		})
	}

	$scope.updateAnimal = function(animal){
		var post_obj = AnimalsService.updateAnimal(animal);
		post_obj.then(function(result){
			$scope.refreshAnimals()
		})
	}

	$scope.toggleEditAnimals = function(animals){
		$scope.refreshClasses()
		var form = $("#form_"+animals.animal_id)
		if(!form.is(":visible")) {
			form.show()
			$('html, body').animate({
				scrollTop: form.offset().top
			});
		}
		else form.hide()
	}

	}*/
	$scope.species=[];
	$scope.getSpecies = function(){
		var post_obj = SpeciesService.getSpecies()
		post_obj.then(function(result){
			$scope.species=result
			if(!$scope.$$phase)
						$scope.$apply();
		})
	}




	$scope.mammalSpecies=[];
	var class_name="Mammalia";
	class_id=null;
	$scope.classes=[];
	var post_obj=null;
	$scope.orders=[];
	$scope.families=[];
	//get class_id with class_name
	var post_obj = HierarchyService.getClasses();
	post_obj.then(function(result){
		$scope.classes=result;
		for (var i = 0; i < $scope.classes.length; i++) {
			if($scope.classes[i].name===class_name){
				class_id=$scope.classes[i].class_id;
				break;
			}
		}
		if(!$scope.$$phase)
						$scope.$apply();
		//console.log(class_id);
		//get orders with parent_class_id
		post_obj = HierarchyService.getOrdersByParentClass(class_id);
		post_obj.then(function(result){
			$scope.orders=result;
			if(!$scope.$$phase)
						$scope.$apply();
			//console.log($scope.orders);
			//get families of all above orders
			for (var i = 0; i < $scope.orders.length; i++) {
				post_obj = HierarchyService.getFamiliesByParentOrder($scope.orders[i].order_id);
				post_obj.then(function(result){
					$scope.families=$scope.families.concat(result);
					if(!$scope.$$phase)
						$scope.$apply();
					
				})
			}
			//get species
			var post_obj = SpeciesService.getSpecies()
			post_obj.then(function(result){
				$scope.species=result
				if(!$scope.$$phase)
						$scope.$apply();
				//get species by families ID
				for (var i = 0; i < $scope.species.length; i++) {
					for (var j = 0; j < $scope.families.length; j++) {
						if($scope.families[j]===$scope.species[i].species_id){
							$scope.mammalSpecies=$scope.mammalSpecies.concat($scope.species[i]);
							break;
						}
					};
				};

			//get animals

				function getSpeciesByID(species_id){
					for (var i = 0; i < $scope.species.length; i++) {
						if($scope.species[i].species_id===species_id){
							return $scope.species[i];
						}
						
					};
				}
				post_obj=AnimalsService.getAnimals(null).then(function(result){
					$scope.animals=result;
					if(!$scope.$$phase)
						$scope.$apply();
					/*for (var i = 0; i < $scope.animals.length; i++) {
						var species = getSpeciesByID($scope.animals[i].species_id);
						$scope.animals[i].species_name=species.name;
					};*/
				})
			})

		})

	})

	$scope.registerAnimal = function(){
		//console.log($scope.new_species.photo)
		post_obj = AnimalsService.registerAnimal($scope.new_animal);
		post_obj.then(function(result){
			location.reload();
		})
	}

	



})