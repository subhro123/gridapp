gridApp.controller('PostGoingViewOtherCtrl',function($scope,$state,$filter,$http,ControVarAccessFac,$ionicLoading,$ionicPopup,$ionicPlatform){
												
	$scope.PostWantJoinViewBack = function() {
			 $state.go('main.post-other-view');
  	};
	
	$scope.postGoingJoin={
		going_list:[]
	}
	
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
		$scope.postGoingJoin.going_list=ControVarAccessFac.getData("goingListOther");
	})
	
	/*$scope.postJoinUpdate= function(index,joinStatus){
			$ionicLoading.show({
			template: '<ion-spinner icon="android"></ion-spinner>'
			});
			var link = 'http://grid.digiopia.in/user/view_event_update';
			$http.post(link, { 	
					   row_id: $scope.postGoingJoin.going_list[index].row_id,
					   status : joinStatus
			}).then(function (res){
			$ionicLoading.hide();
			
			$scope.postGoingJoin.going_list.splice(index,1)
			},function (error){
			$ionicLoading.hide();
			//alert("Post error.."+error)
			
			})
		}*/
	
})