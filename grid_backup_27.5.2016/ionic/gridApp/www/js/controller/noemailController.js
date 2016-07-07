gridApp.controller('NoEmailCtrl',function($scope,$state,$http,$ionicPopup,ControVarAccessFac,$ionicLoading){
		
			/* no email Model*/
			$scope.noSocialEmail = {
				email: ''				
			};
		
		
			$scope.cancel = function() {
				$state.go('login');
			}
			
			var md5_hash = calcMD5('admin'+'GRID API');
			var config = {
                headers : {
					'Client-Service':'GRIDAPP-CLIENT',
					'Auth-Key':md5_hash,
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
		/* ===Implementation for the socialEmailSend Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:forgetPassSend
				Parameter: None
				Return :None
				Uses:tomake user login
				Function Called From: forget password form submission.
				Function Developed By :Suman Ghosh
				Date:01/13/2016
				@@@@@@@@@@@@@@@@
			*/
			$scope.socialEmailSend = function(form) {
					if(form.$valid){
						//$state.go('main.home');email,password
						ControVarAccessFac.setData("userID",$scope.noSocialEmail.email);
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
						var link = 'http://grid.digiopia.in/user/email_update_social';
						$http.post(link, {email : $scope.noSocialEmail.email,social_id:ControVarAccessFac.getData("userSocialID")},config
								  ).then(function (res){
											  //$scope.registerResponse=JSON.stringify(res);
											  $ionicLoading.hide();
												if(res.data.status==true){
													//$state.go('main.import-contact-menu');
													$state.go('interests');
												}else{
													$ionicPopup.alert({
													  title: 'No email  Fail',
													  content: res.data.message
													})
													$scope.forgetPass.email="";
													
												}
									},function (error){
										$ionicLoading.hide();
									//alert("Forget Password error.."+error)
									$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
								});
				 }
			}
	  /* ===Implementation for the socialEmailSend End===*/
})