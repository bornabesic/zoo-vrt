app.controller("ProfileController", function($scope, UserService){

	var ls=localStorage;
	$scope.user = {
		user_id: ls['user_id'],
		username: ls['username'],
		first_last_name: ls['first_last_name'],
		email: ls['email'],
		city: ls['city'],
		year_of_birth: parseInt(ls['year_of_birth']),
		role: ls['role'],
		password1:"",
		password2:""
	}





	$scope.updateUser = function(user){
		//provjera passworda

		if($scope.user.password2!=$scope.user.password1){
			alert("Unesite lozinke koje se podudaraju!");
			return;
		}
		//azuriraj korisnicke informacije u bazi
		var post_obj = UserService.updateUser(user.user_id, user.username, user.password1, user.first_last_name, user.year_of_birth, user.city, user.email, user.role);
		post_obj.then(function(result){
			ls['first_last_name']=$scope.user.first_last_name,
			ls['email']=$scope.user.email,
			ls['city']=$scope.user.city,
			ls['year_of_birth']=$scope.user.year_of_birth,
			ls['role']=$scope.user.role
			location.reload();
		})
	}

})