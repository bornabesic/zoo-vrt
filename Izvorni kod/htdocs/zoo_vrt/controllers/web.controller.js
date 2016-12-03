app.controller("WebController", function($scope, $state, AuthService){

	//Variables
	var ls = localStorage
	$scope.username = ls['username']

	//Functions
	$scope.logout = function(){
		AuthService.logoutUser()
		$state.go("login");
	}

	//Init
	if(!AuthService.loggedIn() || ls['role']&1==0){
		$state.go("login")
	}
})