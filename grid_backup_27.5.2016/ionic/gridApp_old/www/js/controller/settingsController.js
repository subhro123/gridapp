gridApp.controller('SettingsCtrl', function($scope, $ionicSideMenuDelegate,$http,ControVarAccessFac) {
											
		$scope.setting={
			volume:ControVarAccessFac.getData("map_radious"),
			min_radius:'',
			max_radius:'',
			rangePaidFlag:ControVarAccessFac.getData("rangePaidFlag"),
			rangeFreeFlag:ControVarAccessFac.getData("rangeFreeFlag")
		}
		//ControVarAccessFac.setData("user_subscription",res.data.response.user_subscription);
		$scope.$on('$ionicView.enter', function (viewInfo, state) {
			console.log("xxxxxx"+ControVarAccessFac.getData("map_radious"))
			$scope.setting.rangePaidFlag=ControVarAccessFac.getData("rangePaidFlag")
			$scope.setting.rangeFreeFlag=ControVarAccessFac.getData("rangeFreeFlag")
			/*if(ControVarAccessFac.getData("user_subscription")=="freeuser"){
				$scope.setting.rangeflag=false;				
			}else{
				$scope.setting.rangeflag=true;
			}*/
			$scope.setting.min_radius=ControVarAccessFac.getData("min_map_radious");
			$scope.setting.max_radius=ControVarAccessFac.getData("max_map_radious");
			$scope.setting.volume=ControVarAccessFac.getData("map_radious")
			
			//alert("$scope.setting.min_radius::"+$scope.setting.min_radius+"  $scope.setting.max_radius::"+$scope.setting.max_radius+"   $scope.setting.volume"+$scope.setting.volume)
			
		});
		
		$scope.onRangeChange = function(selVal) {		
    		//alert(selVal);
			//$scope.payment.selCardType=selVal.val
			ControVarAccessFac.setData("map_radious",selVal);
  	}
			
})