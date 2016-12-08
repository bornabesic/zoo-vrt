var app = angular.module("ZOOVrtAdmin", ["ui.router"]);

app.config(function($stateProvider, $locationProvider){

	//$locationProvider.html5Mode(true)

	// ================================== KOMPONENTE ==================================

	var login = {
		controller: "LoginController",
		templateUrl: "views/login.html"
	}

	var panel = {
		controller: "PanelController",
		templateUrl: "views/panel.html"
	}

	var users = {
		controller: "UsersController",
		templateUrl: "views/users.html"
	}

	var species = {
		controller: "SpeciesController",
		templateUrl: "views/species.html"
	}

	var statistics = {
		controller: "StatisticsController",
		templateUrl: "views/statistics.html"
	}

	var classes = {
		controller: "ClassesController",
		templateUrl: "views/classes.html"
	}

	var orders = {
		controller: "OrdersController",
		templateUrl: "views/orders.html"
	}

	var families = {
		controller: "FamiliesController",
		templateUrl: "views/families.html"
	}

	// ================================== ROUTING ==================================

	$stateProvider.state("login", {
		url: "/",
		views: {
			"adminMain": login
		}
	});

	$stateProvider.state("panel", {
		url: "/",
		views: {
			"adminMain": panel
		}
	});

	$stateProvider.state("users", {
		url: "/users",
		views: {
			"adminMain": panel,
			"adminPanel@users": users
		}
	});

	$stateProvider.state("species", {
		url: "/species",
		views: {
			"adminMain": panel,
			"adminPanel@species": species
		}
	});

	$stateProvider.state("statistics", {
		url: "/statistics",
		views: {
			"adminMain": panel,
			"adminPanel@statistics": statistics
		}
	});

	$stateProvider.state("classes", {
		url: "/classes",
		views: {
			"adminMain": panel,
			"adminPanel@classes": classes
		}
	});

	$stateProvider.state("orders", {
		url: "/orders",
		views: {
			"adminMain": panel,
			"adminPanel@orders": orders
		}
	});

	$stateProvider.state("families", {
		url: "/families",
		views: {
			"adminMain": panel,
			"adminPanel@families": families
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

