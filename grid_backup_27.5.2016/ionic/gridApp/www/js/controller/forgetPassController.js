gridApp.controller('ForgetPassCtrl',function($scope,$state,$http,$ionicPopup,$ionicLoading){
		
		/* login Model*/
			$scope.forgetPass = {
				email: ''
				
			};
		
			var md5_hash = calcMD5('admin'+'GRID API');
			var config = {
                headers : {
					'Client-Service':'GRIDAPP-CLIENT',
					'Auth-Key':md5_hash,
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
			
			$scope.cancelForgetpass = function() {
				console.log("cancelForgetpass")
				$state.go('login');
			}
		
		/* ===Implementation for the forgetPassSend Start===*/
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
			$scope.forgetPassSend = function(form) {
					if(form.$valid){
						//$state.go('main.home');email,password
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
						var link = 'http://grid.digiopia.in/user/forgot_password';
						$http.post(link, {email : $scope.forgetPass.email},config
								  ).then(function (res){
											  //$scope.registerResponse=JSON.stringify(res);
											  $scope.forgetPass.email= ''
											  $ionicLoading.hide();
												if(res.data.status==true){
													$scope.forgetPass.email='';
													$ionicPopup.alert({
													  title: 'Success',
													  content: 'A password reset code has been sent to your registered mail id. Please check your mail.'
													})
													$state.go('forget-password-access-code');
													
												}else{
													$ionicPopup.alert({
													  title: 'Forget Password Failed',
													  content: res.data.message
													})
													$scope.forgetPass.email='';
													
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
	  /* ===Implementation for the forgetPassSend Start===*/
})