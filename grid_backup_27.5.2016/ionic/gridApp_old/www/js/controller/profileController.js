gridApp.controller('ProfileCtrl',function($scope,$state,$filter,$http,ControVarAccessFac,$ionicLoading,$ionicPopup,$ionicPlatform,$cordovaCamera,$cordovaDevice,$cordovaFile,$ionicActionSheet,$cordovaFileTransfer,$cordovaImagePicker,$ionicModal,$ionicSlideBoxDelegate,$cordovaDatePicker,$timeout){
										   
	/**
		Variavle delcaration for the Interest 
	**/	
	
	$scope.profile={
		capProImage:'',
		profileImage:'',
		user_id:ControVarAccessFac.getData("user_ID"),
		profileEdit:false,
		profileEditTxt:'Edit Profile',
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
		event_count:'',
		contrycode:''
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
						receiver_id : ''
						
					},config).then(function (res){
							$ionicLoading.hide();
							//$http.defaults.withCredentials = true;
							if(res.data.status==true){
								$scope.profileRespons=res.data.response;
								/*console.log($scope.profileRespons.id)
								console.log($scope.profileRespons.fullname)
								console.log($scope.profileRespons.email)*/
								$scope.profile.description=$scope.profileRespons.description;
								$scope.profile.fullname=$scope.profileRespons.fullname
								$scope.profile.email=$scope.profileRespons.email;
								
								if($scope.profileRespons.gender=="M"){
									$scope.profileRespons.gender="Male";
								}else{
									$scope.profileRespons.gender="Female";
								}
								$scope.profile.gender=$scope.profileRespons.gender;
								$scope.profile.dob=$scope.profileRespons.dob;
								$scope.profile.occupation=$scope.profileRespons.occupation;
								$scope.profile.phone=$scope.profileRespons.phone;
								$scope.profile.interest=$scope.profileRespons.interest;
								$scope.profile.friend_count=$scope.profileRespons.friend_count;
								$scope.profile.event_count=$scope.profileRespons.event_count;
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
								$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
					});
		});
	});
	
	
	/* save and edit profile */
	$scope.profile_edit_save=function(){
		$scope.profile.profileEdit=!$scope.profile.profileEdit;
		if($scope.profile.profileEdit){
			$scope.profile.profileEditTxt='Save Profile';
		}else{
			$scope.profile.profileEditTxt='Edit Profile';
			$ionicLoading.show({
				template: '<ion-spinner icon="android"></ion-spinner>'
			});
			var link = 'http://grid.digiopia.in/user/profile_save';
			$http.post(link,{user_id:ControVarAccessFac.getData("user_ID"),
							 description:$scope.profile.description,
							 gender:$scope.profile.gender,
							 dob : $scope.profile.dob,
							 occupation: $scope.profile.occupation,
							 phone:$scope.profile.phone,
							 fb_link:$scope.profile.fb_link,
							 tw_link:$scope.profile.tw_link,
							 linkin_link:$scope.profile.linkin_link,
							 insta_link:$scope.profile.insta_link,
							 interest:$scope.profile.deletInterest},
							 config
							).then(function (res){	
									  $scope.showSuccessPopup('Profile data save successfully')
									  $ionicLoading.hide();
									
							},function (error){
										$ionicLoading.hide();
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							});
			
		}
	}
	
	$scope.setProfileDOB= function() {
		console.log("setProfileDOB..called")
			var optionsPro = {
			date: new Date(),
			mode: 'date', // or 'time'
			/*minDate: new Date() - 10000,*/
			allowOldDates: true,
			allowFutureDates: false,
			doneButtonLabel: 'DONE',
			doneButtonColor: '#000000',
			cancelButtonLabel: 'CANCEL',
			cancelButtonColor: '#000000'
		  };
		  $cordovaDatePicker.show(optionsPro).then(function(date){
				$scope.profile.dob= $filter('date')(date, ' MM/dd/yyyy');
		  });
	}
	
	$scope.deletInterest= function(index) {
		$scope.profile.deletInterest.push($scope.profile.interest[index])
		$scope.profile.interest.splice(index,1);
		
	}
	
	$scope.addInterest= function(index) {
		$state.go('interests');
	}
	
	$scope.showPrompt = function(titleTxt,linkTxt,placeholderTxt) {	
      var promptPopup = $ionicPopup.prompt({
         title: titleTxt,
         template: linkTxt,
         inputType: 'text',
         inputPlaceholder: placeholderTxt
      });
        
      promptPopup.then(function(res) {
         console.log(res+">>>"+titleTxt);
		/* fb_link:'',
		tw_link:'',
		linkin_link:'',
		insta_link:''*/
		if(titleTxt=="facebook"&&res!=undefined&&res!=''){
			$scope.profile.fb_link="https://www.facebook.com/"+res
		}else if(titleTxt=="twitter"&&res!=undefined&&res!=''){
			$scope.profile.tw_link="https://www.twitter.com/"+res
		}else if(titleTxt=="linkedin"&&res!=undefined&&res!=''){
			$scope.profile.linkin_link="https://www.linkedin.com/"+res
		}else if(titleTxt=="instagram"&&res!=undefined&&res!=''){
			$scope.profile.insta_link="https://www.instagram.com/"+res
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
	
	
	/* open file*/
	
	$scope.openFile=function(){
		fileChooser.open(function(uri) {
				 alert(uri);
				//window.FilePath.resolveNativePath(uri, function(path){alert(path)}, errorCallback);
		});
	}
	
	/* Adding profile image*/
	
	$scope.addProfileImg= function(){
		console.log("$scope.profile.user_id::"+$scope.profile.user_id)
		 $scope.hideSheet = $ionicActionSheet.show({
		  buttons: [
			{ text: '<i class="icon ion-camera"></i> Take photo' },
			{ text: '<i class="icon ion-images"></i> Photo from library' }
		  ],
		  titleText: 'Add images',
		  cancelText: 'Cancel',
		  buttonClicked: function(index) {
			  if(index==0){
				$scope.captureProfileImage(index);
			  }else{
				$scope.selectProfileImage();
			  }
		  }
		});
	}
	$scope.captureProfileImage = function(type) {
		console.log("captureProfileImage")
		$scope.hideSheet();
		//$scope.getImage(type);
		var options = {
			  quality: 100,
			  destinationType: Camera.DestinationType.FILE_URI,
			  sourceType: Camera.PictureSourceType.CAMERA,
			  allowEdit: false,
			  encodingType: Camera.EncodingType.JPEG,
			  /*targetWidth: 100,
			  targetHeight: 100,*/
			  popoverOptions: CameraPopoverOptions,
			  saveToPhotoAlbum: false,
			  correctOrientation:true
   	 	};
		$cordovaCamera.getPicture(options).then(function(imageUrl) {
			var name = imageUrl.substr(imageUrl.lastIndexOf('/') + 1);
			var namePath = imageUrl.substr(0, imageUrl.lastIndexOf('/') + 1);
			//$scope.profile.profileImage=imageUrl;
			// alert("getImage called"+imageUrl)
			$scope.profile.capProImage=imageUrl;
			$scope.saveProfileImg();
      	});
		
 	}
	$scope.selectProfileImage=function(){
		//alert("selectProfileImage")
		$scope.hideSheet();
		var options = {
  		 maximumImagesCount: 1,
  		 /*width: 800,
   		 height: 600,*/
   		 quality: 100
  		};
		$cordovaImagePicker.getPictures(options)
    			.then(function (results) {
      				
					
					if(results[0]==''||results[0]=='undefined'||results[0]==undefined){
						//alert("image NOT taken...")
					}else{
						//alert("image taken...")
						$scope.profile.capProImage=results[0];
						$scope.saveProfileImg();
					}
    			}, function(error) {
      				// error getting photos
					//alert("error:::"+error)
    	});
	}
	$scope.saveProfileImg= function(){
		$scope.profile.profileImageEdit=false;
	   	$ionicLoading.show({template: 'Saving Image...'});
		var fileURL = $scope.profile.capProImage;
		var options = new FileUploadOptions();
		options.fileKey = "file";
		options.fileName = fileURL.substr(fileURL.lastIndexOf('/') + 1);
		options.mimeType = "image/jpeg";
		options.chunkedMode = true;
		options.httpMethod = 'POST';		
		/*options.headers = {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
         }*/
		 
		var md5_hash = calcMD5('admin'+'GRID API');
		var params = {};
		//params.user_id = 'aaaa';
		params.user_id =ControVarAccessFac.getData("user_ID");
		params.Client_Service='GRIDAPP-CLIENT';
		params.Auth_Key= md5_hash;
		//alert('Client_Service=>'+params.Client_Service);
		//alert('Auth_Key=>'+params.Auth_Key);
       //params.value2 = "otherparams";

		options.params = params;

		var ft = new FileTransfer();
		ft.upload(fileURL, encodeURI("http://grid.digiopia.in/user/profile_image"), function(res){
		var resObj = JSON.parse(res.response)
		//alert(res.response)
		$scope.profile.profileImage=resObj.response.image_path;
		//alert(res.response);
		//alert(resObj.response.image_path)
		$scope.$apply();
		$ionicLoading.hide();
		 $scope.showSuccessPopup('Profile Image save successfully')
		},function(error) {$ionicLoading.show({template: 'Image not saved...'});
		$ionicLoading.hide();}, options);
	   
   }
	
	/* Adding Backbround Image */
	
	$scope.editProBackgndImg=function(index){
		console.log("selectBackgroundImage")
		//$scope.profile.proBackgndImageEdit=true;
		$scope.profile.selectedBgIndex=index;
		var options = {
  		 maximumImagesCount: 1,  		 
   		 quality: 100
  		};
		$cordovaImagePicker.getPictures(options)
    			.then(function (results) {
										
					if(results[0]==''||results[0]=='undefined'||results[0]==undefined){
						//alert("image NOT taken...")
					}else{
						//alert("image taken...")
						$scope.profile.capBackGroundImage=results[0];
						$scope.saveProBackgndImg();
					}
					
					
					
      				/*for (var i = 0; i < results.length; i++) {
       					 //alert('Image URI: ' + results[i]);
						 $scope.profile.capBackGroundImage[i]=results[i]
     			 	}*/
					
    			}, function(error) {
      				// error getting photos
    	});
	}
	
	
	$scope.saveProBackgndImg= function(){
		//$scope.closeModal();
		//$scope.profile.proBackgndImageEdit=false;
		
	   	$ionicLoading.show({template: 'Saving Image...'});
		
		var fileURL = $scope.profile.capBackGroundImage;
		
		var options = new FileUploadOptions();
		options.fileKey = $scope.profile.backgndImgTypeId[$scope.profile.selectedBgIndex];		
		options.fileName = fileURL.substr(fileURL.lastIndexOf('/') + 1);//array()
		options.mimeType = "image/jpeg";
		options.chunkedMode = true;
		options.httpMethod = 'POST';
		var md5_hash = calcMD5('admin'+'GRID API');
		var params = {};
		//params.user_id = 'aaaa';
		params.user_id = ControVarAccessFac.getData("user_ID");
		params.Client_Service='GRIDAPP-CLIENT';
		params.Auth_Key= md5_hash;
		//alert('Client_Service=>'+params.Client_Service);
	    //alert('Auth_Key=>'+params.Auth_Key);

		//alert(params.user_id)
       //params.value2 = "otherparams";

		options.params = params;
		
		var ft = new FileTransfer();
		ft.upload(fileURL, encodeURI("http://grid.digiopia.in/user/background_image"), function(res){
		var resObj = JSON.parse(res.response)
		//alert(res.response)
		
		$scope.profile.backgroundImage[$scope.profile.selectedBgIndex]=resObj.response[0].image_path;
		$ionicSlideBoxDelegate.update()
		$scope.$apply();
		 $scope.showSuccessPopup('Profile BG save successfully')
		$ionicLoading.hide();
		},function(error) {$ionicLoading.show({template: 'Image not saved...'});
		$ionicLoading.hide();}, options);
		
	   
   }
	
	
	/* opening modal for edit profile BG */
	
	
	$ionicModal.fromTemplateUrl('templates/modal/profile-bg-select-modal.html', {
    	scope: $scope,
    	animation: 'slide-in-up'
  	}).then(function(modal) {
    	$scope.modal = modal;
  	});
	  $scope.openModal = function() {
		$scope.modal.show();
	  };
	  $scope.closeModal = function() {
		$scope.modal.hide();
		/*if($scope.profile.capBackGroundImage!=undefined){
			$scope.saveProBackgndImg();
		}*/
	  };
	  //Cleanup the modal when we're done with it!
	  $scope.$on('$destroy', function() {
		$scope.modal.remove();
	  });
	  // Execute action on hide modal
	  $scope.$on('modal.hidden', function() {
		// Execute action
	  });
	  // Execute action on remove modal
	  $scope.$on('modal.removed', function() {
		// Execute action
	  });
	
	
	/*
	$ionicPlatform.ready(function() {
		$scope.images = FileService.images();
		$scope.$apply();
  	});
	
	$scope.urlForImage = function(imageName) {
   		 var trueOrigin = cordova.file.dataDirectory + imageName;
   		 return trueOrigin;
 	}
	*/
	
	
	$scope.openFriendList= function(){
		$state.go('main.friend');
	}
	
	$scope.openMyEvent= function(){
		ControVarAccessFac.setData("post_tab",2);
		$state.go('main.post');
	}
	
})