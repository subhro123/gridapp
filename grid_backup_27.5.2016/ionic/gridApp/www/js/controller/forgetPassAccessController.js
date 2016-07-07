gridApp.controller('ForgetPassAccessCtrl',function($scope,$state,$http,$ionicPopup,$ionicLoading){
		
		/* forget Pass Access code Model*/
			$scope.forgetPassAccess = {
				code: '',
				password:''	,
				confirmPass:'',
				confirmPassValid:'',
				confirmPassInValid:'',
			};
		
		
			$scope.cancel = function() {
				$state.go('forget-password');
			}
			
			var md5_hash = calcMD5('admin'+'GRID API');
			var config = {
                headers : {
					'Client-Service':'GRIDAPP-CLIENT',
					'Auth-Key':md5_hash,
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
			
			$scope.$watch(function(scope){return scope.forgetPassAccess.confirmPass},
								   function(newValue, oldValue){																							  										//alert(newValue+":::"+oldValue)
								  
									   if(newValue==$scope.forgetPassAccess.password ){
										   $scope.forgetPassAccess.confirmPassValid=true
										   $scope.forgetPassAccess.confirmPassInValid=false
									   }else{
										   $scope.forgetPassAccess.confirmPassInValid=true
										   $scope.forgetPassAccess.confirmPassValid=false
									   }
								  
			});
			$scope.$watch(function(scope){return scope.forgetPassAccess.password},
								   function(newValue, oldValue){																							  										//alert(newValue+":::"+oldValue)
								   if($scope.forgetPassAccess.confirmPass!=='' && $scope.forgetPassAccess.password!==''){
									   if(newValue==$scope.forgetPassAccess.confirmPass){
										   $scope.forgetPassAccess.confirmPassValid=true
										   $scope.forgetPassAccess.confirmPassInValid=false
									   }else{
										   $scope.forgetPassAccess.confirmPassInValid=true
										   $scope.forgetPassAccess.confirmPassValid=false
									   }
								   }else{
									   $scope.forgetPassAccess.confirmPassValid=false;
									   $scope.forgetPassAccess.confirmPassInValid=false;
								   }
			});
		
		/* ===Implementation for the forgetPassReset Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:forgetPassReset
				Parameter: None
				Return :None
				Uses:tomake user login
				Function Called From: forget password reset form submission.
				Function Developed By :Suman Ghosh
				Date:01/13/2016
				@@@@@@@@@@@@@@@@
			*/
			$scope.forgetPassReset = function(form) {
					if($scope.forgetPassAccess.password==$scope.forgetPassAccess.confirmPass){
						$scope.forgetPassAccess.confirmPassValid=true
					}else{
						$scope.forgetPassAccess.confirmPassValid=false
					}
					if(form.$valid && $scope.forgetPassAccess.confirmPassValid){
						//$state.go('main.home');email,password
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
						var link = 'http://grid.digiopia.in/user/reset_password';
						$http.post(link, {access_code : $scope.forgetPassAccess.code,
								   		  new_password:$scope.forgetPassAccess.password},
								  config).then(function (res){
											  //$scope.registerResponse=JSON.stringify(res);
											  $ionicLoading.hide();
											  	$scope.forgetPassAccess.code= '';
												$scope.forgetPassAccess.password=''	;
												$scope.forgetPassAccess.confirmPass='';
												$scope.forgetPassAccess.confirmPassValid='';
												$scope.forgetPassAccess.confirmPassInValid='';
											  
												if(res.data.status==true){
													$scope.forgetPassAccess.code="";
													$scope.forgetPassAccess.password="";
													$scope.forgetPassAccess.confirmPass="";
													$ionicPopup.alert({
													  title: 'Success',
													  content: 'Your new password changed successfully.'
													})
													$state.go('login');
													
												}else{
													$ionicPopup.alert({
													  title: 'Reset Password Failed',
													  content: res.data.message
													})
													$scope.forgetPassAccess.code="";
													$scope.forgetPassAccess.password="";
													$scope.forgetPassAccess.confirmPass="";
													
												}
									},function (error){
										$ionicLoading.hide();
									//alert("Reset Password error.."+error)
									$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
									})
								});
				 }
			}
	  /* ===Implementation for the forgetPassSend Start===*/
})