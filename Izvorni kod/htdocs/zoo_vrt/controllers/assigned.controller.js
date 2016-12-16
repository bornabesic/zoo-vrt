app.controller("AssignedController", function($scope, GuardService, AnimalsService){

/*
	$scope.assigned =
		[
			{
				species_name: "Lav",
				animals: [
							{
								animal_id: 1,
								name: "leo1",
								arrival_date:"danas",
								birth_location:"nemam pojma",
								facts:"tu su facts",
								exclusive_facts:"tu su exclusive facts"
							},
							{
								animal_id: 2,
								name: "leo2",
								arrival_date:"sutra",
								birth_location:"nemam pojma",
								facts:"tu su facts",
								exclusive_facts:"tu su exclusive facts"

							}
					]
			},
			{
				species_name: "Đero",
				animals: [
							{
								animal_id: 3,
								name: "đero1",
								arrival_date:"danas",
								birth_location:"nemam pojma",
								facts:"tu su facts",
								exclusive_facts:"tu su exclusive facts"
							},
							{
								animal_id: 4,
								name: "đero2",
								arrival_date:"danas",
								birth_location:"nemam pojma",
								facts:"tu su facts",
								exclusive_facts:"tu su exclusive facts"

							}
					]
			}
	]
*/

	var ls=localStorage;

	var refreshAssigned = function(){
		GuardService.getAssignedAnimals(ls['user_id']).then(function(result){
		$scope.assigned=result;
		if(!$scope.$$phase)
			$scope.$apply();
		})
	}

	$scope.toggleEditAnimals = function(animals){
		var form = $("#form_"+animals.animal_id)
		if(!form.is(":visible")) {
			form.show()

		}
		else form.hide()
	}

	$scope.addExclusiveFact = function(animal){
		GuardService.addExclusiveFact(animal.animal_id, animal.exclusive_fact).then(function(result){

		})
	}

	$scope.addExclusivePhoto = function(animal){
		GuardService.addExclusivePhoto(animal.animal_id, animal.exclusive_photo).then(function(result){
			
		})
	}

	$scope.addExclusiveVideo = function(animal){
		console.log(animal.exclusive_video.size)

		if(animal.exclusive_video.size > 33554432){ // ako je video veći od 32 MiB
			alert("Najveća dopuštena veličina video isječka je 32 MiB.")
			return
		}
	
		GuardService.addExclusiveVideo(animal.animal_id, animal.exclusive_video).then(function(result){
			
		})
	}

	$scope.updateAnimal = function(animal){
		var post_obj = AnimalsService.updateAnimal(animal);
		post_obj.then(function(result){
			if(!result.error) location.reload()
		})
		console.log(animal.animal_name + " će ažurirati.")
	}

	refreshAssigned()

})