gridApp.controller('MainCtrl', function($scope, $ionicSideMenuDelegate,$interval,$rootScope,ControVarAccessFac,$ionicPopup,$ionicHistory,$state) {
										
	  $scope.toggleLeft = function(str) {
		 
		  if(str=='logout'){
			 $interval.cancel($rootScope.updatePosition); 
			 ControVarAccessFac.destroyModel();
		  }
		  if(str=='payment'){
			  //alert(ControVarAccessFac.getData("user_subscription"));
			  ControVarAccessFac.setData("payment_type","subscription");
			  if(ControVarAccessFac.getData("user_subscription")=='paiduser'){
				  $ionicPopup.alert({
					  title: 'Payment Alert',
					  content: 'You are already paid for this month'
					})				  
			  }
		  }
        $ionicSideMenuDelegate.toggleLeft();
		
      };
	  
})