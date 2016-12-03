app.controller("OrdersController", function($scope, $http, HierarchyService){

	//Variables
	$scope.new_order = {
		name: null,
		parent_class_id: null
	}


	//Functions

	$scope.refreshClasses = function(){
		$scope.classes=[];
		var post_obj = HierarchyService.getClasses();
		post_obj.then(function(result){
			$scope.classes=result;
			$scope.$apply()
		})
	}

	$scope.refreshOrders = function(){
		$scope.orders=[];
		var post_obj = HierarchyService.getOrders();
		post_obj.then(function(result){
			$scope.orders=result;
			$scope.$apply()
		})
	}

	$scope.registerOrder = function(){
		var new_order = $scope.new_order
		
		var post_obj = HierarchyService.registerOrder(new_order.name, new_order.parent_class_id)
		post_obj.then(function(result){
			$scope.refreshOrders()
		})
	}

	$scope.updateOrder = function(order){
		var post_obj = HierarchyService.updateOrder(order.order_id, order.name, order.parent_class_id)
		post_obj.then(function(result){
			$("#form_"+order.order_id).hide();
			$scope.refreshOrders()
		})
	}

	$scope.deleteOrder = function(order){
		var post_obj = HierarchyService.deleteOrder(order.order_id)
		post_obj.then(function(result){
			$scope.refreshOrders()
		})
	}

	$scope.toggleEditOrder = function(order){
		//otvori/zatvori formu
		var form = $("#form_"+order.order_id);
		if(!form.is(":visible")) form.show();
		else form.hide();
	}
	

	//Init
	$scope.refreshClasses()
	$scope.refreshOrders()

});