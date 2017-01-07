app.controller("LoginController", function($scope, $state, AuthService, UserService){

	//Variables
	var ls = localStorage
	$scope.registration=false

	//Functions
	function isUsernameChosen (username){
		if(!username){
			alert("Odaberite korisničko ime.");
			return false;
		}
		return true;
	}

	function isPasswordChosen (password){
		if(!password){
			alert("Odaberite lozinku.");
			return false;
		}
		return true;
	}

	function isFirstAndLastNameChosen (first_last_name){
		if(!first_last_name){
			alert("Odaberite ime i prezime.");
			return false;
		}
		return true;
	}

	function isYearOfBirthChosen (year_of_birth){
		if(!year_of_birth){
			alert("Odaberite godinu rođenja.");
			return false;
		}
		return true;
	}

	function isCityChosen (city){
		if(!city){
			alert("Odaberite mjesto stanovanja.");
			return false;
		}
		return true;
	}

	function isEmailChosen (email){
		if(!email){
			alert("Odaberite email.");
			return false;
		}
		return true;
	}
	$scope.showRegistration = function(){
		$("#registration").show()
		$("#submit_button").val("Registriraj se")
		$("#un").focus()

		$scope.registration=true
	}

	$scope.formSubmitted = function(){
		if(isUsernameChosen($scope.username) && isPasswordChosen($scope.password)){
			if($scope.registration){
				if(isFirstAndLastNameChosen($scope.first_last_name) && isEmailChosen($scope.email) && 
						isCityChosen($scope.city) && isYearOfBirthChosen($scope.year_of_birth)){
					var post_obj = UserService.registerUser($scope.username, $scope.password, $scope.first_last_name, $scope.year_of_birth, $scope.city, $scope.email, 1)
					post_obj.then(function(result){
						location.reload()
					})
				}
			}
			else{
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
							$state.go("web")
						}
						else{
							alert(result.username + " - korisnik nema ovlasti za pristup ZOO Vrt web aplikaciji!")
						}
					}
				})
			}
		}
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

		if($scope.loggedIn && $scope.isVisitor){
			$state.go("web")
		}
	})
})