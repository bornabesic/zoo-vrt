app.controller("GuardsController", function($scope, $http, GuardService){

	//Variables

	//Functions

	$scope.refreshGuards = function(guard_id){
		var post_obj = GuardService.getGuards()
		post_obj.then(function(result){
			$scope.guards=result;

			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.refreshAssignments = function(guard_id){
		var post_obj = GuardService.getAssignedAnimals(guard_id)
		post_obj.then(function(result){
			$scope.assignments=result;

			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.assignAnimal = function(guard_id, animal_id){
		var new_guard = $scope.new_guard

		var post_obj = GuardService.assignAnimal(guard_id, animal_id)
		post_obj.then(function(result){
			$scope.refreshAssignments()
		})
	}

	$scope.unassignAnimal = function(guard_id, animal_id){
		var post_obj = GuardService.unassignAnimal(guard_id, animal_id)
		post_obj.then(function(result){
			$scope.refreshAssignments()
		})
	}

	$scope.addExclusiveFact = function(animal_id, fact){
		var post_obj = GuardService.addExclusiveFact(animal_id, fact)
		post_obj.then(function(result){
			$scope.refreshAssignments()
		})
	}

	$scope.addExclusivePhoto = function(animal_id, photo){
		var post_obj = GuardService.addExclusivePhoto(animal_id, photo)
		post_obj.then(function(result){
			$scope.refreshAssignments()
		})
	}

	$scope.addExclusiveVideo = function(animal_id, video){
		var post_obj = GuardService.addExclusiveVideo(animal_id, video)
		post_obj.then(function(result){
			$scope.refreshAssignments()
		})
	}

	//Init

	$scope.refreshGuards();

});