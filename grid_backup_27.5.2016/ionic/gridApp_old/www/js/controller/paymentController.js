gridApp.controller('PaymentCtrl',function($scope,$state,$http,$ionicPopup,ControVarAccessFac,$ionicLoading,$rootScope,$timeout,$ionicHistory ){
//ControVarAccessFac.setData("token",res.data.response.token)
//ControVarAccessFac.setData("paymentVal",res.data.response.paymentVal)	

/*"eyJ2ZXJzaW9uIjoyLCJhdXRob3JpemF0aW9uRmluZ2VycHJpbnQiOiJlMjAwMjEzN2U3MTI3ZDY2NDg1NTNlYWI1YmJhMzMyY2MxMTljNWExODUzNWExMjYzYzUwMTg2YmVhNmYyYTVifGNyZWF0ZWRfYXQ9MjAxNi0wNC0wOFQwOTozNzozNC4yODYwMjg0MzcrMDAwMFx1MDAyNm1lcmNoYW50X2lkPWM3Yzd5MmtxYzc1Z2c4cmtcdTAwMjZwdWJsaWNfa2V5PWZ5M2QyaG1kZ2ttcXNyMjkiLCJjb25maWdVcmwiOiJodHRwczovL2FwaS5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tOjQ0My9tZXJjaGFudHMvYzdjN3kya3FjNzVnZzhyay9jbGllbnRfYXBpL3YxL2NvbmZpZ3VyYXRpb24iLCJjaGFsbGVuZ2VzIjpbXSwiZW52aXJvbm1lbnQiOiJzYW5kYm94IiwiY2xpZW50QXBpVXJsIjoiaHR0cHM6Ly9hcGkuc2FuZGJveC5icmFpbnRyZWVnYXRld2F5LmNvbTo0NDMvbWVyY2hhbnRzL2M3Yzd5MmtxYzc1Z2c4cmsvY2xpZW50X2FwaSIsImFzc2V0c1VybCI6Imh0dHBzOi8vYXNzZXRzLmJyYWludHJlZWdhdGV3YXkuY29tIiwiYXV0aFVybCI6Imh0dHBzOi8vYXV0aC52ZW5tby5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tIiwiYW5hbHl0aWNzIjp7InVybCI6Imh0dHBzOi8vY2xpZW50LWFuYWx5dGljcy5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tL2M3Yzd5MmtxYzc1Z2c4cmsifSwidGhyZWVEU2VjdXJlRW5hYmxlZCI6ZmFsc2UsInBheXBhbEVuYWJsZWQiOnRydWUsInBheXBhbCI6eyJkaXNwbGF5TmFtZSI6IkdyaWQiLCJjbGllbnRJZCI6bnVsbCwicHJpdmFjeVVybCI6Imh0dHA6Ly9leGFtcGxlLmNvbS9wcCIsInVzZXJBZ3JlZW1lbnRVcmwiOiJodHRwOi8vZXhhbXBsZS5jb20vdG9zIiwiYmFzZVVybCI6Imh0dHBzOi8vYXNzZXRzLmJyYWludHJlZWdhdGV3YXkuY29tIiwiYXNzZXRzVXJsIjoiaHR0cHM6Ly9jaGVja291dC5wYXlwYWwuY29tIiwiZGlyZWN0QmFzZVVybCI6bnVsbCwiYWxsb3dIdHRwIjp0cnVlLCJlbnZpcm9ubWVudE5vTmV0d29yayI6dHJ1ZSwiZW52aXJvbm1lbnQiOiJvZmZsaW5lIiwidW52ZXR0ZWRNZXJjaGFudCI6ZmFsc2UsImJyYWludHJlZUNsaWVudElkIjoibWFzdGVyY2xpZW50MyIsImJpbGxpbmdBZ3JlZW1lbnRzRW5hYmxlZCI6dHJ1ZSwibWVyY2hhbnRBY2NvdW50SWQiOiJncmlkIiwiY3VycmVuY3lJc29Db2RlIjoiVVNEIn0sImNvaW5iYXNlRW5hYmxlZCI6ZmFsc2UsIm1lcmNoYW50SWQiOiJjN2M3eTJrcWM3NWdnOHJrIiwidmVubW8iOiJvZmYifQ==";*/

$scope.payment = {
			paymentVal:'',
			cardHolderName:'',
			cardNumber:'',
			cvvNumber:'',
			selCardType:'visa',
			selMonth:'01',
			selYear:'17',
			userSubscription:'',
			userPaymentType:''
			
};

$scope.cardOption=[{val:'visa',name:'Visa'},{val:'mastercard',name:'Master Card'},{val:'American Express',name:'American Express'},{val:'discover',name:'Discover'}];
$scope.selectedCard=$scope.cardOption[0];


$scope.monthOption=[{val:'01',name:'01'},
					{val:'02',name:'02'},
					{val:'03',name:'03'},
					{val:'04',name:'04'},
					{val:'05',name:'05'},
					{val:'06',name:'06'},
					{val:'07',name:'07'},
					{val:'08',name:'08'},
					{val:'09',name:'09'},
					{val:'10',name:'10'},
					{val:'11',name:'11'},
					{val:'12',name:'12'}
					];
$scope.selectedMonth=$scope.monthOption[0];


$scope.yearOption=[{val:'17',name:'2017'},
					{val:'18',name:'2018'},
					{val:'19',name:'2019'},
					{val:'20',name:'2020'},
					{val:'21',name:'2021'},
					{val:'22',name:'2022'},
					{val:'23',name:'2023'},
					{val:'24',name:'2024'},
					{val:'25',name:'2025'},
					{val:'26',name:'2026'},
					{val:'27',name:'2027'},
					{val:'28',name:'2028'},
					{val:'29',name:'2029'},
					{val:'30',name:'2030'}
					];
$scope.selectedYear=$scope.yearOption[0];


			var md5_hash = calcMD5('admin'+'GRID API');
			var config_reg = {
                headers : {
					'Client-Service':'GRIDAPP-CLIENT',
					'Auth-Key':md5_hash,
					'Content-Type': 'application/json; charset=utf-8'
                }
            }

var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }

$scope.$on('$ionicView.enter', function (viewInfo, state) {
			$scope.payment.userSubscription=ControVarAccessFac.getData("user_subscription");
			$scope.payment.userPaymentType=ControVarAccessFac.getData("payment_type");
			var link = 'http://grid.digiopia.in/user/get_subscription';
			$http.post(link,{payment_type:ControVarAccessFac.getData("payment_type")					 
			 },config_reg
			).then(function (res){	
					 // var clientToken =res.data.response.client_token;
					$scope.payment.paymentVal=res.data.response.paymentVal
					
			},function (error){
						//alert("get  error.."+error)
						$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
			});
});

	$scope.onCardChange = function(selVal) {		
    		//alert(selVal.val);
			$scope.payment.selCardType=selVal.val
  	}
	
	$scope.onMonthChange = function(selVal) {		
    		//alert(selVal.val);
			$scope.payment.selMonth=selVal.val
  	}
	
	$scope.onYearChange = function(selVal) {		
    		//alert(selVal.val);
			$scope.payment.selYear=selVal.val
  	}

		
$scope.sendUserPaymentInfo= function(form){
	
	if(form.$valid){
	//alert($scope.payment.selCardType+">>>"+$scope.payment.cardHolderName+">>>"+$scope.payment.cardNumber+">>>"+$scope.payment.cvvNumber+">>>"+$scope.payment.selMonth+$scope.payment.selYear)
			$ionicLoading.show({
				template: '<ion-spinner icon="android"></ion-spinner>'
			});
			var link = 'http://grid.digiopia.in/user/get_payeeze';
			$http.post(link,{cardType:$scope.payment.selCardType,
					   	cardHolderName:$scope.payment.cardHolderName,
						cardNumber:$scope.payment.cardNumber,
						cvvCode:$scope.payment.cvvNumber,
						expDate:$scope.payment.selMonth+$scope.payment.selYear,
						payment_type:ControVarAccessFac.getData("payment_type")
			 },config_reg
			).then(function (res){
				$ionicLoading.hide();
				if(res.data.response.transaction_status=="success"){
					console.log(res.data.response.transaction_status);
					console.log(ControVarAccessFac.getData("user_ID"));
					$scope.showSuccessPopup('Your Payment has done successfully')
					$scope.payment.selCardType='visa'
					$scope.payment.cardHolderName=''
					$scope.payment.cardNumber=''
					$scope.payment.cvvNumber=''
					$scope.payment.selMonth='01'
					$scope.payment.selYear='17'
					
					if(ControVarAccessFac.getData("payment_type")=="subscription"){
						// This payment at the time of resigtration 
						if(ControVarAccessFac.getData("user_ID")==undefined ){
						$ionicLoading.show({
							template: '<ion-spinner icon="android"></ion-spinner>'
						});	
						var link = 'http://grid.digiopia.in/user/registration';
						$http.post(link, {fullname : ControVarAccessFac.getData("r_fullname"),
										  email : ControVarAccessFac.getData("r_email"),
										  password : ControVarAccessFac.getData("r_password"),
										  phone : ControVarAccessFac.getData("r_phone"),
										  occupation : ControVarAccessFac.getData("r_occupation"),
										  dob : ControVarAccessFac.getData("r_dob"),
										  gender :ControVarAccessFac.getData("r_gender"),
										  subcription:ControVarAccessFac.getData("r_subcription"),
										  transactionID:res.data.response.transaction_id,
										  payment_type:ControVarAccessFac.getData("payment_type")
									},config_reg).then(function (res){
													$ionicLoading.hide();
												 
													
													if(res.data.status==true){
														ControVarAccessFac.setData("user_subscription",res.data.response.user_subscription);										
														ControVarAccessFac.setData("map_radious",10);
										
														$scope.showSuccessPopup('Please check your mail for activation code to activate your account.')
														$state.go('account-activate');
														
													}else{
														$ionicPopup.alert({
														  title: 'Registration After Paid Fail',
														  content: res.data.message
														})
														
													}
									},function (error){
										$ionicLoading.hide();
										//alert("Registration error.."+error)
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
									});
						}else{
							// update subcription for the FREE user...
							$ionicLoading.show({
									template: '<ion-spinner icon="android"></ion-spinner>'
							});
							var link = 'http://grid.digiopia.in/user/update_subscription';
						$http.post(link, {user_id : ControVarAccessFac.getData("user_ID"),	
										  transactionID:res.data.response.transaction_id,
										  payment_type:ControVarAccessFac.getData("payment_type")
									},config).then(function (res){
													$ionicLoading.hide();
													if(res.data.status==true){
														ControVarAccessFac.setData("user_subscription",res.data.response.user_subscription);
														$scope.showSuccessPopup('Your Subscription is updated')
														ControVarAccessFac.setData("min_map_radious",res.data.response.min_radius);
									ControVarAccessFac.setData("max_map_radious",res.data.response.max_radius);
									ControVarAccessFac.setData("map_radious",res.data.response.max_radius);
									
									
									//alert("$scope.setting.min_radius::"+res.data.response.min_radius+"  $scope.setting.max_radius::"+res.data.response.max_radius+"   $scope.setting.volume"+res.data.response.max_radius)
														
														//$state.go('main.profile');
														$ionicHistory.goBack(-1)
														
													}else{
														$ionicPopup.alert({
														  title: 'Update Subscription Fail',
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
							
						}
					}else{
						//$state.go('payment')
						$rootScope.submitPost('1',res.data.response.transaction_id);
					}
				}else{
					alert("Transaction error:"+res.data.response.transaction_status)
				}
					
			},function (error){
						$ionicLoading.hide();
						$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
			});
	}
}

$scope.cancelPayment=function(){
	//$state.go('register');
	ControVarAccessFac.setData("createpost_tab",3);
	$ionicHistory.goBack(-1)
}
$scope.openLink =function(){
	$ionicPopup.alert({
		title: 'Terms And Condition',
		content: 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using "Content here, content here", making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for "Grid" will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like). '
	})
}

/* coinfirm message oppup */
	
	// A confirm dialog
 $scope.showConfirm = function(form) {
	 if(form.$valid){
		 var msg='';
		 if(ControVarAccessFac.getData("payment_type")=="subscription"){
			 msg='Confirm payment?'
		 }else{
			  msg='Confirm payment?'
		 }
		// Are you sure to feature your post?
		 
	   var confirmPopup = $ionicPopup.confirm({
		 title: 'Warning!!',
		 template: msg
	   });
	
	   confirmPopup.then(function(res) {
		 if(res) {
			 $scope.sendUserPaymentInfo(form)
		   //console.log('You are sure');
		 } else {
		   //console.log('You are not sure');
		 }
	   });
 	}
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

			
})