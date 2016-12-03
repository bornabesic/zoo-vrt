app.controller("LoginController", function($scope, $state, AuthService){

	//Vars

	//Functions
	$scope.loginUser = function(){
		var post_obj = AuthService.loginUser($scope.username, $scope.password)
		post_obj.then(function(result){
			if(!result.error){
				if(result.role&4){
					localStorage.setItem("user_id", result.user_id);
					localStorage.setItem("username", result.username);
					localStorage.setItem("city", result.city);
					localStorage.setItem("email", result.email);
					localStorage.setItem("year_of_birth", result.year_of_birth);
					localStorage.setItem("first_last_name", result.first_last_name);
					localStorage.setItem("role", result.role);
					$state.go("panel")
				}
				else{
					alert(result.username + " - korisnik nema ovlasti za pristup ZOO Vrt Admin Panelu!")
				}
			}
		})

		/*if($scope.username){
			localStorage.setItem("username", $scope.username);
			$state.go("panel")
		}*/

	}

	//Init
	if($state.current=="panel"){
		$state.go("panel")
	}

});