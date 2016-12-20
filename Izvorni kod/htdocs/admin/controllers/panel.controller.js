app.controller("PanelController", function($scope, $state, AuthService){

	//Variables

	//Functions

	$scope.logout = function(){
		AuthService.logoutUser().then(function(result){
			$state.go("login");
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

		if(!$scope.loggedIn){
			$state.go("login")
		}
	})

	$scope.username = localStorage["username"];

});
