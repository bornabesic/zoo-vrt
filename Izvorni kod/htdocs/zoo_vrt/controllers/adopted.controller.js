app.controller("AdoptedController", function($scope, $stateParams, AdoptedService){

	//
	$scope.animals = [
	{animal_id:'4', species_id:'7', name:'đero1', age:'42', sex:'M', birth_location:'zAGREB', arrival_date:'dns', photo_path:'/img/species/pero.png', interesting_facts:'zanimljive price', exclusive_facts:'dodatno, imfads fjidsfjsdf', exclusive_photos:['/img/species/pero.png', '/img/species/pero.png', '/img/species/pero.png']},
	{animal_id:'5', species_id:'7', name:'đero2', age:'42', sex:'M', birth_location:'zAGREB', arrival_date:'dns', photo_path:'/img/species/pero.png', interesting_facts:'zanimljive price fdsadsafasdas das dasd s ', exclusive_facts:'dodatno, imfads fjids dsfasd ad sdf sads sfjsdf', exclusive_photos:['/img/species/pero.png', '/img/species/pero.png', '/img/species/pero.png'], exclusive_videos:['/img/species/Cannonball_dookie.mp4']}
	];

	/*
	//functions
	function getAnimalsByUserID(user_id){

		AnimalsService.getAnimalsByUserID(user_id).then(function(result) {
			$scope.animals=result;
			$scope.$apply();
		})
	}

	*/

	$scope.openGallery = function(animal) {
		var gallery = $("#gallery_"+animal.animal_id);
		if(!gallery.is(":visible")) gallery.show()
		else gallery.hide()
	}


	//init
	//getAnimalsByUserID(user_id);
	
})