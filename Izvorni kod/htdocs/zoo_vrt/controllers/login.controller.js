app.controller("LoginController", function($scope, $state, AuthService, UserService){

	//Variables
	var ls = localStorage
	$scope.registration=false

	//Functions
	$scope.showRegistration = function(){
		$("#registration").show()
		$("#submit_button").val("Registriraj se")
		$("#un").focus()

		$scope.registration=true
	}

	$scope.formSubmitted = function(){
		if($scope.registration){
			console.log($scope.username)
			console.log($scope.first_last_name)
			console.log($scope.email)
			console.log($scope.city)
			console.log($scope.year_of_birth)
		}
		else{
			console.log($scope.username)

			var post_obj = AuthService.loginUser($scope.username, $scope.password)
			post_obj.then(function(result){
				if(!result.error){
					if(result.role&1){
						ls.setItem("user_id", result.user_id);
						ls.setItem("username", result.username);
						ls.setItem("city", result.city);
						ls.setItem("email", result.email);
						ls.setItem("year_of_birth", result.year_of_birth);
						ls.setItem("first_last_name", result.first_last_name);
						ls.setItem("role", result.role);
						$state.go("web")
					}
					else{
						alert(result.username + " - korisnik nema ovlasti za pristup ZOO Vrt web aplikaciji!")
					}
				}
			})
		}
	}

	//Init
	if(AuthService.loggedIn() && ls['role']&1){
		$state.go("web")
	}

})