gridApp.controller('ActiveAccountCtrl',function($scope,$state,$cordovaDevice,$http,$ionicPopup,$ionicLoading,$timeout){
		$scope.cancel = function() {
            $state.go('login');
        }
		$scope.accountActivate = {
			activationEmail:'',
			activationCode:''
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
												$scope.accountActivate.activationEmail='';
												$scope.accountActivate.activationCode=''
											  $scope.registerResponse=JSON.stringify(res);
											 // alert("Activation succes.."+res.data.message)
											  //alert("Registration succes.."+$scope.registerResponse.message)
											//$scope.response = res.data;
											 
											if(res.data.status==true){
												/*$ionicPopup.alert({
												  title: 'Success',
												  content: 'Your Account activated successfully.'
												})*/
												$scope.showSuccessPopup('Please login to use GRID app')
										  		 $state.go('login');
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
		/* ===Implementation for the Account Activation end===*/
})