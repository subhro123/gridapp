gridApp.controller('PostWantJoinViewCtrl',function($scope,$state,$filter,$http,ControVarAccessFac,$ionicLoading,$ionicPopup,$ionicPlatform,$timeout,$ionicModal){
												
	$scope.PostWantJoinViewBack = function() {
			 $state.go('main.post-owner-view');
  	};
	
	$scope.postWantJoin={
		join_list:[],
		availablePerson:0
	}
	var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
	
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
		$scope.postWantJoin.join_list=ControVarAccessFac.getData("wishList");
		$scope.postWantJoin.availablePerson=ControVarAccessFac.getData("availableCount")
		console.log("$scope.postWantJoin.availablePerson"+$scope.postWantJoin.availablePerson)
	})
	
	$scope.postJoinUpdate= function(index,joinStatus){
			$ionicLoading.show({
			template: '<ion-spinner icon="android"></ion-spinner>'
			});
			var link = 'http://grid.digiopia.in/user/view_event_update';
			$http.post(link, { 	
					   row_id: $scope.postWantJoin.join_list[index].row_id,
					   status : joinStatus
			},config).then(function (res){
			$ionicLoading.hide();
			//$scope.postOtherResponse=res.data.response;
			//$scope.postOther.approve_status=res.data.response.is_approve;
			//$scope.setApproveStatus();
			//console.log($scope.postOtherResponse)
			//"is_approve":"accept"
			$scope.postWantJoin.availablePerson=res.data.response.availableperson;
			if(res.data.response.is_approve=="accept"){
				$scope.postWantJoin.join_list.splice(index,1)
				$scope.showSuccessPopup('Successfully updated')
			}else if(res.data.response.is_approve=="cancel"){
				$scope.postWantJoin.join_list.splice(index,1)
				$scope.showSuccessPopup('Successfully canceled')
			}else if(res.data.response.is_approve=="notaccept"){
				$ionicPopup.alert({
							title: 'SORRY!',
							content: "Maximum Spots Reached"
				})
			}
			},function (error){
			$ionicLoading.hide();
			//alert("Post error.."+error)
			$ionicPopup.alert({
									  title: 'Post want to join Error',
									  content:  error.statusText
								})
			})
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