// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
var gridApp=angular.module('starter', ['ionic','ngCordova','ngStorage','ngTwitter','appConstant','ngMessages','btford.socket-io'])

.run(function($ionicPlatform,$cordovaSplashscreen,$rootScope,$ionicPopup) {
	
$ionicPlatform.registerBackButtonAction(function(){
  event.preventDefault();
}, 100);
	
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if(window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(false);
    }
    if(window.StatusBar) {
      StatusBar.styleDefault();
	  //return StatusBar.hide();
    }
	if(window.Connection) {
                if(navigator.connection.type == Connection.NONE) {
                    $ionicPopup.confirm({
                        title: "Internet Disconnected",
                        content: "The internet is disconnected on your device."
                    })
                    .then(function(result) {
                        if(!result) {
                            ionic.Platform.exitApp();
                        }
                    });
                }
	}
	
	/*if(ionic.Platform.platform()=='ios'){
			ionic.Platform.showStatusBar(false)
	}*/
	
	  /*var deviceInformation = ionic.Platform.device();

	  var isWebView = ionic.Platform.isWebView();
	  var isIPad = ionic.Platform.isIPad();
	  var isIOS = ionic.Platform.isIOS();
	  var isAndroid = ionic.Platform.isAndroid();
	  var isWindowsPhone = ionic.Platform.isWindowsPhone();
	
	  var currentPlatform = ionic.Platform.platform();
	  var currentPlatformVersion = ionic.Platform.version();
	  
	  alert("deviceInformation::"+deviceInformation+"  isWebView===="+isWebView+"    isIPad===="+isIOS+"   isAndroid===="+isAndroid+"   isWindowsPhone===="+isWindowsPhone+"   currentPlatform===="+currentPlatform+"   currentPlatformVersion===="+currentPlatformVersion)*/
	
	/*setTimeout(function() {
        $cordovaSplashscreen.hide()
    }, 3000);*/
                       
                       
              function get_contacts() {
                       
                       var obj = new ContactFindOptions();
                       obj.filter = "";
                       obj.multiple = true;
                       navigator.contacts.find(["displayName", "name", "phoneNumbers"], contacts_success, contacts_fail, obj);
              }
              function contacts_success(contacts) {
                       
                      // if(ionic.Platform.platform()=="ios"){
                       		 //alert(ionic.Platform.platform())
                        	//alert("contacts_success(): " +JSON.stringify( contacts));
                      		 var contactArr = [];
                      		 var formattedContact=[];
                      		 var contactObj={};
                      		 contactArr=contacts;
                       
                       		for(var k=0; k<contactArr.length; k++){
                       			//alert(contactArr[k].name.formatted);
                      			 var email;
                      		 	 var phone;
                       			if(contactArr[k].emails!=null){
                      				 email=contactArr[k].emails[0].value;
                      			}else{
                       				email="";
                       			}
                       
                      			if(contactArr[k].phoneNumbers!=null){
                       				phone=contactArr[k].phoneNumbers[0].value;
                      			}else{
                       				phone="";
                      			}
                       if(phone!='' && phone.length>=10){
                       		var contactObj={fullname:contactArr[k].name.formatted,email:email,phone:phone};
                       		//alert(contactObj.fullname+"::"+contactObj.email+"::"+contactObj.phone)
                       		formattedContact.push(contactObj)
					   		}
                       	}
                       		$rootScope.allPhoneContact=formattedContact;
							//alert("$rootScope.allPhoneContact::"+$rootScope.allPhoneContact)
                      // }
                       
                }
                       function contacts_fail(msg) {
                       alert("get_contacts() Error: " + msg);
                       }
                       get_contacts();
		
  });
})

.config(function($compileProvider){
  $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https?|ftp|file|blob):|data:image\//);
})

.config(function($stateProvider,$urlRouterProvider,$ionicConfigProvider){
				 $ionicConfigProvider.views.swipeBackEnabled(false);
				 $stateProvider
				 	.state('login',{
						   url:'/login',
						   templateUrl:'templates/login.html',
						   controller: 'LoginCtrl'
						   })
					.state('register',{
						   url:'/register',
						   templateUrl:'templates/register.html',
						   controller:'RegisterCtrl'
						   })
					.state('payment',{
						   url:'/payment',
						   templateUrl:'templates/payment.html',
						   controller:'PaymentCtrl'
						   })
					.state('forget-password',{
						   url:'/forget-password',
						   templateUrl:'templates/forget-password.html',
						   controller:'ForgetPassCtrl'
						   })
					.state('forget-password-access-code',{
						   url:'/forget-password-access-code',
						   templateUrl:'templates/forget-password-access-code.html',
						   controller:'ForgetPassAccessCtrl'
						   })
					.state('account-activate',{
						   url:'/account-activate',
						   templateUrl:'templates/account-activate.html',
						   controller:'ActiveAccountCtrl'
						   })
				    .state('resend-activate',{
						   url:'/resend-activate',
						   templateUrl:'templates/resend-activate.html',
						   controller:'ResendActivateCtrl'
				   			})
					.state('no-email',{
						   url:'/no-email',
						   templateUrl:'templates/no-email.html',
						   controller:'NoEmailCtrl'
				   		  })
					.state('interests',{
						   		url:'/interests',								
								templateUrl:'templates/interests.html',
								controller:'InterestCtrl'
				   		   })
					.state('interests-sub',{
						   		url:'/interests-sub',
						   		templateUrl:'templates/interests-sub.html',
						   		controller:'InterestSubCtrl'
				   			})
					.state('main', {
							url : '/main',
							templateUrl : 'templates/mainContainer.html',
							abstract : true,
							controller : 'MainCtrl'
            				})
					.state('main.home', {
								url: '/home',
								views: {
									'main': {
										templateUrl: 'templates/home.html',
										controller : 'HomeCtrl'
									}
								}
            				})
					.state('main.profile', {
								url: '/profile',
								views: {
									'main': {
										templateUrl: 'templates/profile.html',
										controller : 'ProfileCtrl'
									}
								}
            				})
					.state('main.profile-other', {
								url: '/profile-other',
								views: {
									'main': {
										templateUrl: 'templates/profile-other.html',
										controller : 'ProfileOtherCtrl'
									}
								}
            				})
					.state('main.chat', {
								url: '/chat',
								views: {
									'main': {
										templateUrl: 'templates/chat.html',
										controller : 'ChatCtrl'
									}
								}
            				})
					.state('main.import-contact-menu', {
								url: '/import-contact-menu',
								views: {
									'main': {
										templateUrl: 'templates/import-contact-menu.html',
										controller : 'ImportContactMenuCtrl'
									}
								}
            				})
					.state('main.dashboard',{
						   url:'/dashboard',
						   views: {
							   'main': {
						   			templateUrl:'templates/dashboard.html',
						  	 		controller:'DashboardCtrl'
							   }
						   }
				   		})
					.state('main.friend',{
						   url:'/friend',
						   views: {
							   'main': {
						   			templateUrl:'templates/friend.html',
						  	 		controller:'FriendCtrl'
							   }
						   }
				   		})
					.state('main.import-contact',{
						   url:'/import-contact',
						   views: {
							   'main': {
						   			templateUrl:'templates/import-contact.html',
						  	 		controller:'ImportContactCtrl'
							   }
						   }
				   		})
					.state('main.create-post',{
						   url:'/create-post',
						   views: {
							   'main': {
						   			templateUrl:'templates/create-post.html',
						  	 		controller:'CreatePostCtrl'
							   }
						   }
				   		})
					.state('main.post',{
						   url:'/post',
						   views: {
							   'main': {
						   			templateUrl:'templates/post.html',
						  	 		controller:'PostCtrl'
							   }
						   }
				   		})
					.state('main.post-owner-view',{
						   url:'/post-owner-view',
						   views: {
							   'main': {
						   			templateUrl:'templates/post-owner-view.html',
						  	 		controller:'PostOwnerViewCtrl'
							   }
						   }
				   		})
					.state('main.post-want-join-view',{
						   url:'/post-want-join-view',
						   views: {
							   'main': {
						   			templateUrl:'templates/post-want-join-view.html',
						  	 		controller:'PostWantJoinViewCtrl'
							   }
						   }
				   		})
					.state('main.post-going-view',{
						   url:'/post-going-view',
						   views: {
							   'main': {
						   			templateUrl:'templates/post-going-view.html',
						  	 		controller:'PostGoingViewCtrl'
							   }
						   }
				   		})
					.state('main.post-other-view',{
						   url:'/post-other-view',
						   views: {
							   'main': {
						   			templateUrl:'templates/post-other-view.html',
						  	 		controller:'PostOtherViewCtrl'
							   }
						   }
				   		})
					.state('main.post-going-view-other',{
						   url:'/post-going-view-other',
						   views: {
							   'main': {
						   			templateUrl:'templates/post-going-view-other.html',
						  	 		controller:'PostGoingViewOtherCtrl'
							   }
						   }
				   		})
					.state('main.settings',{
						   url:'/settings',
						   views: {
							   'main': {
						   			templateUrl:'templates/settings.html',
						  	 		controller:'SettingsCtrl'
							   }
						   }
				   		})
					.state('main.payment-history',{
						   url:'/payment-history',
						   views: {
							   'main': {
						   			templateUrl:'templates/payment-history.html',
						  	 		controller:'PaymentHisCtrl'
							   }
						   }
				   		})
					.state('main.drink', {
								url: '/drink',
								views: {
									'main': {
										templateUrl: 'templates/drink.html',
										controller : 'DrinkCtrl'
									}
								}
            				})
					.state('main.map', {
								url: '/map',
								views: {
									'main': {
										templateUrl: 'templates/map.html',
										controller : 'MapCtrl'
									}
								}
            				})
				 $urlRouterProvider.otherwise('/login')
})
/*
.controller('NavController', function($scope, $ionicSideMenuDelegate) {
      $scope.toggleLeft = function() {
        $ionicSideMenuDelegate.toggleLeft();
      };
})
*/
.factory('ControVarAccessFac',function(){
		/*============Note========*/
			//user_ID=for unique id from DB
			//userID =fom emailID
		/***************************/
	  var factory={};
	  var model={};
	  factory.setData = function(key, value) {
       model[key] = value;
      }
	  factory.getData = function(key) {
		   return model[key];
	  };
	  factory.destroyModel=function(){
		  model=null;
		  delete model;
	  }
	  factory.createModel=function(){
		  model={};
	  }
	  return factory;
									
})
/* This factory is used to save image in locale storage */

.factory('FileService', function() {
  var images;
  var IMAGE_STORAGE_KEY = 'images';
 
  function getImages() {
    var img = window.localStorage.getItem(IMAGE_STORAGE_KEY);
    if (img) {
      images = JSON.parse(img);
    } else {
      images = [];
    }
    return images;
  };
 
  function addImage(img) {
    images.push(img);
    window.localStorage.setItem(IMAGE_STORAGE_KEY, JSON.stringify(images));
  };
 
  return {
    storeImage: addImage,
    images: getImages
  }
})

/* This service is used to handel capture or gallery Image */

.factory('ImageService', function($cordovaCamera, FileService, $q, $cordovaFile) {
 
  function makeid() {
    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
 
    for (var i = 0; i < 5; i++) {
      text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
  };
 
  function optionsForType(type) {
    var source;
    switch (type) {
      case 0:
        source = Camera.PictureSourceType.CAMERA;
        break;
      case 1:
        source = Camera.PictureSourceType.PHOTOLIBRARY;
        break;
    }
    return {
      destinationType: Camera.DestinationType.FILE_URI,
      sourceType: source,
      allowEdit: false,
      encodingType: Camera.EncodingType.JPEG,
      popoverOptions: CameraPopoverOptions,
      saveToPhotoAlbum: false,
	  correctOrientation:true
    };
  }
 
  function saveMedia(type) {
    return $q(function(resolve, reject) {
      var options = optionsForType(type);
 
      $cordovaCamera.getPicture(options).then(function(imageUrl) {
        var name = imageUrl.substr(imageUrl.lastIndexOf('/') + 1);
        var namePath = imageUrl.substr(0, imageUrl.lastIndexOf('/') + 1);
        var newName = makeid() + name;
        $cordovaFile.copyFile(namePath, name, cordova.file.dataDirectory, newName)
          .then(function(info) {
            FileService.storeImage(newName);
            resolve();
          }, function(e) {
            reject();
          });
      });
    })
  }
  return {
    handleMediaDialog: saveMedia
  }
})

/* This service is used for paypal payment */
.factory('PaypalService', ['$q', '$ionicPlatform', 'shopSettings', '$filter', '$timeout', function ($q, $ionicPlatform, shopSettings, $filter, $timeout) {

        var init_defer;
        /**
         * Service object
         * @type object
         */
        var service = {
            initPaymentUI: initPaymentUI,
            createPayment: createPayment,
            configuration: configuration,
            onPayPalMobileInit: onPayPalMobileInit,
            makePayment: makePayment
        };


        /**
         * @ngdoc method
         * @name initPaymentUI
         * @methodOf app.PaypalService
         * @description
         * Inits the payapl ui with certain envs. 
         *
         * 
         * @returns {object} Promise paypal ui init done
         */
        function initPaymentUI() {
				
            init_defer = $q.defer();
            $ionicPlatform.ready().then(function () {

                var clientIDs = {
                    "PayPalEnvironmentProduction": shopSettings.payPalProductionId,
                    "PayPalEnvironmentSandbox": shopSettings.payPalSandboxId
                };
                PayPalMobile.init(clientIDs, onPayPalMobileInit);
            });

            return init_defer.promise;

        }


        /**
         * @ngdoc method
         * @name createPayment
         * @methodOf app.PaypalService
         * @param {string|number} total total sum. Pattern 12.23
         * @param {string} name name of the item in paypal
         * @description
         * Creates a paypal payment object 
         *
         * 
         * @returns {object} PayPalPaymentObject
         */
        function createPayment(total, name) {
                
            // "Sale  == >  immediate payment
            // "Auth" for payment authorization only, to be captured separately at a later time.
            // "Order" for taking an order, with authorization and capture to be done separately at a later time.
            var payment = new PayPalPayment("" + total, "USD", "" + name, "Sale");
            return payment;
        }
        /**
         * @ngdoc method
         * @name configuration
         * @methodOf app.PaypalService
         * @description
         * Helper to create a paypal configuration object
         *
         * 
         * @returns {object} PayPal configuration
         */
        function configuration() {
            // for more options see `paypal-mobile-js-helper.js`
            var config = new PayPalConfiguration({merchantName: shopSettings.payPalShopName, merchantPrivacyPolicyURL: shopSettings.payPalMerchantPrivacyPolicyURL, merchantUserAgreementURL: shopSettings.payPalMerchantUserAgreementURL});
			
            return config;
        }

        function onPayPalMobileInit() {
			
            $ionicPlatform.ready().then(function () {
                // must be called
                // use PayPalEnvironmentNoNetwork mode to get look and feel of the flow
                PayPalMobile.prepareToRender(shopSettings.payPalEnv, configuration(), function () {

                    $timeout(function () {
                        init_defer.resolve();
                    });

                });
            });
        }

        /**
         * @ngdoc method
         * @name makePayment
         * @methodOf app.PaypalService
         * @param {string|number} total total sum. Pattern 12.23
         * @param {string} name name of the item in paypal
         * @description
         * Performs a paypal single payment 
         *
         * 
         * @returns {object} Promise gets resolved on successful payment, rejected on error 
         */
        function makePayment(total, name) {
            var defer = $q.defer();
            total = $filter('number')(total, 2);
            $ionicPlatform.ready().then(function () {
                PayPalMobile.renderSinglePaymentUI(createPayment(total, name), function (result) {
                    $timeout(function () {
                        defer.resolve(result);
                    });
                }, function (error) {
                    $timeout(function () {
                        defer.reject(error);
                    });
                });
            });

            return defer.promise;
        }

        return service;
    }]);





angular.module('appConstant', []).constant('shopSettings',{
   payPalSandboxId : 'AbyddNtJnYqzs4WGZA-RAfPvlqsXD03-7qq5TLywcVS02cqO6GyozOZYBDY47APtfwJbCxv8GN3cGW4P',
   payPalProductionId : 'production id here',
   payPalEnv: 'PayPalEnvironmentSandbox',   // for testing  production for production
   payPalShopName : 'gridApp',
   payPalMerchantPrivacyPolicyURL : 'https://mytestshop.com/policy',
   payPalMerchantUserAgreementURL : 'https://mytestshop.com/agreement' 
});


 