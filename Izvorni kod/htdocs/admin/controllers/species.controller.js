app.controller("SpeciesController", function($scope, HierarchyService, SpeciesService){
	/* updateSpecies nije rješen, dropdown u updateu ne radi */

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
		location_y: null
	}

	//Functions
	$scope.refreshSpecies = function(){
		var post_obj = SpeciesService.getSpecies()
		post_obj.then(function(result){
			$scope.species=result
			console.log(result)
			$scope.$apply()
		})
	}

	$scope.drawMap = function(){
		var img = new Image;
		img.src = "/img/karta.png";

		var canvas = document.getElementById("karta");
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

		    $scope.new_species.location_x = x
		    $scope.new_species.location_y = y
		    $scope.$apply()

		    //console.log("x: " + x + " y: " + y);

		}, false)


		img.onload = function(){
			context.drawImage(img,
				0,0, img.width, img.height,
				0,0, canvas.width, canvas.height);
		}
	}

	$scope.registerSpecies = function(){
		var post_obj = SpeciesService.registerSpecies($scope.new_species);
		post_obj.then(function(result){
			$scope.refreshSpecies()
		})
	}

	$scope.deleteSpecies = function(species){
		var post_obj = SpeciesService.deleteSpecies(species)
		post_obj.then(function(result){
			$scope.refreshSpecies()
		})
	}

	$scope.updateSpecies = function(species){
		
	}

	$scope.toggleEditSpecies = function(specie){
		$scope.refreshClasses()
		var form = $("#form_"+specie.species_id)
		if(!form.is(":visible")) {
			form.show()
			$('html, body').animate({
				scrollTop: form.offset().top
			});
		}
		else form.hide()
	}

	$scope.refreshClasses = function(){
		$scope.classes=[];
		var post_obj = HierarchyService.getClasses();
		post_obj.then(function(result){
			$scope.classes=result;
			$scope.$apply()
		})
	}

	$scope.refreshOrdersByParentClass = function(){
		$scope.orders=[];
		var post_obj = HierarchyService.getOrdersByParentClass($scope.new_species.class_id);
		post_obj.then(function(result){
			$scope.orders=result;
			$scope.$apply()
		})
	}

	$scope.refreshFamiliesByParentOrder = function(){
		$scope.families=[];
		var post_obj = HierarchyService.getFamiliesByParentOrder($scope.new_species.order_id);
		post_obj.then(function(result){
			$scope.families=result;
			$scope.$apply()
		})
	}
	

	//Init
	$scope.refreshSpecies()
	$scope.refreshClasses()
	$scope.drawMap()
});