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
					$state.go("panel")
				}
				else{
					alert(result.username + " - korisnik nema ovlasti za pristup ZOO Vrt Admin Panelu!")
				}
			}
		})
	}

	//Init
	AuthService.checkUserState().then(function(result){
		$scope.isVisitor=result.is_visitor
		$scope.isGuard=result.is_guard
		$scope.isAdmin=result.is_admin
		$scope.loggedIn=result.logged_in

		if(!$scope.$$phase) {
			$scope.$apply();
		}

		if($scope.loggedIn && $scope.isAdmin){
			$state.go("panel")
		}
	})

});