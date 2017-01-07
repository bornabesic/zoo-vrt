app.controller("ClassesController", function($scope, $http, HierarchyService){

	//Variables

	$scope.new_class = {
		name: null
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

	$scope.toggleEditClass = function(klass){
		//otvori/zatvori formu
		var form = $("#form_"+klass.class_id);
		if(!form.is(":visible")) form.show();
		else form.hide();
	}

	$scope.registerClass = function(){
		var new_class = $scope.new_class;

		var post_obj = HierarchyService.registerClass(new_class.name);
		post_obj.then(function(result){
			$scope.refreshClasses();
		})
	}

	$scope.updateClass = function(klass){
		//azuriraj korisnicke informacije u bazi
		var post_obj = HierarchyService.updateClass(klass.class_id, klass.name);
		post_obj.then(function(result){
			//sakrij formu
			$("#form_"+klass.class_id).hide();
			$scope.refreshClasses();
		})
	}

	$scope.deleteClass = function(klass){
		//izbrisi razred iz baze
		var post_obj = HierarchyService.deleteClass(klass.class_id);
		post_obj.then(function(result){
			//izbrisi razred iz lokalne liste
			var i=0;
			for(; i<$scope.classes.length; i++){
				if($scope.classes[i].class_id===klass.class_id) break;
			}
			$scope.classes.splice(i, 1);
			if(!$scope.$$phase) {
				$scope.$apply();
			}
		})
	}
	

	//Init
	$scope.refreshClasses();

});