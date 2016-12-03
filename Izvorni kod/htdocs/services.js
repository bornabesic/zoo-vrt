// ================================== SERVISI ==================================

app.service('AuthService', function() {
	var ls = localStorage
	this.loginUser = function (username, password){
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

	this.loggedIn = function(){
		return ls['user_id']!=undefined &&
		ls['username']!=undefined && 
		ls['city']!=undefined && 
		ls['email']!=undefined && 
		ls['year_of_birth']!=undefined &&
		ls['first_last_name']!=undefined &&
		ls['role']!=undefined
	}

	this.logoutUser = function(){
		ls.clear()
	}
})

app.service('UserService', function() {
    this.registerUser = function (username, password, first_last_name, year_of_birth, city, email, role) {
        
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

    this.deleteUser = function(user_id){
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

     this.updateUser = function(user_id, username, password, first_last_name, year_of_birth, city, email, role){
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

    this.getUsers = function(){
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
});

app.service('HierarchyService', function() {
	//CLASSES
	this.getClasses = function (){
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

	this.registerClass = function (name){
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

	this.deleteClass = function (class_id){
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

	this.updateClass = function(class_id, name){
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
	this.getOrders = function(){
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

	this.getOrdersByParentClass = function(parent_class_id){
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

	this.registerOrder = function(name, parent_class_id){
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

	this.updateOrder = function(order_id, name, parent_class_id){
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

	this.deleteOrder = function(order_id){
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
	this.getFamilies = function(){
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

	this.getFamiliesByParentOrder = function(parent_order_id){
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

	this.registerFamily = function(name, parent_order_id){
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

	this.updateFamily = function(family_id, name, parent_order_id){
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

	this.deleteFamily = function(family_id){
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
})

app.service('SpeciesService', function(){
	this.registerSpecies = function(species){
		post_data={
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
			"action": "add_species"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri registraciji životinjske vrste.");
  					console.log(data.error)
  				}
  				else{
 					alert("Životinjska vrsta uspješno registrirana!")
  				}
		}, "JSON");

		return post_obj;

	}
	this.deleteSpecies = function(species){
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
		}, "JSON");

		return post_obj;
	}

	this.updateSpecies = function(){
		
	}

	this.getSpecies = function(){
		post_data={
			"action": "get_species"
		}

		var post_obj = $.post("/Database.php", post_data, function(data) {
  				if(data.error){
  					alert("Nažalost, došlo je do greške pri dohvatu popisa životinjskih vrsti.");
  					console.log(data.error)
  				}
		}, "JSON");

		return post_obj;
	}
	this.getSpeciesByParentFamily = function(parent_family_id){
		
	}
})