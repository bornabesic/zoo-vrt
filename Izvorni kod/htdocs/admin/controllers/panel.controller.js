app.controller("PanelController", function($scope, $state, $http){

	//Variables

	//Functions

	$scope.logout = function(){
		localStorage.removeItem("username");
		$state.go("login");
	}


	//Init

	if(localStorage["username"]===undefined){
		$state.go("login");
	}

	$scope.username = localStorage["username"];


});
