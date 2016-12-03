var app = angular.module("ZOOVrt", ["ui.router"]);

app.config(function($stateProvider, $locationProvider){

	// ================================== KOMPONENTE ==================================
	var login = {
		controller: "LoginController",
		templateUrl: "/zoo_vrt/views/login.html"
	}

	var web = {
		controller: "WebController",
		templateUrl: "/zoo_vrt/views/web_new.html"
	}

	var map = {
		controller: "MapController",
		templateUrl: "/zoo_vrt/views/map.html"
	}

	var explore = {
		controller: "ExploreController",
		templateUrl: "/zoo_vrt/views/explore.html"
	}

	var adopted = {
		controller: "AdoptedController",
		templateUrl: "/zoo_vrt/views/adopted.html"
	}

	// ================================== ROUTING ==================================

	$stateProvider.state("login", {
		url: "/",
		views: {
			"main": login
		}
	});

	$stateProvider.state("web", {
		url: "/",
		views: {
			"main": web,
			"map@web": map
		}
	});

	$stateProvider.state("explore", {
		url: "/explore",
		views: {
			"main": web,
			"map@explore": map,
			"content@explore": explore
		}
	});

	$stateProvider.state("adopted", {
		url: "/adopted",
		views: {
			"main": web,
			"map@adopted": map,
			"content@adopted": adopted
		}
	});
})