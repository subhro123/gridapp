gridApp.controller('ImportContactMenuCtrl',function($scope,$state,ControVarAccessFac){
	
	
	$scope.importContactByType=function(type){
		
		ControVarAccessFac.setData("selecContactImportType","PHONE");
		$state.go('main.import-contact');
	}
})