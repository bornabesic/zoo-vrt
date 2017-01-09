// ================================== SERVISI ==================================

app.service('AuthService', function() {
	var ls = localStorage
	var loginUser = function (username, password){
		post_data={
			"username": username,
			"password": password,
			"action": "login_user"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Neuspjela prijava.");
  					console.log(data.error)
  				}
  				else{

  				}
		}, "JSON");
		return post_obj;
	}

	var checkUserState = function(){
		post_data={
			"action": "check_user_state"
		}

		var post_obj = $.post("/Database.php", post_data, null, "JSON");
		return post_obj;
	}

	var logoutUser = function(){
		post_data={
			"action": "logout_user"
		}

		var post_obj = $.post("/Database.php", post_data, function(){
			ls.removeItem("user_id")
			ls.removeItem("username")
			ls.removeItem("city")
			ls.removeItem("email")
			ls.removeItem("year_of_birth")
			ls.removeItem("first_last_name")
		}, "JSON");
		return post_obj;
	}

	return{
		loginUser: loginUser,
		logoutUser: logoutUser,
		checkUserState: checkUserState
	}
})

app.service('UserService', function() {
    var registerUser = function (username, password, first_last_name, year_of_birth, city, email, role) {
        
        post_data={
			"username": username,
			"password": password,
			"first_last_name": first_last_name,
			"year_of_birth": year_of_birth,
			"city": city,
			"email": email,
			"role": role,
			"action": "register_user"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri registraciji korisnika.");
  					console.log(data.error)
  				}
  				else{
  					alert("Korisnik uspješno registriran!");
  				}
		}, "JSON");

		return post_obj;
    }

    var deleteUser = function(user_id){
    	post_data={
			"user_id": user_id,
			"action": "delete_user"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri brisanju korisnika.");
  					console.log(data.error)
  				}
  				else{
  					alert("Korisnik uspješno obrisan!");
  				}
		}, "JSON");

		return post_obj;
    }

    var updateUser = function(user_id, username, password, first_last_name, year_of_birth, city, email, role){
    	post_data={
    		"user_id": user_id,
			"username": username,
			"password": password,
			"first_last_name": first_last_name,
			"year_of_birth": year_of_birth,
			"city": city,
			"email": email,
			"role": role,
			"action": "update_user"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri ažuriranju korisnika.");
  					console.log(data.error)
  				}
  				else{
  					alert("Korisnik uspješno ažuriran!");
  				}
		}, "JSON");

		return post_obj;
    }

    var getUsers = function(){
    	post_data={
			"action": "get_users"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvatu popisa korisnika.");
  					console.log(data.error)
  				}
  				else{
 
 					
  				}
		}, "JSON");

		return post_obj;
    }

    return{
    	registerUser: registerUser,
		deleteUser: deleteUser,
		getUsers: getUsers,
		updateUser: updateUser
    }
});

app.service('GuardService', function($http, $q) {

	var assignAnimal = function(user_id, animal_id){
		post_data={
			"user_id": user_id,
			"animal_id": animal_id,
			"action": "assign_animal"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dodjeli jedinke čuvaru.");
  					console.log(data.error)
  				}
  				else{
  					alert("Jedinka uspješno dodijeljena!");
  				}
		}, "JSON");

		return post_obj;
	}

	var unassignAnimal = function(user_id, animal_id){
		post_data={
			"user_id": user_id,
			"animal_id": animal_id,
			"action": "unassign_animal"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri uskraćivanju jedinke čuvaru.");
  					console.log(data.error)
  				}
  				else{
  					alert("Jedinka uspješno uskraćena!");
  				}
		}, "JSON");

		return post_obj;
	}

	var getAssignedAnimals = function(user_id){
		post_data={
			"user_id": user_id,
			"action": "get_assigned_animals"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvaćanju dodijeljenih jedinki.");
  					console.log(data.error)
  				}
		}, "JSON");

		return $q(function(resolve, reject) {
					post_obj.then(function(result){
						var animals = result
						var groups=new Set()
						var final_result=[]
						//priprema polja za kontroler
						for(var i=0; i<animals.length; i++){
							groups.add(animals[i].species_name);
						}

						for(let group of groups){
							var group_animals=[]
							for(var i=0; i<animals.length; i++){
								if(animals[i].species_name===group){
									group_animals.push(animals[i])
								}
							}
							final_result.push(
								{
									species_name: group,
									animals: group_animals
								}
							)
						}

						//vrati
						resolve(final_result)
					})
				})
	}

	var addExclusiveFact = function(animal_id, fact){
		post_data={
			"animal_id": animal_id,
			"fact": fact,
			"action": "add_exclusive_fact"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dodavanju eksluzivne činjenice.");
  					console.log(data.error)
  				}
  				else{
  					alert("Ekskluzivna činjenica uspješno dodana!");
  				}
		}, "JSON");

		return post_obj;
	}

	var addExclusivePhoto = function(animal_id, photo){
		var post_data = new FormData()
		post_data.append("animal_id", animal_id)
		post_data.append("photo", photo)
		post_data.append("action", "add_exclusive_photo")

		var post_obj = $http.post("/Database.php", post_data, {
             transformRequest: angular.identity,
             headers: {'Content-Type': undefined,'Process-Data': false}
         })

		post_obj.success(function(data){
			if(data.error){
  					alert("Nažalost, došlo je do greške pri dodavanju eksluzivne fotografije.");
  					console.log(data.error)
  				}
  				else{
 					alert("Eksluzivna fotografija uspješno dodana!")
  				}
		})
         
         post_obj.error(function(data){
            alert("Prijenos slike na poslužitelj nije uspio.");
         })

         return post_obj;
	}

	var addExclusiveVideo = function(animal_id, video){
		var post_data = new FormData()
		post_data.append("animal_id", animal_id)
		post_data.append("video", video)
		post_data.append("action", "add_exclusive_video")

		var post_obj = $http.post("/Database.php", post_data, {
             transformRequest: angular.identity,
             headers: {'Content-Type': undefined,'Process-Data': false}
         })

		post_obj.success(function(data){
			if(data.error){
  					alert("Nažalost, došlo je do greške pri dodavanju eksluzivnog video isječka.");
  					console.log(data.error)
  				}
  				else{
 					alert("Eksluzivni video isječak uspješno dodan!")
  				}
		})
         
         post_obj.error(function(data){
            alert("Prijenos video isječka na poslužitelj nije uspio.");
         })

         return post_obj;
	}

	return{
		assignAnimal: assignAnimal,
		unassignAnimal: unassignAnimal,
		getAssignedAnimals: getAssignedAnimals,
		addExclusiveFact: addExclusiveFact,
		addExclusivePhoto: addExclusivePhoto,
		addExclusiveVideo: addExclusiveVideo
	}

})

app.service('HierarchyService', function() {
	//HIERARCHY
	var getSpeciesHierarchy = function(species_id){
		post_data={
			"species_id": species_id,
			"action": "get_species_hierarchy"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvatu hijerarhije životinjske vrste.");
  					console.log(data.error)
  				}
		}, "JSON");

		return post_obj;
	}

	//CLASSES
	var getClasses = function (){
		post_data={
			"action": "get_classes"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvatu popisa razreda.");
  					console.log(data.error)
  				}
  				else{
 
 					
  				}
		}, "JSON");

		return post_obj;
	}

	var registerClass = function (name){
		post_data={
			"name": name,
			"action": "add_class"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri registraciji razreda.");
  					console.log(data.error)
  				}
  				else{
 					alert("Razred uspješno registriran!")
  				}
		}, "JSON");

		return post_obj;
	}

	var deleteClass = function (class_id){
		post_data={
			"class_id": class_id,
			"action": "remove_class"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri brisanju razreda.");
  					console.log(data.error)
  				}
  				else{
 					alert("Razred uspješno obrisan!")
  				}
		}, "JSON");

		return post_obj;
	}

	var updateClass = function(class_id, name){
		post_data={
			"class_id": class_id,
			"name": name,
			"action": "update_class"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri ažuriranju razreda.");
  					console.log(data.error)
  				}
  				else{
 					alert("Razred uspješno ažuriran!")
  				}
		}, "JSON");

		return post_obj;
	}

	//ORDERS
	var getOrders = function(){
		post_data={
			"action": "get_orders"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvatu popisa redova.");
  					console.log(data.error)
  				}
		}, "JSON");

		return post_obj;
	}

	var getOrdersByParentClass = function(parent_class_id){
		post_data={
			"parent_class_id": parent_class_id,
			"action": "get_orders"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvatu popisa redova.");
  					console.log(data.error)
  				}
		}, "JSON");

		return post_obj;
	}

	var registerOrder = function(name, parent_class_id){
		post_data={
			"name": name,
			"parent_class_id": parent_class_id,
			"action": "add_order"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri registraciji reda.");
  					console.log(data.error)
  				}
  				else{
 					alert("Red uspješno registriran!")
  				}
		}, "JSON");

		return post_obj;
	}

	var updateOrder = function(order_id, name, parent_class_id){
		post_data={
			"order_id": order_id,
			"name": name,
			"parent_class_id": parent_class_id,
			"action": "update_order"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri ažuriranju reda.");
  					console.log(data.error)
  				}
  				else{
  					alert("Red uspješno ažuriran!")
  				}
		}, "JSON");

		return post_obj;
	}

	var deleteOrder = function(order_id){
		post_data={
			"order_id": order_id,
			"action": "remove_order"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri brisanju reda.");
  					console.log(data.error)
  				}
  				else{
 					alert("Red uspješno obrisan!")
  				}
		}, "JSON");

		return post_obj;
	}

	//FAMILIES
	var getFamilies = function(){
		post_data={
			"action": "get_families"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvatu popisa porodica.");
  					console.log(data.error)
  				}
		}, "JSON");

		return post_obj;
	}

	var getFamiliesByParentOrder = function(parent_order_id){
		post_data={
			"parent_order_id": parent_order_id,
			"action": "get_families"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvatu popisa porodica.");
  					console.log(data.error)
  				}
		}, "JSON");

		return post_obj;
	}

	var registerFamily = function(name, parent_order_id){
		post_data={
			"name": name,
			"parent_order_id": parent_order_id,
			"action": "add_family"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri registraciji porodice.");
  					console.log(data.error)
  				}
  				else{
 					alert("Porodica uspješno registrirana!")
  				}
		}, "JSON");

		return post_obj;
	}

	var updateFamily = function(family_id, name, parent_order_id){
		alert(parent_order_id)
		post_data={
			"family_id": family_id,
			"name": name,
			"parent_order_id": parent_order_id,
			"action": "update_family"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri ažuriranju porodice.");
  					console.log(data.error)
  				}
  				else{
  					alert("Porodica uspješno ažurirana!")
  				}
		}, "JSON");

		return post_obj;
	}

	var deleteFamily = function(family_id){
		post_data={
			"family_id": family_id,
			"action": "remove_family"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri brisanju porodice.");
  					console.log(data.error)
  				}
  				else{
 					alert("Porodica uspješno obrisana!")
  				}
		}, "JSON");

		return post_obj;
	}

	return{
		getSpeciesHierarchy: getSpeciesHierarchy,

		getClasses: getClasses,
		registerClass: registerClass,
		deleteClass: deleteClass,
		updateClass: updateClass,

		getOrders: getOrders,
		getOrdersByParentClass: getOrdersByParentClass,
		registerOrder: registerOrder,
		updateOrder: updateOrder,
		deleteOrder: deleteOrder,

		getFamilies: getFamilies,
		getFamiliesByParentOrder: getFamiliesByParentOrder,
		registerFamily: registerFamily,
		updateFamily: updateFamily,
		deleteFamily: deleteFamily
	}

})

app.service('SpeciesService', function($http, $q){
	var _species=[];

	var registerSpecies = function(species){
		/*post_data={
			"name": species.name,
			"family_id": species.family_id,
			"size": species.size,
			"nutrition": species.nutrition,
			"predators": species.predators,
			"lifetime": species.lifetime,
			"habitat": species.habitat,
			"lifestyle": species.lifestyle,
			"reproduction": species.reproduction,
			"distribution": species.distribution,
			"location_x": species.location_x,
			"location_y": species.location_y,
			"photo": photo
			"action": "add_species"
		}*/

		var post_data = new FormData()
		post_data.append("name", species.name)
		post_data.append("family_id", species.family_id)
		post_data.append("size", species.size)
		post_data.append("nutrition", species.nutrition)
		post_data.append("predators", species.predators)
		post_data.append("lifetime", species.lifetime)
		post_data.append("habitat", species.habitat)
		post_data.append("lifestyle", species.lifestyle)
		post_data.append("reproduction", species.reproduction)
		post_data.append("distribution", species.distribution)
		post_data.append("location_x", species.location_x)
		post_data.append("location_y", species.location_y)
		post_data.append("photo", species.photo)
		post_data.append("action", "add_species")

		/*var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri registraciji životinjske vrste.");
  					console.log(data.error)
  				}
  				else{
 					alert("Životinjska vrsta uspješno registrirana!")
  				}
		}, "JSON");

		return post_obj;*/

		var post_obj = $http.post("/Database.php", post_data, {
             transformRequest: angular.identity,
             headers: {'Content-Type': undefined,'Process-Data': false}
         })

		post_obj.success(function(data){
			if(data.error){
  					alert("Nažalost, došlo je do greške pri registraciji životinjske vrste.");
  					console.log(data.error)
  				}
  				else{
 					alert("Životinjska vrsta uspješno registrirana!")
  				}
  				_species.splice(0,_species.length) //invalidate cache
		})
         
         post_obj.error(function(data){
            alert("Prijenos slike na poslužitelj nije uspio.");
         })

         return post_obj;

	}

	var deleteSpecies = function(species){
		post_data={
			"species_id": species.species_id,
			"action": "remove_species"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri brisanju životinjske vrste.");
  					console.log(data.error)
  				}
  				else{
 					alert("Životinjska vrsta uspješno obrisana!")
  				}
  				_species.splice(0,_species.length) //invalidate cache
		}, "JSON");
		return post_obj;
	}

	var updateSpecies = function(species){
		var post_data = new FormData()
		post_data.append("species_id", species.species_id)
		post_data.append("name", species.name)
		post_data.append("family_id", species.family_id)
		post_data.append("size", species.size)
		post_data.append("nutrition", species.nutrition)
		post_data.append("predators", species.predators)
		post_data.append("lifetime", species.lifetime)
		post_data.append("habitat", species.habitat)
		post_data.append("lifestyle", species.lifestyle)
		post_data.append("reproduction", species.reproduction)
		post_data.append("distribution", species.distribution)
		post_data.append("location_x", species.location_x)
		post_data.append("location_y", species.location_y)
		post_data.append("photo", species.photo)
		post_data.append("action", "update_species")

		alert(JSON.stringify(post_data))

		var post_obj = $http.post("/Database.php", post_data, {
             transformRequest: angular.identity,
             headers: {'Content-Type': undefined,'Process-Data': false}
         })

		post_obj.success(function(data){
			// nekad je error da ne prođe ovaj uvjet ?! nego javi da je uspješno
			if(data.error){
  					alert("Nažalost, došlo je do greške pri ažuriranju životinjske vrste.");
  					console.log(data.error)
  				}
  				else{
 					alert("Životinjska vrsta uspješno ažurirana!")
  				}
  				_species.splice(0,_species.length) //invalidate cache
		})
         
         post_obj.error(function(data){
            alert("Prijenos slike na poslužitelj nije uspio.");
         })

         return post_obj;
	}

	var getSpecies = function(){
		if(_species.length<=0){ // nema u cacheu (polje 'species')
			post_data={
				"action": "get_species"
			}

			var post_obj = $.post("/Database.php", post_data, function(data) {
	  				if(data.error){
	  					alert("Nažalost, došlo je do greške pri dohvatu popisa životinjskih vrsti.");
	  					console.log(data.error)
	  				}
	  				else{
	  					_species=data;
	  				}
			}, "JSON");

			return post_obj;
		}
		else{ //ima u cacheu
			return $q(function(resolve, reject) {
				resolve(_species)
			})
		}
	}

	var getSpeciesByParentFamily = function(parent_family_id){
		
	}

	var getSpeciesById = function(species_id){
		
			if(_species.length<=0){ // ako nije već dohvaćeno u polje 'species', dohvati iz baze
				return $q(function(resolve, reject) {
					getSpecies().then(function(result){
						for(var i=0; i<_species.length; i++){
							if(_species[i].species_id==species_id){
								resolve(_species[i])
								break;
							}
						}
					})
				});
			}
			else{ // inače nađi vrstu u polju 'species' i vrati ju
				return $q(function(resolve, reject) {
					for(var i=0; i<_species.length; i++){
						if(_species[i].species_id==species_id){
							resolve(_species[i])
							break;
						}
					}
				});
			}

	}

	return {
		registerSpecies: registerSpecies,
		deleteSpecies: deleteSpecies,
		updateSpecies: updateSpecies,
		getSpecies: getSpecies,
		getSpeciesByParentFamily: getSpeciesByParentFamily,
		getSpeciesById: getSpeciesById
	}

})

app.service('AnimalsService', function($http, $q){
	var _species_id=null
	var _animals=[]
	//var _exclusive_content=[]; treba implementirati cache

	var registerAnimal = function(animal){

		console.log(animal)

		var arrival_date = animal.arrival_year + "-" + animal.arrival_month + "-" + animal.arrival_day

		var post_data = new FormData()
		post_data.append("name", animal.name)
		post_data.append("age", animal.age)
		post_data.append("sex", animal.sex)
		post_data.append("species_id", animal.species_id)
		post_data.append("birth_location", animal.birth_location)
		post_data.append("arrival_date", arrival_date)
		post_data.append("interesting_facts", animal.interesting_facts)
		post_data.append("photo", animal.photo)
		post_data.append("action", "add_mammal")

		var post_obj = $http.post("/Database.php", post_data, {
             transformRequest: angular.identity,
             headers: {'Content-Type': undefined,'Process-Data': false}
         })

		post_obj.success(function(data){
			if(data.error){
  					alert("Nažalost, došlo je do greške pri registraciji jedinke.");
  					console.log(data.error)
  				}
  				else{
 					alert("Jedinka uspješno registrirana!")
  				}
  				_animals.splice(0,_animals	.length) //invalidate cache
		})
         
         post_obj.error(function(data){
            alert("Prijenos slike na poslužitelj nije uspio.");
         })

         return post_obj;

	}

	var deleteAnimal = function(animal){
		post_data={
			"animal_id": animal.animal_id,
			"action": "remove_mammal"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri brisanju jedinke.");
  					console.log(data.error)
  				}
  				else{
 					alert("Jedinka uspješno obrisana!")
  				}
  				_animals.splice(0,_animals.length) //invalidate cache
		}, "JSON");
		return post_obj;
	}

	var updateAnimal = function(animal){
		var name;
		if(animal.name) name=animal.name;
		else if(animal.animal_name) name=animal.animal_name;

		var arrival_date = animal.arrival_year + "-" + animal.arrival_month + "-" + animal.arrival_day

		var post_data = new FormData()
		post_data.append("animal_id", animal.animal_id)
		post_data.append("name", name)
		post_data.append("age", animal.age)
		post_data.append("sex", animal.sex)
		post_data.append("photo", animal.photo)
		post_data.append("birth_location", animal.birth_location)
		post_data.append("arrival_date", arrival_date)
		post_data.append("interesting_facts", animal.interesting_facts)
		post_data.append("action", "update_animal")

		console.log(animal);
		var post_obj = $http.post("/Database.php", post_data, {
             transformRequest: angular.identity,
             headers: {'Content-Type': undefined,'Process-Data': false}
         })

		post_obj.success(function(data){
			if(data.error){
				alert("Nažalost, došlo je do greške pri ažuriranju jedinke.");
				console.log(data.error)
			}
			else{
				alert("Jedinka uspješno ažurirana!")
			}
		})
         
         post_obj.error(function(data){
            alert("Prijenos slike na poslužitelj nije uspio.");
         })

         return post_obj;
	}

	var getAnimals = function(species_id){
		if(species_id!=_species_id){
			_animals.splice(0,_animals.length) //invalidate cache
			_species_id=null
		}

		if(_animals.length<=0){ //nema u cacheu
			if(species_id!=null){
				post_data={
					"species_id": species_id,
					"action": "get_animals"
				}
			}
			else{
				post_data={
					"action": "get_animals"
				}
			}


			var post_obj = $.post("/Database.php", post_data, function(data) {
	  				if(data.error){
	  					alert("Nažalost, došlo je do greške pri dohvatu popisa jedinki vrste.");
	  					console.log(data.error)
	  				}
	  				else{
	  					_animals=data
	  					_species_id=species_id
	  				}
			}, "JSON");
			return post_obj;
		}
		else{ // ima u cacheu
			return $q(function(resolve, reject) {
				resolve(_animals)
			})
		}
	}

	var getExclusiveContent = function(animal_id){
		post_data={
			"animal_id": animal_id,
			"action": "get_exclusive_content"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvatu ekskluzivnog sadržaja.");
  					console.log(data.error)
  				}
  				else{
  					//_exclusive_content=data;
  				}
		}, "JSON");

		return post_obj;
	}




	return{
		getAnimals: getAnimals,
		getExclusiveContent: getExclusiveContent,
		updateAnimal: updateAnimal,
		registerAnimal: registerAnimal,
		deleteAnimal: deleteAnimal
	}

})

app.service('AdoptService', function($q){
	var _adopted=[]

	var adopt = function(user_id, animal_id, email, first_last_name, city){
		post_data={
			"user_id": user_id,
			"animal_id": animal_id,
			"email": email,
			"first_last_name": first_last_name,
			"city": city,
			"action": "adopt"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri posvajanju jedinke.");
  					console.log(data.error)
  				}
  				else{
  					alert("Jedinka uspješno posvojena!");
  				}
  				 
  				_adopted.splice(0,_adopted.length) //invalidate cache
		}, "JSON");
		return post_obj;
	}

	var getAdopted = function(user_id){
		if(_adopted.length<=0){ //nema u cacheu
			post_data={
				"user_id": user_id,
				"action": "get_adopted"
			}

			var post_obj = $.post("/Database.php", post_data, function(data) {
	  				if(data.error){
	  					alert("Nažalost, došlo je do greške pri dohvatu posvojenih jedinki.");
	  					console.log(data.error)
	  				}
	  				else{
	  					_adopted=data;
	  					console.log(data)
	  				}
			}, "JSON");
			return post_obj;
		}
		else{//ima u cacheu
			return $q(function(resolve, reject) {
				resolve(_adopted)
			})
		}
	}

	return{
		adopt: adopt,
		getAdopted: getAdopted
	}

})

app.service('MapService', function(){
	var mapScope=null

	var dot = {
		x: null,
		y: null
	}

	var setDot = function(x,y){
		dot.x=x
		dot.y=y

		if(mapScope==null) return

		mapScope.dot.x=dot.x
		mapScope.dot.y=dot.y
		if(mapScope.draw) mapScope.draw()
	}

	var setScope = function(scope){
		mapScope=scope

		if(dot.x!=null && dot.y!=null){
			mapScope.dot.x=dot.x
			mapScope.dot.y=dot.y
			
			if(mapScope.draw) mapScope.draw()
		}
	}

	return{
		setDot: setDot,
		setScope: setScope
	}
})


app.service('VisitService', function(){

	var registerVisit = function(user_id, species_id){
		post_data={
			"user_id": user_id,
			"species_id": species_id,
			"action": "register_visit"
		};

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri registraciji posjete.");
  					console.log(data.error)
  				}
		}, "JSON");

		return post_obj;
	}

	var checkVisit = function(user_id, species_id){
		post_data={
			"user_id": user_id,
			"species_id": species_id,
			"action": "check_visit"
		};

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri provjeri posjete.");
  					console.log(data.error)
  				}
		}, "JSON");

		return post_obj;
	}

	var visitCount = function(species_id) {
		post_data = {
			"species_id": species_id,
			"action": "get_visit_count"
		};

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri provjeri posjete.");
  					console.log(data.error);
  				}
		}, "JSON");

		return post_obj;
	}

	return {
		registerVisit: registerVisit,
		checkVisit: checkVisit,
		visitCount: visitCount
	}
	
})
