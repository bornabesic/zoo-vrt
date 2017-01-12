app.controller("FamiliesController", function($scope, $http, HierarchyService){

	//Variables
	$scope.new_family = {
		name: null,
		parent_order_id: null
	}


	//Functions

	$scope.refreshClasses = function(){
		$scope.classes=[];
		var post_obj = HierarchyService.getClasses();
		post_obj.then(function(result){
			$scope.classes=result;
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.refreshOrders = function(){
		$scope.orders=[];
		var post_obj = HierarchyService.getOrders();
		post_obj.then(function(result){
			$scope.orders=result;
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.refreshFamilies = function(){
		$scope.families=[];
		var post_obj = HierarchyService.getFamilies();
		post_obj.then(function(result){
			$scope.families=result;
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}

	$scope.registerFamily = function(){
		var new_family = $scope.new_family
		
		var post_obj = HierarchyService.registerFamily(new_family.name, new_family.parent_order_id)
		post_obj.then(function(result){
			$scope.refreshFamilies()
		})
	}

	$scope.deleteFamily = function(family){
		var post_obj = HierarchyService.deleteFamily(family.family_id)
		post_obj.then(function(result){
			$scope.refreshFamilies()
		})
	}

	$scope.updateFamily = function(family){
		var post_obj = HierarchyService.updateFamily(family.family_id, family.name, family.parent_order_id)
		post_obj.then(function(result){
			$("#form_"+family.family_id).hide();
			$scope.refreshFamilies()
		})
	}

	$scope.toggleEditFamily = function(family){
		//otvori/zatvori formu
		var form = $("#form_"+family.family_id);
		if(!form.is(":visible")) form.show();
		else form.hide();
	}
	

	//Init
	$scope.refreshClasses()
	$scope.refreshOrders()
	$scope.refreshFamilies()

});