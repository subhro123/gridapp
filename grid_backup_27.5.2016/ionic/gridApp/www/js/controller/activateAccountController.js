gridApp.controller('ActiveAccountCtrl',function($scope,$state,$cordovaDevice,$http,$ionicPopup,$ionicLoading,$timeout,$rootScope,ControVarAccessFac,$interval,$cordovaGeolocation,$ionicPlatform){
		$scope.cancel = function() {
            $state.go('login');
        }
		$scope.accountActivate = {
			activationEmail:'',
			activationCode:'',
			userLat:'',
			userLang:''
		}
		
		if($cordovaDevice.getPlatform()=="android" ||$cordovaDevice.getPlatform()=="ios"){
			$scope.uuid = $cordovaDevice.getUUID();
			$scope.model = $cordovaDevice.getModel();
   			$scope.platform = $cordovaDevice.getPlatform();
		}else{
			$scope.uuid = '';
			$scope.model = '';
   			$scope.platform = '';
		}
		/*
			$scope.uuid = '';
			$scope.model = '';
   		*/	$scope.platform = '';
		
		var md5_hash = calcMD5('admin'+'GRID API');
			var config = {
                headers : {
					'Client-Service':'GRIDAPP-CLIENT',
					'Auth-Key':md5_hash,
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
		
		/*$scope.uuid = $cordovaDevice.getUUID();
		$scope.model = $cordovaDevice.getModel();
   		$scope.platform = $cordovaDevice.getPlatform();*/
		
		/* ===Implementation for the user Account Activation start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:activateAccount
				Parameter: None
				Return :None
				Uses:To call user activation web service for account activation
				Function Developed By :Suman Ghosh
				Date:01/12/2016
				@@@@@@@@@@@@@@@@
			*/
			$scope.activateAccount = function(form) {
				if(form.$valid){
					$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
					var link = 'http://grid.digiopia.in/user/activate';
					$http.post(link, {email : $scope.accountActivate.activationEmail,
									  activationcode : $scope.accountActivate.activationCode,
									  UUID : $scope.uuid,
									  model : $scope.model,
									  platform : $scope.platform								  
								},config).then(function (res){
												$ionicLoading.hide();
												
											  $scope.registerResponse=JSON.stringify(res);
											 // alert("Activation succes.."+res.data.message)
											  //alert("Registration succes.."+$scope.registerResponse.message)
											//$scope.response = res.data;
											 
											if(res.data.status==200){
												/*$ionicPopup.alert({
												  title: 'Success',
												  content: 'Your Account activated successfully.'
												})*/
												//$scope.showSuccessPopup('Please login to use GRID app')
												
									ControVarAccessFac.setData("userID",$scope.accountActivate.activationEmail);
									ControVarAccessFac.setData("loginType",res.data.response.loginType);
									ControVarAccessFac.setData("user_ID",res.data.response.token);
									ControVarAccessFac.setData("token",res.data.response.token);
									ControVarAccessFac.setData("user_subscription",res.data.response.user_subscription);
									ControVarAccessFac.setData("min_map_radious",res.data.response.min_radius);
									ControVarAccessFac.setData("max_map_radious",res.data.response.max_radius);
									ControVarAccessFac.setData("map_radious",res.data.response.max_radius);
									ControVarAccessFac.setData("gridusers",res.data.response.gridusers);
									
									ControVarAccessFac.setData("createpost_tab",0)
									ControVarAccessFac.setData("post_tab",0);
									if(ControVarAccessFac.getData("user_subscription")=="paiduser"){
										ControVarAccessFac.setData("rangePaidFlag",true);
										ControVarAccessFac.setData("rangeFreeFlag",false);
									}else{
										ControVarAccessFac.setData("rangePaidFlag",false);
										ControVarAccessFac.setData("rangeFreeFlag",true);
									}
									ControVarAccessFac.setData("payment_type","subscription");
										
									$scope.accountActivate.activationEmail='';
									$scope.accountActivate.activationCode='';
									$scope.setUserUpdatdPosition();
									$rootScope.updatePosition=$interval($scope.setUserUpdatdPosition,60000)
										  		 $state.go('interests');
											}else{
												$ionicPopup.alert({
												  title: 'Account Activation Fail',
												  content: res.data.message
												})
												$scope.accountActivate.activationCode="";
											}
											  
										},function (error){
											$ionicLoading.hide();
											//alert("Activation error.."+error)
											$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
								});
				}
			}
			
			/* success message pop up */
			   $scope.showSuccessPopup = function(txtMsg) {
				   
					$scope.data = {};
			
			  // An elaborate, custom popup
			  var myPopup = $ionicPopup.show({
				template: '',
				title: 'Account Activated successfully.',
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
			 
			 /*=========update logedin user position every 1 min after login this function now only call from simple login..later we have to update for FB and twitter login==== */
		
		$scope.setUserUpdatdPosition= function(){
			console.log("set user position.....")
			$ionicPlatform.ready(function() {
					var posOptions = {
						enableHighAccuracy: true,
						timeout: 20000,
						maximumAge: 0
					};
					$cordovaGeolocation.getCurrentPosition(posOptions).then(function (position) {
						$scope.accountActivate.userLat  = position.coords.latitude;
						$scope.accountActivate.userLang = position.coords.longitude;
						ControVarAccessFac.setData("userLat",$scope.accountActivate.userLat);
						ControVarAccessFac.setData("userLang",$scope.accountActivate.userLang);
						var config = {
							headers : {
								'Authorizations':ControVarAccessFac.getData("token") ,
								'User-Id':ControVarAccessFac.getData("user_ID"),
								'Content-Type': 'application/json; charset=utf-8'
							}
						}
						var link = 'http://grid.digiopia.in/user/user_position';
						$http.post(link,{user_id:ControVarAccessFac.getData("user_ID"),
									 post_lat:$scope.accountActivate.userLat,
									 post_long:$scope.accountActivate.userLang
							 },config
							).then(function (res){	
									  //$http.defaults.withCredentials = true;
									  console.log(res.data.response)
									
							},function (error){
										$ionicLoading.hide();
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							});
					});
			
					
			});
		}
			 
			 
		/* ===Implementation for the Account Activation end===*/
})