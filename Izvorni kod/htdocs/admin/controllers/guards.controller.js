app.controller("GuardsController", function($scope, $http, AnimalsService, GuardService, UserService){

	//Variables

	$scope.selectedGuardID = null;

	$scope.assigned_animals = [];
	$scope.unassigned_animals = [];
	$scope.assignments = [];
	$scope.guards = [];
	$scope.new_guard = [];

	//Functions

	$scope.refreshAnimals = function(){
		var post_obj = AnimalsService.getAnimals(null)
		post_obj.then(function(result){
			var unassigned_animals = [];

			for(var i = 0; i < result.length; i++) {
				var assigned = false;
				for(var j = 0; j < $scope.assigned_animals.length; j++){
					if (result[i].animal_id == $scope.assigned_animals[j].animal_id) assigned = true;
				}
				if (assigned == true) continue;
				unassigned_animals.push(result[i])
			}
			$scope.unassigned_animals = unassigned_animals;

			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.refreshAssignments = function(){
		var post_obj = GuardService.getAssignedAnimals($scope.selectedGuardID)
		post_obj.then(function(result){
			$scope.assignments = result;

			var assigned_animals = [];
			for(var i = 0; i < result.length; i++){
				for(var j = 0; j < result[i].animals.length; j++){
					assigned_animals.push(result[i].animals[j]);
				}
			}

			$scope.assigned_animals = assigned_animals;

			$scope.refreshAnimals();
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.refreshGuards = function(){
		var post_obj = UserService.getUsers()
		post_obj.then(function(result){
			var all_users = result;
			var guards = [];

			for(var i = 0; i < all_users.length; i++){
				if (all_users[i].role & 2) guards.push(all_users[i]);
			}

			$scope.guards = guards;
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.selectGuard = function(guard_id){
		if ($scope.selectedGuardID == guard_id){
			$scope.selectedGuardID = null;
		} else {
			$scope.selectedGuardID = guard_id;
		}
	
		$scope.refreshAssignments();

		if(!$scope.$$phase) {
			$scope.$apply();
		}
	}

	$scope.assignAnimal = function(animal_id){
		if ($scope.selectedGuardID == null) {
			alert("Za ovu operaciju potrebno je odabrati čuvara!");
			return;
		}

	console.log("Assigning animal: " + animal_id + " to guard: " + $scope.selectedGuardID);
		var post_obj = GuardService.assignAnimal($scope.selectedGuardID, animal_id)
		post_obj.then(function(result){
			console.log("refreshAssignments")
			$scope.refreshAssignments()
		})
	}

	$scope.unassignAnimal = function(animal_id){
		if ($scope.selectedGuardID == null) {
			alert("Za ovu operaciju potrebno je odabrati čuvara!");
			return;
		}

	console.log("Unssigning animal: " + animal_id);
		var post_obj = GuardService.unassignAnimal($scope.selectedGuardID, animal_id)
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

	$scope.refreshAnimals();
	$scope.refreshGuards();

});