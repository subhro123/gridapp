gridApp.controller('LoginCtrl',function($scope,$state,$cordovaOauth,$http,$ionicPopup,$localStorage,$twitterApi,$ionicLoading,ControVarAccessFac,$interval,$cordovaGeolocation,$ionicPlatform,$rootScope,$filter){
		
		/* login Model*/
			$scope.login = {
				userEmail: '',
				password:'',
				userLat:'',
				userLang:''
			};
			
			var md5_hash = calcMD5('admin'+'GRID API');
			var config = {
                headers : {
					'Client-Service':'GRIDAPP-CLIENT',
					'Auth-Key':md5_hash,
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
			
			$scope.$on('$ionicView.enter', function (viewInfo, state) {
					ControVarAccessFac.createModel();
					ControVarAccessFac.setData("myFriendSelectedDate",moment().format("dddd, MMMM Do YYYY"));
					ControVarAccessFac.setData("arroundSelectedDate",moment().format("dddd, MMMM Do YYYY"));
					ControVarAccessFac.setData("myEventSelectedDate",moment().format("dddd, MMMM Do YYYY"))
					
			})
			
			/*$scope.$on("$ionicView.beforeEnter", function(event, data){
   				// handle event
  				 alert("State Params:Before Enter ");
			});*/

			
		
		/* ===Implementation for the User Login Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:userLogin
				Parameter: None
				Return :None
				Uses:tomake user login
				Function Called From: login form submission.
				Function Developed By :Suman Ghosh
				Date:01/13/2016
				@@@@@@@@@@@@@@@@
			*/
		
			$scope.userLogin = function(form) {
				if(form.$valid){
					//$state.go('main.home');email,password 
					$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
					var link = 'http://grid.digiopia.in/user/login';
					
					$http.post(link, {email : $scope.login.userEmail,
								  	  password : $scope.login.password},
									  config
							  ).then(function (res){
								 
										  //$scope.registerResponse=JSON.stringify(res);
										  
								if(res.data.status==200){
									ControVarAccessFac.setData("userID",$scope.login.userEmail);
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
									
										console.log(ControVarAccessFac.getData("user_ID"))
										  		//$state.go('main.import-contact-menu');
												$scope.login.userEmail='';
												$scope.login.password='';
												if(!res.data.response.is_interest){
													$state.go('interests');
												}else{
													//$state.go('main.dashboard')
													$state.go('main.profile');
													//$state.go('main.friend');
												}
												$scope.setUserUpdatdPosition();
										$rootScope.updatePosition=$interval($rootScope.setUserUpdatdPosition,60000)
												$ionicLoading.hide();
											}else {
												$ionicPopup.alert({
												  title: 'Login Fail',
												  content: res.data.message
												})
												$scope.login.userEmail="";
												$scope.login.password="";
												$ionicLoading.hide();
											}
							},function (error){
								//alert("Login error.."+error)
								$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							});
				}
			}
		/* ===Implementation for the User Login End===*/
		
		/* ===Implementation for the social login for Facebook Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:facebooklogin
				Parameter: None
				Return :None
				Uses:Make the face book login functionality. And retuns logged in user's details
				Function Called From: Face book login Ion.
				Function Developed By :Suman Ghosh
				Date:11/20/2015
				@@@@@@@@@@@@@@@@
			*/
			$scope.facebooklogin=function(){
				//alert("facebooklogin called")
				$cordovaOauth.facebook("433558930182083", ["public_profile","email","user_website", "user_location", "user_relationships"]).then(function(result) {
						$localStorage.accessToken = result.access_token;						
						// =====this part for FB login profile====
						if($localStorage.hasOwnProperty("accessToken") === true) {
							$http.get("https://graph.facebook.com/v2.2/me", { params: { access_token: $localStorage.accessToken, fields: "id,name,gender,location,website,picture,relationship_status,birthday,email", format: "json" }}).then(function(result) {
								$scope.profileData = result.data;
								//alert(JSON.stringify($scope.profileData));
								$scope.sendFBLoginData();
							}, function(error) {
								alert("There was a problem getting your profile.  Check the logs for details.");
								console.log(error);
							});
						} else {
							alert("Not signed in");
							//$location.path("/login");
						}
					//$location.path("/profile");
				}, function(error) {
					alert("There was a problem signing in!  See the console for logs");
					console.log(error);
				});
			}
			
			/**=====Send FB data to server ===== **/
			$scope.sendFBLoginData = function() {
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
						//alert("fbProfileData"+ JSON.stringify($scope.profileData))
					var link = 'http://grid.digiopia.in/user/facebook_import';
					$http.post(link, {social : "facebook",
							   		  role : "freeuser",
									  fbProfileData : $scope.profileData
								}).then(function (res){
											$ionicLoading.hide();
											  //$scope.registerResponse=JSON.stringify(res);
											  //alert(JSON.stringify(res.data))
											  //alert("FB login succes.."+res.data.message)
											 // alert("FB login email.."+res.data.response.email)
											 //alert(JSON.stringify(res.data.response))
										ControVarAccessFac.setData("userSocialID",res.data.response.social_id);
										ControVarAccessFac.setData("loginType",res.data.response.loginType);
										ControVarAccessFac.setData("user_ID",res.data.response.user_id);
											  if(res.data.response.email==true){
										ControVarAccessFac.setData("userID",res.data.response.email_id);
												  //$state.go('main.import-contact-menu');
												  $state.go('interests');
											  }else{
												  $state.go('no-email');
											  }
											
										},function (error){
											$ionicLoading.hide();
											alert("Activation error.."+error)
								});
				
			}
			/** ======= send FB data End === **/
		/*===Implementation for the social login for Facebook End===*/
		
		/* ===Implementation for the social login for Twitter Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:twitterlogin
				Parameter: None
				Return :None
				Uses:Make the twitter login functionality. And retuns logged in user's details
				Function Called From: twitter login Ion.
				Function Developed By :Suman Ghosh
				Date:11/20/2015
				@@@@@@@@@@@@@@@@
			*/
			$scope.twitterlogin=function(){
					var twitterKey = 'STORAGE.TWITTER.KEY';
					var clientId = 'Hm0rgymtfxa7KW9znE2ApgmEB';
					var clientSecret = 'P4hjBouxHIygGZmCctyVtWw5vwNyVLvNcqKivXXn0EidqJwdAK';
					var myToken = '';
					
					$cordovaOauth.twitter(clientId, clientSecret).then(function(result) {
						$localStorage.oauthToken = result.oauth_token;
						$localStorage.oauthTokenSecret = result.oauth_token_secret;
						$localStorage.screeName = result.screen_name;
						$localStorage.userId = result.user_id;
						$twitterApi.configure(clientId, clientSecret, result);
						
						//$twitterApi.getHomeTimeline().then(function(data) {
							// $scope.home_timeline = data;
							// alert(JSON.stringify(data));
						//});
						//alert(JSON.stringify(result));
						$twitterApi.getUserDetails($localStorage.userId).then(function(data) {
							//alert("getUserDetails called.......")
							 $scope.profileDataTwitter = data;
							 //alert(JSON.stringify(data));
							 //$location.path("/profile");
							 $scope.sendTwitterLoginData();
						});
						
						//$location.path("/profile");
					   
					}, function(error) {
						alert("There was a problem signing in!  See the console for logs");
						console.log(error);
					});
			}
			
			/**=====Send twitter data to server ===== **/
			$scope.sendTwitterLoginData = function() {
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
				    //alert("twtlogindata"+ JSON.stringify($scope.profileDataTwitter))
					var link = 'http://grid.digiopia.in/user/twitter_import';
					$http.post(link, {social : "twitter",
							   		  role : "freeuser",
									  twtlogindata : $scope.profileDataTwitter
								}).then(function (res){
											$ionicLoading.hide();
											//alert(JSON.stringify(res.data.response))
										ControVarAccessFac.setData("userSocialID",res.data.response.social_id);
										ControVarAccessFac.setData("loginType",res.data.response.loginType);
										ControVarAccessFac.setData("user_ID",res.data.response.user_id);
											  if(res.data.response.email==true){
										ControVarAccessFac.setData("userID",res.data.response.email_id);
												  //$state.go('main.import-contact-menu');
												  $state.go('interests');
											  }else{
												  $state.go('no-email');
											  }
										},function (error){
											$ionicLoading.hide();
											alert("Activation error.."+error)
								});
				
			}
			/** ======= send twitter data End === **/
		/*===Implementation for the social login for Twitter End===*/
		
		
		
		
		/* ===Implementation for the social login for Linkedin Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:linkedinlogin
				Parameter: None
				Return :None
				Uses:Make the Linkedin login functionality. And retuns logged in user's details
				Function Called From: twitter login Ion.
				Function Developed By :Suman Ghosh
				Date:04/05/2016
				@@@@@@@@@@@@@@@@
			*/
			$scope.linkedinlogin=function(){
					$cordovaOauth.linkedin("75o3gw1xog9rsp","Lwrvn3BzCGqD9oUG", ["r_basicprofile","r_emailaddress"],"9614772155").then(function(result) {
            		$localStorage.accessToken = result.access_token;
					alert(JSON.stringify(result))
					//alert("result.access_token"+result.access_token)
           			 //$location.path("/profile");
					 /*oauthService.getLinkedinProfile(access_token).then(function(result){
						//do what you want
						}, function(err){
						//handle error
						});
					 */
        	}, function(error) {
            alert("There was a problem signing in!  See the console for logs");
            console.log(error);
       		 });
   
		}
			
			/**=====Send twitter data to server ===== **/
			$scope.sendTwitterLoginData = function() {
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
				    //alert("twtlogindata"+ JSON.stringify($scope.profileDataTwitter))
					var link = 'http://grid.digiopia.in/user/twitter_import';
					$http.post(link, {social : "twitter",
							   		  role : "freeuser",
									  twtlogindata : $scope.profileDataTwitter
								}).then(function (res){
											$ionicLoading.hide();
											//alert(JSON.stringify(res.data.response))
										ControVarAccessFac.setData("userSocialID",res.data.response.social_id);
										ControVarAccessFac.setData("loginType",res.data.response.loginType);
										ControVarAccessFac.setData("user_ID",res.data.response.user_id);
											  if(res.data.response.email==true){
										ControVarAccessFac.setData("userID",res.data.response.email_id);
												  //$state.go('main.import-contact-menu');
												  $state.go('interests');
											  }else{
												  $state.go('no-email');
											  }
										},function (error){
											$ionicLoading.hide();
											alert("Activation error.."+error)
								});
				
			}
			/** ======= send Linkedin data End === **/
		/*===Implementation for the social login for Linkedin End===*/
		
		
		
		
		
		
		
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
						$scope.login.userLat  = position.coords.latitude;
						$scope.login.userLang = position.coords.longitude;
						ControVarAccessFac.setData("userLat",$scope.login.userLat);
						ControVarAccessFac.setData("userLang",$scope.login.userLang);
						var config = {
							headers : {
								'Authorizations':ControVarAccessFac.getData("token") ,
								'User-Id':ControVarAccessFac.getData("user_ID"),
								'Content-Type': 'application/json; charset=utf-8'
							}
						}
						var link = 'http://grid.digiopia.in/user/user_position';
						$http.post(link,{user_id:ControVarAccessFac.getData("user_ID"),
									 post_lat:$scope.login.userLat,
									 post_long:$scope.login.userLang
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
})