app.controller("UsersController", function($scope, $http, UserService){

	//Variables


	$scope.new_user = {
		username: null,
		first_last_name: null,
		email: null,
		city: null,
		year_of_birth: null,
		is_visitor: true,
		is_guard: null,
		is_admin: null
	}

	//Functions
	$scope.refreshUsers = function(){
		$scope.users=[];
		var post_obj = UserService.getUsers();
		post_obj.then(function(result){
			$scope.users=result;
			for(var i=0; i<$scope.users.length; i++){
				var user = $scope.users[i]
				var role = user.role;
				if(role&1) user.is_visitor=true;
				if(role&2) user.is_guard=true;
				if(role&4) user.is_admin=true;
			}
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		});
		//alert($scope.users)
	}

	$scope.registerUser = function(){
		var new_user = $scope.new_user;
		var role=0;
		if(new_user.is_visitor) role+=1;
		if(new_user.is_guard) role+=2;
		if(new_user.is_admin) role+=4;

		var post_obj = UserService.registerUser(new_user.username, new_user.password, new_user.first_last_name, new_user.year_of_birth, new_user.city, new_user.email, role);
		post_obj.then(function(result){
			$scope.refreshUsers();
		})
	}

	$scope.deleteUser = function(user){
		//izbrisi korisnika iz baze
		UserService.deleteUser(user.user_id).then(function(result){
			
			//izbrisi korisnika iz lokalne liste
			for(var i=0; i<$scope.users.length; i++){
				if($scope.users[i].user_id===user.user_id) break;
			}
			$scope.users.splice(i, 1);
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})


	}

	$scope.toggleEditUser = function(user){
		//otvori/zatvori formu
		var form = $("#form_"+user.user_id);
		if(!form.is(":visible")) form.show();
		else form.hide();
	}

	$scope.updateUser = function(user){
		var role=0;

		/* mora se moći prijaviti */
		user.is_visitor=true;

		if(user.is_visitor) role+=1;
		if(user.is_guard) role+=2;
		if(user.is_admin) role+=4;

		//azuriraj korisnicke informacije u bazi
		var post_obj = UserService.updateUser(user.user_id, user.username, user.password, user.first_last_name, user.year_of_birth, user.city, user.email, role);
		post_obj.then(function(result){
			//sakrij formu
			$("#form_"+user.user_id).hide();
			$scope.refreshUsers();
		})
	}

	//Init
	$scope.refreshUsers();

});