gridApp.controller('RegisterCtrl',function($scope,$state,$http,$ionicPopup,$ionicLoading,$cordovaDatePicker,$filter,$ionicPlatform,$cordovaGeolocation,ControVarAccessFac,$timeout){
	
	/* Resigtration Model*/	
		$scope.register = {
			fullname: '',
			email : '',
			password:'',
			phone:'',
			occupation:'',
			dob:'',//'10/05/1980',
			dobFormat:'DATE OF BIRTH',
			gender:'male',
			subcription:'FREE',
			userLat:'',
			userLang:'',
			contrycode:''
		};
		var dateObj= new Date();
		var yyyy=dateObj.getFullYear();
		var mm=dateObj.getMonth();
		var dd=dateObj.getDate();
		var hh=dateObj.getHours();
		var MM=dateObj.getMinutes();
		var ss=dateObj.getSeconds();
		var yyyymmddhhMMss=yyyy.toString()+mm.toString()+dd.toString()+hh.toString()+MM.toString()+ss.toString();
		var md5_time_hash = calcMD5(yyyymmddhhMMss);
		var md5_hash = calcMD5('admin'+'GRID API');
			var config = {
                headers : {
					'Client-Service':'GRIDAPP-CLIENT',
					'Auth-Key':md5_hash,
					'UniqueTime-Key':md5_time_hash,
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
	/*$scope.registerResponse='';
	*/
	//alert("uuid:: "+$scope.uuid+" $scope.model:: "+$scope.model+" $scope.platform:: "+$scope.platform)
	
	/* ===Implementation for the user Registration start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:userRegistration
				Parameter: None
				Return :None
				Uses:To call registration web service to send registration related data
				Function Developed By :Suman Ghosh
				Date:01/12/2016
				@@@@@@@@@@@@@@@@
			*/
		$scope.userRegistration = function(form) {		
		  	//alert("Register called....")
			//alert($scope.register.subcription)
			//$state.go('payment');
			if($scope.register.gender="male"){
					gender="M"
			}else{
					gender="F"
			}
			
			if($scope.register.subcription=="FREE"){
				if(form.$valid){
				
				$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
				var gender;
				
				//alert("Register called...DOB..."+$scope.register.dob)
				var link = 'http://grid.digiopia.in/user/registration';
				$http.post(link, {fullname : $scope.register.fullname,
								  email : $scope.register.email,
								  password : $scope.register.password,
								  phone : $scope.register.contrycode+$scope.register.phone,
								  occupation : $scope.register.occupation,
								  dob : $scope.register.dob,
								  gender : gender,
								  subcription:$scope.register.subcription,
								  transactionID:''
							},config).then(function (res){
											$ionicLoading.hide();
										 
										  	//alert("Registration succes.."+res.data.message)
										  	//alert("Registration succes.."+$scope.registerResponse.message)
											//$scope.response = res.data;
											
											$scope.register.fullname= '',
											$scope.register.email = '',
											$scope.register.password='',
											$scope.register.phone='',
											$scope.register.occupation='',
											$scope.register.dob='',//'10/05/1980',
											$scope.register.dobFormat='DATE OF BIRTH',
											$scope.register.gender='male',
											$scope.register.subcription='FREE',
											$scope.register.userLat='',
											$scope.register.userLang='',
											$scope.register.contrycode=''
											
											if(res.data.status==true){	
											 $scope.registerResponse=JSON.stringify(res);
											 ControVarAccessFac.setData("user_subscription",res.data.response.user_subscription);
												$scope.showSuccessPopup('Please check your mail for activation code to activate your account.')
										  		$state.go('account-activate');
												
											}else{
												$ionicPopup.alert({
												  title: 'Registration Fail',
												  content: res.data.message
												})
												$scope.register.email="";
											}
							},function (error){
								$ionicLoading.hide();
								//alert("Registration error.."+error)
								$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							});
				}				
			}else{
				ControVarAccessFac.setData("r_fullname",$scope.register.fullname)
				ControVarAccessFac.setData("r_email",$scope.register.email)
				ControVarAccessFac.setData("r_password",$scope.register.password)
				ControVarAccessFac.setData("r_phone",$scope.register.phone)
				ControVarAccessFac.setData("r_occupation",$scope.register.occupation)
				ControVarAccessFac.setData("r_dob",$scope.register.dob)
				ControVarAccessFac.setData("r_gender",gender)
				ControVarAccessFac.setData("r_subcription",$scope.register.subcription)
				ControVarAccessFac.setData("payment_type","subscription")
				$state.go('payment');
			}
    	}
	/* ===Implementation for the user Registration end===*/
	
	
	$scope.selectGenderMale = function() {		
          $scope.register.gender="male" 
		 //alert($scope.register.gender)
    }
	$scope.selectGenderFemale = function() {		
          $scope.register.gender="female" 
		 // alert($scope.register.gender)
		  
    }
	$scope.setDateOfBirth= function() {
			var optionsRegis = {
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
		  $cordovaDatePicker.show(optionsRegis).then(function(date){
				$scope.register.dob= $filter('date')(date, ' MM/dd/yyyy');
				$scope.register.dobFormat=$filter('date')(date, '  MM/yyyy');
		  });
	}
	
	//=================Set user position to get country code ==================//
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
			/*
			$scope.register.userLat  = ControVarAccessFac.getData("userLat");
			$scope.register.userLang = ControVarAccessFac.setData("userLang");
			*/
						
			
		$scope.setUserUpdatdPosition= function(){
			console.log("set user position.....")
			$ionicPlatform.ready(function() {
					var posOptions = {
						enableHighAccuracy: true,
						timeout: 20000,
						maximumAge: 0
					};
					$cordovaGeolocation.getCurrentPosition(posOptions).then(function (position) {
						$scope.register.userLat  = position.coords.latitude;
						$scope.register.userLang = position.coords.longitude;
						
						
						var link = 'http://grid.digiopia.in/user/get_phone_code';
						$http.post(link,{
									 post_lat:$scope.register.userLat,
									 post_long:$scope.register.userLang
							 },config
							).then(function (res){	
									  $scope.register.contrycode=res.data.response.phonecode
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
		$scope.setUserUpdatdPosition();								 
	})
	//=========================================================================//
	
	/* success message pop up */
	   $scope.showSuccessPopup = function(txtMsg) {
		   
			$scope.data = {};
	
	  // An elaborate, custom popup
	  var myPopup = $ionicPopup.show({
		template: '',
		title: 'Congrats!!! Your account on Grid has been created successfully.',
		subTitle: txtMsg,
		scope: $scope   
	  });
	
	  myPopup.then(function(res) {
		console.log('Tapped!', res);
	  });
	
	  $timeout(function() {
		 myPopup.close(); //close the popup after 3 seconds for some reason
	  }, 5000);
	 };
	
	
})
