app.controller("AssignedController", function($scope, GuardService, AnimalsService){

	var ls=localStorage;

	$scope.assigned=[]
	$scope.noAnimals=false;

	function isInputChosen (animal){
		if(animal.animal_name===""){
			alert("Polje 'Ime' ne smije biti prazno");
			return false;
		}
		if(animal.arrival_date===""){
			alert("Polje 'Datum dolaska' ne smije biti prazno");
			return false;
		}
		if(animal.birth_location===""){
			alert("Polje 'Mjesto rođenja' ne smije biti prazno");
			return false;
		}
		if(animal.interesting_facts===""){
			alert("Polje 'Zanimljivosti' ne smije biti prazno");
			return false;
		}		
		return true;
	}
	function isExclusiveFactChosen (animal){
		if(!animal.exclusive_fact){
			alert("Nije upisana ekskluzivna zanimljivost.");
			return false;
		}
		return true;
	}

	function isPhotoChosen (animal){
		if(!animal.exclusive_photo){
			alert("Nije odabrana ekskluzivna fotografija.");
			return false;
		}
		return true;
	}

	function isVideoChosen (animal){
		if(!animal.exclusive_video){
			alert("Nije odabran ekskluzivi video isječak.");
			return false;
		}
		return true;
	}	

	var refreshAssigned = function(){
		GuardService.getAssignedAnimals(ls['user_id']).then(function(result){
		$scope.assigned=result;

		if($scope.assigned.length<=0){
			$scope.noAnimals=true;
		}

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
		if(isExclusiveFactChosen(animal)){
			GuardService.addExclusiveFact(animal.animal_id, animal.exclusive_fact).then(function(result){

			})
		}
	}

	$scope.addExclusivePhoto = function(animal){
		if(isPhotoChosen(animal)){
			GuardService.addExclusivePhoto(animal.animal_id, animal.exclusive_photo).then(function(result){
				
			})
		}
	}

	$scope.addExclusiveVideo = function(animal){
		if(isVideoChosen(animal)){
			console.log(animal.exclusive_video.size)

			if(animal.exclusive_video.size > 33554432){ // ako je video veći od 32 MiB
				alert("Najveća dopuštena veličina video isječka je 32 MiB.")
				return
			}
		
			GuardService.addExclusiveVideo(animal.animal_id, animal.exclusive_video).then(function(result){
				
			})
		}
	}

	$scope.updateAnimal = function(animal){
		if(isInputChosen(animal)){
			var post_obj = AnimalsService.updateAnimal(animal);
			post_obj.then(function(result){
				if(!result.error) location.reload()
			})
			console.log(animal.animal_name + " će ažurirati.")
		}
	}

	refreshAssigned()

})