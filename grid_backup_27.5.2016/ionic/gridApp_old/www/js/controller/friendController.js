gridApp.controller('FriendCtrl',function($scope,$state,$ionicLoading,$http,ControVarAccessFac,$ionicTabsDelegate,$ionicPopup,$ionicPlatform,$cordovaGeolocation){
										 
		$scope.friend={
			userLat:'',
			userLong:'',
			mapViewFriend:'',
			markersArr:[],
			mapViewMarkerIconArr:[],
			reault_event_latArr:[],
			reault_event_longArr:[],
			circle:'',
			radius:'10',
			mapViewFriend:'',
			myFriendFlag:true,
			requestFlag:true,
			pendingFlag:true,
			nearbyFlag:true
		}
		$scope.friendRequestRes='';
		$scope.friendPendingRes='';
		$scope.friendListRes='';
		$scope.friendNearByListRes='';
		var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
		$scope.$on('$ionicView.enter', function (viewInfo, state) {
			//$ionicTabsDelegate.select(0);
			//$ionicTabsDelegate.$getByHandle('friend-tabs').select(0)
			//$scope.onFriendStepOneTabSelct();
		});
		
		/*=========MyFriend TAB Start ===============*/
		
			$scope.onFriendStepOneTabSelct=function(){
				
				$ionicLoading.show({
							template: '<ion-spinner icon="android"></ion-spinner>'
						});
					var link = 'http://grid.digiopia.in/user/friend_accept_list';
					$http.post(link,{user_id:ControVarAccessFac.getData("user_ID")
								 
								 },config).then(function (res){	
										  //$scope.showSuccessPopup('Profile data save successfully')
											
										  $scope.friendListRes=res.data.response;
										  if($scope.friendListRes.length>0){
											  $scope.friend.myFriendFlag=true
										  }else{
											  $scope.friend.myFriendFlag=false
										  }
										  $ionicLoading.hide();
										
								},function (error){
											$ionicLoading.hide();
											$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
								});
				
			}
			
			$scope.openOtherProfileFriend= function(index){
				ControVarAccessFac.setData("other_user_ID",$scope.friendListRes[index].user_id);
				
				$state.go('main.profile-other');
		   }
		   
		   $scope.openChatLink= function(index){
				//ControVarAccessFac.setData("other_user_ID",$scope.friendListRes[index].user_id);
				//
				
				ControVarAccessFac.setData("other_user_ID",$scope.friendListRes[index].user_id);
				$state.go('main.chat');
		   }
		   
		   //
		/*=========MyFriend TAB End ===============*/
		
		
		
		/*=========Request TAB Start ===============*/
		
		$scope.onFriendStepTwoTabSelct=function(){
					$ionicLoading.show({
						template: '<ion-spinner icon="android"></ion-spinner>'
					});
				var link = 'http://grid.digiopia.in/user/friend_request_list';
				$http.post(link,{user_id:ControVarAccessFac.getData("user_ID")							 
							 	},
							 config
							).then(function (res){	
									  //$scope.showSuccessPopup('Profile data save successfully')
									 	
									  $scope.friendRequestRes=res.data.response;
									  if($scope.friendRequestRes.length>0){
											  $scope.friend.requestFlag=true
										  }else{
											  $scope.friend.requestFlag=false
										  }
									  $ionicLoading.hide();
									
							},function (error){
										$ionicLoading.hide();
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							});
		}
		
		
		$scope.acceptRequest=function(index){
			
			var uniqueID=$scope.friendRequestRes[index].unique_id;
			
			$ionicLoading.show({
						template: '<ion-spinner icon="android"></ion-spinner>'
					});
			
			var link = 'http://grid.digiopia.in/user/friend_request_accept';
				$http.post(link,{
							 	 unique_id:uniqueID,
								 relation_status:'accept'
							 },
							 config
							).then(function (res){	
									  //$scope.showSuccessPopup('Profile data save successfully')
									 	
									  //$scope.friendPendingRes=res.data.response;
									  $scope.friendRequestRes.splice(index,1);
									 
									  $ionicLoading.hide();
									
							},function (error){
										$ionicLoading.hide();
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							});
		
		}
		
		
		$scope.declineRequest=function(index){
			
			var uniqueID=$scope.friendRequestRes[index].unique_id;
			
			$ionicLoading.show({
						template: '<ion-spinner icon="android"></ion-spinner>'
					});
			
			var link = 'http://grid.digiopia.in/user/friend_request_accept';
				$http.post(link,{
							 	 unique_id:uniqueID,
								 relation_status:'decline'
							 },
							 config
							).then(function (res){	
									  //$scope.showSuccessPopup('Profile data save successfully')
									 	
									  //$scope.friendPendingRes=res.data.response;
									  $scope.friendRequestRes.splice(index,1);
									  
									  $ionicLoading.hide();
									
							},function (error){
										$ionicLoading.hide();
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							});
			
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
				 if(joinStatus=='accept'){
				 	$scope.acceptRequest(index)
				 }else if(joinStatus=='decline'){
					 $scope.declineRequest(index)
				 }else if(joinStatus=='pendingcancel'){
					 $scope.cancelPending(index)
				 }
			   //console.log('You are sure');
			 } else {
			   //console.log('You are not sure');
			 }
		   });
		 };
		
		
		
		
		/*=========Request TAB End ===============*/
		
		
		
		/*=========Pending TAB Start ===============*/	
		
				$scope.onFriendStepThreeTabSelct=function(){
							$ionicLoading.show({
								template: '<ion-spinner icon="android"></ion-spinner>'
							});
						var link = 'http://grid.digiopia.in/user/friend_pending_list';
						$http.post(link,{user_id:ControVarAccessFac.getData("user_ID")									 
									 },
									 config
									).then(function (res){	
											  //$scope.showSuccessPopup('Profile data save successfully')
												
											  $scope.friendPendingRes=res.data.response;
											  if($scope.friendPendingRes.length>0){
											  	$scope.friend.pendingFlag=true
										 	  }else{
											    $scope.friend.pendingFlag=false
										      }
											  $ionicLoading.hide();
											
									},function (error){
												$ionicLoading.hide();
												$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
									});
				}
				$scope.openOtherProfilePending= function(index){
					ControVarAccessFac.setData("other_user_ID",$scope.friendPendingRes[index].user_id);
					
					$state.go('main.profile-other');
			   }
			   
			   $scope.cancelPending=function(index){
					
					var uniqueID=$scope.friendPendingRes[index].unique_id;
					
					$ionicLoading.show({
								template: '<ion-spinner icon="android"></ion-spinner>'
							});
					
					var link = 'http://grid.digiopia.in/user/friend_request_accept';
						$http.post(link,{
										 unique_id:uniqueID,
										 relation_status:'decline'
									 },
									 config
									).then(function (res){	
											  //$scope.showSuccessPopup('Profile data save successfully')
												
											  //$scope.friendPendingRes=res.data.response;
											  $scope.friendPendingRes.splice(index,1);
											  
											  $ionicLoading.hide();
											
									},function (error){
												$ionicLoading.hide();
												$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
									});
					
				}
				$scope.openOtherProfileRequest= function(index){
					ControVarAccessFac.setData("other_user_ID",$scope.friendRequestRes[index].user_id);
					
					$state.go('main.profile-other');
			   }
		/*=========Pending TAB End ===============*/	
		
		
		/*=========NearBy  TAB Start ===============*/
		
		$scope.onFriendStepfourTabSelct=function(){
					$ionicLoading.show({
						template: '<ion-spinner icon="android"></ion-spinner>'
					});
					var link = 'http://grid.digiopia.in/user/friend_position';
					$http.post(link,{user_id:ControVarAccessFac.getData("user_ID"),
							 		radius:$scope.friend.radius,
									post_lat : ControVarAccessFac.getData("userLat"),						 							 									post_long: ControVarAccessFac.getData("userLang")
									
							 },config
							 ).then(function (res){	
									  //$scope.showSuccessPopup('Profile data save successfully')
									 	
									 
									  $ionicLoading.hide();
									  if(res.data.status==true){										
										if(res.data.response!=''){
											$scope.friendNearByListRes=res.data.response;
											$scope.friend.reault_event_latArr=[];
											$scope.friend.reault_event_longArr=[];											
									     for(var i=0;i<$scope.friendNearByListRes.length;i++){											
											$scope.friend.reault_event_latArr[i]=Number($scope.friendNearByListRes[i].post_lat)
											$scope.friend.reault_event_longArr[i]=Number($scope.friendNearByListRes[i].post_long)
										$scope.createNearByFriendMapView();
											}
											
										}else{
											$ionicPopup.alert({
												  title: 'Oops..!!!',
												  content: "No Post Record Found...!!"
												})
										}
									
									}else{
										$ionicPopup.alert({
										title: 'Friend Fail',
												  content: res.data.message
										})
												//$scope.register.email="";
									}
									  
									  
									  
									  
									  
									
							},function (error){
										$ionicLoading.hide();
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							}
					);
					
		}
		
		
		$scope.createNearByFriendMapView =function(){
			
			
				//$scope.post.mapViewPost
			$ionicPlatform.ready(function() {
					
					$ionicLoading.show({
					template: '<ion-spinner icon="bubbles"></ion-spinner><br/>Acquiring location!'
					});
											  
					var posOptions = {
						enableHighAccuracy: true,
						timeout: 20000,
						maximumAge: 0
					};
					
				$cordovaGeolocation.getCurrentPosition(posOptions).then(function (position) {
						
						$scope.friend.userLat  = position.coords.latitude;
						$scope.friend.userLong = position.coords.longitude;
						console.log($scope.friend.userLat+">>>>"+$scope.friend.userLong)
					var myLatlng = new google.maps.LatLng($scope.friend.userLat,$scope.friend.userLong);
						var mapOptions = {
										center: myLatlng,
										zoom: 15,
										mapTypeId: google.maps.MapTypeId.ROADMAP
						};          
						 
		$scope.friend.mapViewFriend = new google.maps.Map(document.getElementById("friendMapView"), mapOptions);
		console.log("$scope.friend.mapViewFriend:: "+$scope.friend.mapViewFriend)
						$ionicLoading.hide(); 
						
						var  man_marker = new google.maps.Marker({
									  map: $scope.friend.mapViewFriend,									 
									  position: {lat: $scope.friend.userLat, lng: $scope.friend.userLong},
									  title: 'I am here..',
									  icon: 'img/nevigator_man.png'
						});			  
																				  
						if($scope.friend.markersArr.length!=0){
							for(var i=0;i<$scope.friend.markersArr.length;i++){
								$scope.friend.markersArr[i].setMap(null);
							}
							//$scope.friend.circle.setMap(null)
						}
						$scope.friend.markersArr=[];
						var marker;
				
					for(var j=0;j<$scope.friendNearByListRes.length;j++){
									
									marker = new google.maps.Marker({
										map: $scope.friend.mapViewFriend,									 
										position: {lat: $scope.friend.reault_event_latArr[j], lng: $scope.friend.reault_event_longArr[j]},
									  
									  	icon: 'img/friend.png'
									});
									
									
									var contentString = '<div id="content">'+
									  '<div id="siteNotice">'+
									  '</div>'+
									  '<img src="http://grid.digiopia.in/uploads/profile/thumb/'+$scope.friendNearByListRes[j].image+'" class="rounded_info">'+'<h5 class="info_name" class="firstHeading">'+$scope.friendNearByListRes[j].fullname+'</h5>'+
									  '</div>';
									 marker.content=contentString; 
									 
									  var infoWindow = new google.maps.InfoWindow();
									
									
									google.maps.event.addListener(marker, 'click', function () {
                                		infoWindow.setContent(this.content);
                                			infoWindow.open($scope.friend.mapViewFriend, this);
                           			});
									
									
									
									
									$scope.friend.markersArr[j]=marker;
				   }
				
				// Add circle overlay and bind to marker
				$scope.friend.circle = new google.maps.Circle({
								  map: $scope.friend.mapViewFriend,
								  radius: 10000,    // metres
								  strokeColor: '#666666',
      							  strokeOpacity: 0.8,
								  strokeWeight: 2,
								  fillColor: '#fbeded',
								  fillOpacity: 0.15,

				});
				//$scope.friend.circle.bindTo('center', man_marker, 'position');														  
				});
				
		   });
			
		}
	/*=========NearBy  TAB End ===============*/
	
	
	
		
});