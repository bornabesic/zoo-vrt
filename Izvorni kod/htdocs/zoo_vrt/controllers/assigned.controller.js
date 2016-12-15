app.controller("AssignedController", function($scope, GuardService){


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
	GuardService.getAssignedAnimals(ls['user_id']).then(function(result){
		$scope.assigned=result;
		if(!$scope.$$phase)
			$scope.$apply();
	})



	$scope.toggleEditAnimals = function(animals){
		var form = $("#form_"+animals.animal_id)
		if(!form.is(":visible")) {
			form.show()

		}
		else form.hide()
	}

/*
	$scope.updateAnimal = function(animal){
		var post_obj = GuardService.updateAnimal(animal);
		post_obj.then(function(result){
			$scope.refreshSpecies()
		})
	}


*/
})