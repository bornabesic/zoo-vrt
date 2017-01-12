app.controller("WebController", function($scope, $state, AuthService){

	//Variables
	var ls = localStorage
	$scope.username = ls['username']

	$scope.isGuard=false;
	$scope.isVisitor=false;
	$scope.isAdmin=false;

	//Functions
	$scope.logout = function(){
		AuthService.logoutUser().then(function(result){
			$state.go("login");
		})
	}

	$scope.scrollToContent = function(){
		    $('html, body').animate({
			scrollTop: $(".content").offset().top
			}, 1000);
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

		if(!$scope.loggedIn){
			$state.go("login")
		}
	})
})