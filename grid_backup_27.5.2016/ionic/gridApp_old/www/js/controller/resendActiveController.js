gridApp.controller('ResendActivateCtrl',function($scope,$state,$http,$ionicPopup,$ionicLoading){
												 
		$scope.resendActive = {
				email: ''
				
			};
		$scope.cancel = function() {
            $state.go('account-activate');
        }
		var md5_hash = calcMD5('admin'+'GRID API');
			var config = {
                headers : {
					'Client-Service':'GRIDAPP-CLIENT',
					'Auth-Key':md5_hash,
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
		/* ===Implementation for the resendActivation code Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:resendActiovation
				Parameter: None
				Return :None
				Uses:to send the new activation code to the user mail 
				Function Called From: resendActivForm form submission.
				Function Developed By :Suman Ghosh
				Date:01/13/2016
				@@@@@@@@@@@@@@@@
			*/
			$scope.resendActiovation = function(form) {
					if(form.$valid){
						//$state.go('main.home');email,password
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
						var link = 'http://grid.digiopia.in/user/resend_activate';
						$http.post(link, {email : $scope.resendActive.email},config
								  ).then(function (res){
											  //$scope.registerResponse=JSON.stringify(res);
											  $ionicLoading.hide();
												if(res.data.status==true){
													$ionicPopup.alert({
													  title: 'Success',
													  content: 'An activation code has been sent to your registered mail id. Please check your mail to activate your account.'
													})
													$state.go('account-activate');
												}else{
													$ionicPopup.alert({
													  title: 'activation code Fail',
													  content: res.data.message
													})
													$scope.forgetPass.email="";
													
												}
									},function (error){
										$ionicLoading.hide();
									//alert("activation code error.."+error)
									$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
								});
				 }
			}
	  /* ===Implementation for resendActivation code end===*/
})