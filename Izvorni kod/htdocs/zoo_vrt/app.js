var app = angular.module("ZOOVrt", ["ui.router"]);

app.config(function($stateProvider, $locationProvider){

	// ================================== KOMPONENTE ==================================
	var login = {
		controller: "LoginController",
		templateUrl: "/zoo_vrt/views/login.html"
	}

	var web = {
		controller: "WebController",
		templateUrl: "/zoo_vrt/views/web.html"
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

	var species = {
		controller: "SpeciesController",
		templateUrl: "/zoo_vrt/views/species.html"
	}

	var animals = {
		controller: "AnimalsController",
		templateUrl: "/zoo_vrt/views/animals.html"
	}

	var profile = {
		controller: "ProfileController",
		templateUrl: "/zoo_vrt/views/profile.html"
	}

	var assigned = {
		controller: "AssignedController",
		templateUrl: "/zoo_vrt/views/assigned.html"
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
			"map@web": map,
			"content@web": explore
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

	$stateProvider.state("species", {
		url: "/species/:species_id",
		views: {
			"main": web,
			"map@species": map,
			"content@species": species
		}
	});

	$stateProvider.state("animals", {
		url: "/animals/:species_id",
		views: {
			"main": web,
			"map@animals": map,
			"content@animals": animals
		}
	});

	$stateProvider.state("profile", {
		url: "/profile",
		views: {
			"main": web,
			"map@profile": map,
			"content@profile": profile
		}
	});

	$stateProvider.state("assigned", {
		url: "/assigned",
		views: {
			"main": web,
			"map@assigned": map,
			"content@assigned": assigned
		}
	});
})

// ================================== DIREKTIVE ==================================

app.directive("maxLength", [function() {
    return {
        restrict: "A",
        link: function(scope, elem, attrs) {
            var limit = parseInt(attrs.maxLength);
            angular.element(elem).on("keypress", function(e) {
                if (this.value.length == limit && e.keyCode!=8) e.preventDefault();
            });
        }
    }
}]);

app.directive('fileModel', ['$parse', function ($parse) {
    return {
    restrict: 'A',
    link: function(scope, element, attrs) {
        var model = $parse(attrs.fileModel);
        var modelSetter = model.assign;

        element.bind('change', function(){
            scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
            });
        });
    }
   };
}]);

// ----

app.filter('yearRange', function() {
  return function(input) {
  	var currentYear = new Date().getFullYear();
    min = currentYear-100
    max = currentYear
    for (var i=min; i<=max; i++)
      input.push(i);
    return input;
  };
});

app.filter('monthRange', function() {
  return function(input) {
    min = 1
    max = 12
    for (var i=min; i<=max; i++)
      input.push(i);
    return input;
  };
});

app.filter('dayRange', function() {
  return function(input) {
  	var currentYear = new Date().getFullYear();
    min = 1
    max = 31
    for (var i=min; i<=max; i++)
      input.push(i);
    return input;
  };
});