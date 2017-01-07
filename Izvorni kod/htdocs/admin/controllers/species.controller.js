app.controller("SpeciesController", function($scope, HierarchyService, SpeciesService){

	//Variables
	$scope.new_species = {
		name: null,
		class_id: null,
		order_id: null,
		family_id: null,
		size: null,
		nutrition: null,
		predators: null,
		lifetime: null,
		habitat: null,
		lifestyle: null,
		reproduction: null,
		distribution: null,
		location_x: null,
		location_y: null,
		photo_path: null
	}

	//Functions

	function isPhotoChosen (species){
		if(!species.photo){
			alert("Nije odabrana slika.");
			return false;
		}
		return true;
	}

	function isDropdownChosen (species){
		if(!species.class_id){
			alert("Niste odabrali razred.")
			return false;
		}

		if(!species.order_id){
			alert("Niste odabrali red.")
			return false;
		}

		if(!species.family_id){
			alert("Niste odabrali familiju.")
			return false;
		}
		return true;
	}

	$scope.mapInit = function(species){
		drawMap("karta_"+species.species_id, species)
	}

	function drawMap(map, species){
		var img = new Image;
		img.src = "/media/karta.png";

		img.onload = function(){

			var canvas = document.getElementById(map);
			
			var context = canvas.getContext("2d");
			context.strokeStyle = "#df4b26";
			context.lineJoin = "round";
			context.lineWidth = 5;

			var radius = 10;
			canvas.addEventListener('click', function(e){
				context.clearRect(0, 0, canvas.width, canvas.height);
				context.drawImage(img,
					0,0, img.width, img.height,
					0,0, canvas.width, canvas.height);

				var rect = canvas.getBoundingClientRect();
			    var x = parseInt(e.clientX - rect.left);
			    var y = parseInt(e.clientY - rect.top);

				
			    //crtanje oznake
				context.beginPath();
				context.arc(x, y, radius, 0, 2 * Math.PI, false);
				context.fillStyle = 'red';
				context.fill();
				context.stroke();
			    //

			    if(species!=null){
			    	species.location_x = x
			    	species.location_y = y
			    }
			    else{
					$scope.new_species.location_x = x
				    $scope.new_species.location_y = y

			    }
			    if(!$scope.$$phase) {
					$scope.$apply();
				}


			}, false)


			context.drawImage(img,
				0,0, img.width, img.height,
				0,0, canvas.width, canvas.height);
		}

		

	}

	function refreshSpecies(){
		var post_obj = SpeciesService.getSpecies()
		post_obj.then(function(result){
			$scope.species=result

			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.registerSpecies = function(){
		console.log($scope.new_species.photo)

		//provjera dropdowna
		
		if(isDropdownChosen($scope.new_species) && isPhotoChosen($scope.new_species)){
			var post_obj = SpeciesService.registerSpecies($scope.new_species);
			post_obj.then(function(result){
				refreshSpecies()
			})
		}
	}

	$scope.deleteSpecies = function(species){
		var post_obj = SpeciesService.deleteSpecies(species)
		post_obj.then(function(result){
			refreshSpecies()
		})
	}

	$scope.updateSpecies = function(species){
		var post_obj = SpeciesService.updateSpecies(species);
		post_obj.then(function(result){
			refreshSpecies()
		})
	}

	$scope.toggleEditSpecies = function(species){
		refreshClasses()
		var form = $("#form_"+species.species_id)
		if(!form.is(":visible")) {
			form.show()
			$('html, body').animate({
				scrollTop: form.offset().top
			});
		}
		else form.hide()
	}

	function refreshClasses(){
		$scope.classes=[];
		var post_obj = HierarchyService.getClasses();
		post_obj.then(function(result){
			$scope.classes=result;
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.refreshOrdersByParentClass = function(parent_class_id){
		$scope.orders=[];
		var post_obj = HierarchyService.getOrdersByParentClass(parent_class_id);
		post_obj.then(function(result){
			$scope.orders=result;
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.refreshFamiliesByParentOrder = function(parent_order_id){
		$scope.families=[];
		var post_obj = HierarchyService.getFamiliesByParentOrder(parent_order_id);
		post_obj.then(function(result){
			$scope.families=result;
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}
	

	//Init
	drawMap("karta", null)

	refreshSpecies()
	refreshClasses()
});