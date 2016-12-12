app.controller("AdoptedController", function($scope, $stateParams, AdoptService, AnimalsService){

	//
	$scope.animals = []

	
	//functions
	function getAdopted(){

		AdoptService.getAdopted(localStorage['user_id']).then(function(result) {
			$scope.animals=result;
			
			if(!$scope.$$phase) {
				$scope.$apply();
			}

			var animals = $scope.animals
			for(var i=0; i<animals.length; i++){
				var animal = animals[i]

				AnimalsService.getExclusiveContent(animal.animal_id).then(function(result){
					animal.exclusive_content=result
					if(!$scope.$$phase) {
						$scope.$apply();
					}

					console.log(result)
					
					//inicijalizacija galerije
					var photos = animal.exclusive_content.photos
					var videos = animal.exclusive_content.videos

					var gallery = []
					if(photos!=null){
						for(var j=0; j<photos.length; j++){
							if(photos[j]==null) continue;

							var ext = photos[j].split('.').pop();
							var gallery_photo_item = {
								href: photos[j],
								type: 'image/jpeg'
							}
							gallery.push(gallery_photo_item)
						}
					}
					if(videos!=null){
						for(var j=0; j<videos.length; j++){
							if(videos[j]==null) continue;

							var ext = videos[j].split('.').pop();
							var gallery_photo_item = {
								href: videos[j],
	        					type: 'video/mp4'
							}
							gallery.push(gallery_photo_item)
						}
					}

					if(gallery.length>0){
						blueimp.Gallery(gallery,
						{
						    container: "#blueimp_"+result.animal_id,
						    carousel: true
						}
						);
					}
					else{
							$("#blueimp_"+result.animal_id).hide()
					}
					

					//

				})
			}
		})
	}

	$scope.toggleFacts = function(animal){
		var facts = $("#facts_"+animal.animal_id);
		if(!facts.is(":visible")) facts.show()
		else facts.hide()
	}

	$scope.toggleGallery = function(animal) {
		var gallery = $("#gallery_"+animal.animal_id)
		if(!gallery.is(":visible")) gallery.show()
		else gallery.hide()
	}


	//init
	getAdopted();
	
})