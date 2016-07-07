gridApp.controller('PostOtherViewCtrl',function($scope,$state,$filter,$http,ControVarAccessFac,$ionicLoading,$ionicPopup,$ionicPlatform,$ionicHistory){
												
	$scope.PostDetailsViewBack = function() {
			 //$state.go('main.post');
			 $ionicHistory.goBack(-1);
  	};
	$scope.postOther={
		post_join:true,
		post_request:false,
		post_accept:false,
		approve_status:'',
		post_creator_id:'',
		post_row_id:'',
		post_going_list:[],
		post_going_dispaly_list:[],
		going_list:false,
		empty_going_list_txt:"No one Yet Going...",
		going_list_name_txt:'',
		going_list_more:'',
		going_list_more_flag:false,
		add_friend_flag:true,
		formated_date:''
	}
	var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
	$scope.postOtherResponse='';
	
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
		$ionicLoading.show({
								template: '<ion-spinner icon="android"></ion-spinner>'
		});
		
		var link = 'http://grid.digiopia.in/user/view_event_other';
							$http.post(link, {
									user_id : ControVarAccessFac.getData("user_ID"),						 							 									event_id: ControVarAccessFac.getData("post_ID")
							},config).then(function (res){
					$ionicLoading.hide();
					$scope.postOtherResponse=res.data.response;
					$scope.postOther.approve_status=$scope.postOtherResponse.is_approve;
					$scope.postOther.post_creator_id=$scope.postOtherResponse.user_id;
					$scope.postOther.post_row_id=$scope.postOtherResponse.row_id;
					$scope.postOther.post_going_list=[];
					$scope.postOther.post_going_list=$scope.postOtherResponse.is_going;
					$scope.postOther.formated_date=$scope.formateDate($scope.postOtherResponse.formated_date)
					ControVarAccessFac.setData("goingListOther",$scope.postOther.post_going_list);
					if($scope.postOtherResponse.is_friend=='accept'||$scope.postOtherResponse.is_friend=='request'){
						$scope.postOther.add_friend_flag=false;
					}else{
						$scope.postOther.add_friend_flag=true;
					}
				if($scope.postOther.post_going_list==''){
					$scope.postOther.going_list=false;
				}else{
					$scope.postOther.going_list=true;
					$scope.postOther.going_list_name_txt='';
					if($scope.postOther.post_going_list.length>5){
						$scope.postOther.going_list_more_flag=true;
						$scope.postOther.post_going_dispaly_list=[];
						for(var i=0;i<5;i++){
						$scope.postOther.post_going_dispaly_list[i]=$scope.postOther.post_going_list[i]
							if(i<=3){
								$scope.postOther.going_list_name_txt+=$scope.postOther.post_going_list[i].fullname.split(" ")[0]+",  								";
							}else{
							$scope.postOther.going_list_name_txt+=$scope.postOther.post_going_list[i].fullname.split(" ")[0]+"... ";
							}
						}
						$scope.postOther.going_list_more=$scope.postOther.post_going_list.length-5
					}else{
						$scope.postOther.post_going_dispaly_list=[];
						$scope.postOther.post_going_dispaly_list=$scope.postOther.post_going_list;
						$scope.postOther.going_list_more_flag=false;
						for(var i=0;i<$scope.postOther.post_going_dispaly_list.length;i++){
						
							if(i<$scope.postOther.post_going_dispaly_list.length-1){
								$scope.postOther.going_list_name_txt+=$scope.postOther.post_going_list[i].fullname.split(" ")[0]+",  								";
							}else{
							$scope.postOther.going_list_name_txt+=$scope.postOther.post_going_list[i].fullname.split(" ")[0]+" ";
							}
						}
					}
				}
					
					$scope.setApproveStatus();
								//console.log($scope.postOtherResponse)
							},function (error){
								$ionicLoading.hide();
								//alert("Post error.."+error)
								$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
								})
							})
		
	});
	
	
	
	$scope.setApproveStatus= function(){
					if($scope.postOther.approve_status=='cancel'||$scope.postOther.approve_status==''){
						$scope.postOther.post_join=true;
					}else{
						$scope.postOther.post_join=false;
					}
					if($scope.postOther.approve_status=='request'){
						$scope.postOther.post_request=true;
					}else{
						$scope.postOther.post_request=false;
					}
					
					if($scope.postOther.approve_status=='accept'){
						$scope.postOther.post_accept=true;
					}else{
						$scope.postOther.post_accept=false;
					}
	}
	
	
		$scope.join_post= function(joinStatus){
			$ionicLoading.show({
			template: '<ion-spinner icon="android"></ion-spinner>'
			});
			var link = 'http://grid.digiopia.in/user/view_event_update';
			$http.post(link, { 	
					   row_id: $scope.postOther.post_row_id,
					   status : joinStatus
			},config).then(function (res){
			$ionicLoading.hide();
			//$scope.postOtherResponse=res.data.response;
			$scope.postOther.approve_status=res.data.response.is_approve;
			$scope.setApproveStatus();
			//console.log($scope.postOtherResponse)
			},function (error){
			$ionicLoading.hide();
			//alert("Post error.."+error)
			
			})
		}
		
	  $scope.openOtherProfile= function(index){
		ControVarAccessFac.setData("other_user_ID",$scope.postOther.post_creator_id);
		$state.go('main.profile-other');
	 }
	 $scope.openPostWantGoingView =function(){
		$state.go('main.post-going-view-other');
	}
	
	$scope.formateDate=function(dateStr){
		var strLength=dateStr.length;
		return dateStr.slice(0,strLength-4);
	}											
})