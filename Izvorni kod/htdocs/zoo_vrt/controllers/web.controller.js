app.controller("WebController", function($scope, $state, AuthService){

	//Variables
	var ls = localStorage
	$scope.username = ls['username']

	//Functions
	$scope.logout = function(){
		AuthService.logoutUser()
		$state.go("login");
	}

	$scope.scrollToContent = function(){
		    $('html, body').animate({
			scrollTop: $(".content").offset().top
			}, 1000);
	}

	$scope.isGuard = function(){
		return ls['role']&2;
	}

	//Init
	if(!AuthService.loggedIn() || ls['role']&1==0){
		$state.go("login")
	}
})