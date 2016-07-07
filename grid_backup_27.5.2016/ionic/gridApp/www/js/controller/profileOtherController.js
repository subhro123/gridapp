gridApp.controller('ProfileOtherCtrl',function($scope,$state,$filter,$http,ControVarAccessFac,$ionicLoading,$ionicPopup,$ionicPlatform,$cordovaCamera,$cordovaDevice,$cordovaFile,$ionicActionSheet,$cordovaFileTransfer,$cordovaImagePicker,$ionicModal,$ionicSlideBoxDelegate,$cordovaDatePicker,$timeout){
										   
	/**
		Variavle delcaration for the Interest 
	**/	
	
	$scope.profile={
		capProImage:'',
		profileImage:'',
		user_id:ControVarAccessFac.getData("other_user_ID"),
		addFriendStatus:true,
		relationStatus:'',
		relationTxt:'',
		capBackGroundImage:'',
		proBackgndImageEdit:false,
		maxNumberProBackgndImage:3,
		proBackgndImage:'',
		backgroundImage:["img/banner_slide1.jpg","img/banner_slide2.jpg","img/banner_slide3.jpg"],
		backgndImgTypeId:["cover_pic_1","cover_pic_2","cover_pic_3"],
		selectedBgIndex:0,
		profileRespons:{},
		fullname:'',
		description:'',
		email:'',
		gender:'',
		dob:'',
		occupation:'',
		phone:'',
		interest:[],
		deletInterest:[],
		fb_link:'',
		tw_link:'',
		linkin_link:'',
		insta_link:'',
		friend_count:'',
		event_count:''
	}
	$scope.profileRespons='';
	var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
	
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
			//
			$ionicSlideBoxDelegate.start();
		$ionicPlatform.ready(function() {
			$ionicLoading.show({
				template: '<ion-spinner icon="android"></ion-spinner>'
			});
			var link = 'http://grid.digiopia.in/user/profile_display';
			$http.post(link, {
						sender_id : ControVarAccessFac.getData("user_ID"),
						receiver_id : ControVarAccessFac.getData("other_user_ID")
						
					},config).then(function (res){
							$ionicLoading.hide();
							if(res.data.status==true){
								$scope.profileRespons=res.data.response;
								/*console.log($scope.profileRespons.id)
								console.log($scope.profileRespons.fullname)
								console.log($scope.profileRespons.email)*/
								$scope.profile.description=$scope.profileRespons.description;
								$scope.profile.email=$scope.profileRespons.email;
								$scope.profile.relationStatus=$scope.profileRespons.relation_status;
								if($scope.profile.relationStatus==''||$scope.profile.relationStatus=='decline'){
									$scope.profile.relationTxt='Add friend';
									$scope.profile.addFriendStatus=true;
								}else if($scope.profile.relationStatus=='request'){
									$scope.profile.relationTxt='Friend request sent';
									$scope.profile.addFriendStatus=false;
								}else if($scope.profile.relationStatus=='accept'){
									$scope.profile.relationTxt='Friend';
									$scope.profile.addFriendStatus=false;
								}
								$scope.profile.fullname=$scope.profileRespons.fullname;
								/*if($scope.profileRespons.gender=="M"){
									$scope.profileRespons.gender="Male";
								}else{
									$scope.profileRespons.gender="Female";
								}*/
								$scope.profile.gender=$scope.profileRespons.gender;
								$scope.profile.dob=$scope.profileRespons.dob;
								$scope.profile.occupation=$scope.profileRespons.occupation;
								$scope.profile.phone=$scope.profileRespons.phone;
								$scope.profile.interest=$scope.profileRespons.interest;
								$scope.profile.friend_count=$scope.profileRespons.friend_count;
								$scope.profile.event_count=$scope.profileRespons.event_count;
								
								$scope.profile.fb_link=$scope.profileRespons.fb_link;
								$scope.profile.tw_link=$scope.profileRespons.tw_link;
							 	$scope.profile.linkin_link=$scope.profileRespons.linkin_link;
								$scope.profile.insta_link=$scope.profileRespons.insta_link;
							 	$scope.profile.deletInterest=$scope.profileRespons.event_count;
								
								if($scope.profileRespons.image!=''){
								$scope.profile.profileImage="http://grid.digiopia.in/uploads/profile/thumb/"+$scope.profileRespons.image;
								}else{
									$scope.profile.profileImage="img/pro01.jpg"
								}
								
								if($scope.profileRespons.cover_pic_1!=""){
									$scope.profile.backgroundImage[0]="http://grid.digiopia.in/uploads/background/thumb/"+$scope.profileRespons.cover_pic_1
								}else{
									$scope.profile.backgroundImage[0]="img/banner_slide1.jpg"
								}
								if($scope.profileRespons.cover_pic_2!=""){
									$scope.profile.backgroundImage[1]="http://grid.digiopia.in/uploads/background/thumb/"+$scope.profileRespons.cover_pic_2
								}else{
									$scope.profile.backgroundImage[1]="img/banner_slide2.jpg"
								}
								if($scope.profileRespons.cover_pic_3!=""){
									$scope.profile.backgroundImage[2]="http://grid.digiopia.in/uploads/background/thumb/"+$scope.profileRespons.cover_pic_3
								}else{
									$scope.profile.backgroundImage[2]="img/banner_slide3.jpg"
								}
								
								//$scope.populateProfile();
							}else{
								$ionicPopup.alert({
									title: 'Post Fail',
									content: res.data.message
								})
							}
					},function (error){
								$ionicLoading.hide();
								//alert("Post error.."+error)
								$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
					});
		});
	});
	
	
	/* save and edit profile */
	$scope.addFriend=function(){
		$ionicLoading.show({
				template: '<ion-spinner icon="android"></ion-spinner>'
			});
		var link = 'http://grid.digiopia.in/user/friend_request_accept';
			$http.post(link,{sender_id:ControVarAccessFac.getData("user_ID"),
							 receiver_id:ControVarAccessFac.getData("other_user_ID"),
							 },
							 config
							).then(function (res){	
									  //$scope.showSuccessPopup('Profile data save successfully')
									  //alert(JSON.stringify(res.data.response))
									  //$scope.profile.profileEditTxt=res.data.response.status;
									  // $scope.profile.addFriendStatus=false;
									   
									   $scope.profile.relationTxt='Friend request sent';
									   $scope.profile.addFriendStatus=false;
									   
									  $ionicLoading.hide();
									
							},function (error){
										$ionicLoading.hide();
										//alert("get Interest error.."+error)
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							});
			
		
		
		
		
	}
	
	/*open link*/
	$scope.openLink=function(type){
		if(type=="fb"){
			window.open($scope.profile.fb_link,'_system','location=yes');return false;
		}else if(type=="tw"){
			window.open($scope.profile.tw_link,'_system','location=yes');return false;
		}else if(type=="in"){
			window.open($scope.profile.linkin_link,'_system','location=yes');return false;
		}else if(type=="ins"){
			window.open($scope.profile.insta_link,'_system','location=yes');return false;
		}
	}
	
	
   
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