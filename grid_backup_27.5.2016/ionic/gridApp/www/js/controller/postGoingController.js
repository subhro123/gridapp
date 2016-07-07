gridApp.controller('PostGoingViewCtrl',function($scope,$state,$filter,$http,ControVarAccessFac,$ionicLoading,$ionicPopup,$ionicPlatform,$timeout,$ionicModal){
												
	$scope.PostWantJoinViewBack = function() {
			 $state.go('main.post-owner-view');
  	};
	
	$scope.postGoingJoin={
		going_list:[]
	}
	
	var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
	
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
		$scope.postGoingJoin.going_list=ControVarAccessFac.getData("goingList");
	})
	
	$scope.postJoinUpdate= function(index,joinStatus){
			$ionicLoading.show({
			template: '<ion-spinner icon="android"></ion-spinner>'
			});
			var link = 'http://grid.digiopia.in/user/view_event_update';
			$http.post(link, { 	
					   row_id: $scope.postGoingJoin.going_list[index].row_id,
					   status : joinStatus
			},config).then(function (res){
			$ionicLoading.hide();
			//$scope.postOtherResponse=res.data.response;
			//$scope.postOther.approve_status=res.data.response.is_approve;
			//$scope.setApproveStatus();
			//console.log($scope.postOtherResponse)
			$scope.postGoingJoin.going_list.splice(index,1)
			$scope.showSuccessPopup('Successfully updated')
			},function (error){
			$ionicLoading.hide();
			//alert("Post error.."+error)
				$ionicPopup.alert({
				  title: 'Network Connection Error',
				  content: 'Please check your network connection'
				})
			});
		}
		
	 $scope.openOtherProfile= function(index){
		ControVarAccessFac.setData("other_user_ID",$scope.postGoingJoin.going_list[index].id);
		console.log($scope.postGoingJoin.going_list[index].id)
		$state.go('main.profile-other');
	 }	
		
		
		
	/* coinfirm message oppup */
	
	// A confirm dialog
 $scope.showConfirm = function(index,joinStatus) {
	 var msg='';
	 if(joinStatus=='accept'){
		msg="Are you sure to accept?" 
	 }else{
		 msg="Are you sure to cancel?"
	 }
   var confirmPopup = $ionicPopup.confirm({
     title: 'Warning!!',
     template: msg
   });

   confirmPopup.then(function(res) {
     if(res) {
		 $scope.postJoinUpdate(index,joinStatus)
       //console.log('You are sure');
     } else {
       //console.log('You are not sure');
     }
   });
 };
		
		
		
	/* success message pop up */
   $scope.showSuccessPopup = function(txtMsg) {
	   
  		$scope.data = {};

  // An elaborate, custom popup
  var myPopup = $ionicPopup.show({
    template: '',
    title: 'Success',
    subTitle: txtMsg,
    scope: $scope   
  });

  myPopup.then(function(res) {
    console.log('Tapped!', res);
  });

  $timeout(function() {
     myPopup.close(); //close the popup after 3 seconds for some reason
  }, 2000);
 };
	
})