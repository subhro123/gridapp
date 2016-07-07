gridApp.controller('PostOwnerViewCtrl',function($scope,$state,$filter,$http,ControVarAccessFac,$ionicLoading,$ionicPopup,$ionicPlatform,$ionicHistory){
										   
	$scope.PostDetailsViewBack = function() {
			// $state.go('main.post');
			$ionicHistory.goBack(-1)
  	};
	
	$scope.postOwner={
		
		post_going_list:[],
		post_going_dispaly_list:[],
		going_list:false,
		empty_going_list_txt:"No one Yet Going...",
		going_list_name_txt:'',
		going_list_more:'',
		going_list_more_flag:false,
		/*post_wish_list:[
				{"name":"suman",
				"image":"http://grid.digiopia.in/uploads/profile/thumb/6905ec7d969fb3c1f25cfb12165cfd8d.jpg"
				},
				{"name":"malay",
				"image":"http://grid.digiopia.in/uploads/profile/thumb/4fddb3caafffde3bc40e8e7369f2797a.jpg"
				},
				{"name":"subhro",
				"image":"http://grid.digiopia.in/uploads/profile/thumb/e63899c58be72332128e2aad946cda95.jpg.jpg"
				},
				{"name":"joy",
				"image":"http://grid.digiopia.in/uploads/profile/thumb/6905ec7d969fb3c1f25cfb12165cfd8d.jpg"
				},
				{"name":"sham",
				"image":"http://grid.digiopia.in/uploads/profile/thumb/6905ec7d969fb3c1f25cfb12165cfd8d.jpg"
				}]*/
		post_wish_list:[],
		post_wish_dispaly_list:[],
		wish_list:false,
		empty_wish_list_txt:"No one Yet Going...",
		wish_list_name_txt:'',
		wish_list_more:'',
		wish_list_more_flag:false,
		formated_date:''
	}
	var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
	
	$scope.postOwnerResponse='';
	
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
		$ionicLoading.show({
								template: '<ion-spinner icon="android"></ion-spinner>'
		});
		
		var link = 'http://grid.digiopia.in/user/view_event_owner';
							$http.post(link, {
									user_id : ControVarAccessFac.getData("user_ID"),						 							 									event_id: ControVarAccessFac.getData("post_ID")
							},config).then(function (res){
								$ionicLoading.hide();
								$scope.postOwnerResponse=res.data.response;
								$scope.postOwner.post_wish_list=[];
								$scope.postOwner.post_wish_list=$scope.postOwnerResponse.is_join;
								$scope.postOwner.formated_date=$scope.formateDate($scope.postOwnerResponse.formated_date)
								ControVarAccessFac.setData("wishList",$scope.postOwner.post_wish_list);
								ControVarAccessFac.setData("availableCount",$scope.postOwnerResponse.availableperson);
								console.log("availableCount:: "+ControVarAccessFac.getData("availableCount"))
				if($scope.postOwner.post_wish_list==''){
					$scope.postOwner.wish_list=false;
				}else{
					$scope.postOwner.wish_list=true;
					$scope.postOwner.wish_list_name_txt='';
					console.log("length::"+$scope.postOwner.post_wish_list.length)
					if($scope.postOwner.post_wish_list.length>5){
								$scope.postOwner.wish_list_more_flag=true;
								$scope.postOwner.post_wish_dispaly_list=[];
						for(var i=0;i<5;i++){
						$scope.postOwner.post_wish_dispaly_list[i]=$scope.postOwner.post_wish_list[i]
							if(i<=3){
								$scope.postOwner.wish_list_name_txt+=$scope.postOwner.post_wish_list[i].fullname.split(" ")[0]+",  								";
							}else{
							$scope.postOwner.wish_list_name_txt+=$scope.postOwner.post_wish_list[i].fullname.split(" ")[0]+"... ";
							}
						}
						$scope.postOwner.wish_list_more=$scope.postOwner.post_wish_list.length-5
					}else{
						$scope.postOwner.post_wish_dispaly_list=[];
						$scope.postOwner.post_wish_dispaly_list=$scope.postOwner.post_wish_list;
						$scope.postOwner.wish_list_more_flag=false;
						for(var i=0;i<$scope.postOwner.post_wish_dispaly_list.length;i++){
							
								if(i<$scope.postOwner.post_wish_dispaly_list.length-1){
									$scope.postOwner.wish_list_name_txt+=$scope.postOwner.post_wish_list[i].fullname.split(" ")[0]+", ";
								}else{
								$scope.postOwner.wish_list_name_txt+=$scope.postOwner.post_wish_list[i].fullname.split(" ")[0]+" ";
								}
							}
						
					}
				}
								
				$scope.postOwner.post_going_list=$scope.postOwnerResponse.is_going;
				ControVarAccessFac.setData("goingList",$scope.postOwner.post_going_list);
				if($scope.postOwner.post_going_list==''){
					$scope.postOwner.going_list=false;
				}else{
					$scope.postOwner.going_list=true;
					$scope.postOwner.going_list_name_txt="";
						if($scope.postOwner.post_going_list.length>5){
								$scope.postOwner.going_list_more_flag=true;
								$scope.postOwner.post_going_dispaly_list=[];
						for(var i=0;i<5;i++){
						$scope.postOwner.post_going_dispaly_list[i]=$scope.postOwner.post_going_list[i]
							if(i<=3){
								$scope.postOwner.going_list_name_txt+=$scope.postOwner.post_going_list[i].fullname.split(" ")[0]+",  								";
							}else{
							$scope.postOwner.going_list_name_txt+=$scope.postOwner.post_going_list[i].fullname.split(" ")[0]+"... ";
							}
						}
						$scope.postOwner.going_list_more=$scope.postOwner.post_going_list.length-5
					}else{
						$scope.postOwner.post_going_dispaly_list=[];
						$scope.postOwner.post_going_dispaly_list=$scope.postOwner.post_going_list;
						$scope.postOwner.going_list_more_flag=false;
						for(var i=0;i<$scope.postOwner.post_going_dispaly_list.length;i++){
						
							if(i<$scope.postOwner.post_going_dispaly_list.length-1){
								$scope.postOwner.going_list_name_txt+=$scope.postOwner.post_going_list[i].fullname.split(" ")[0]+",  								";
							}else{
							$scope.postOwner.going_list_name_txt+=$scope.postOwner.post_going_list[i].fullname.split(" ")[0]+" ";
							}
						}
					}
				}
								
								
								//$scope.setApproveStatus();
								//console.log($scope.postOwnerResponse)
							},function (error){
								$ionicLoading.hide();
								//alert("Post error.."+error)
								$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							})
		
	});
	
	$scope.openPostWantJoinView =function(){
		$state.go('main.post-want-join-view');
	}
	
	$scope.openPostWantGoingView =function(){
		$state.go('main.post-going-view');
	}
	$scope.formateDate=function(dateStr){
		var strLength=dateStr.length;
		 return dateStr.slice(0,strLength-4);
	}
})