gridApp.controller('ImportContactCtrl',function($scope,$state,$cordovaContacts,$ionicLoading,$http,ControVarAccessFac,$stateParams, $location, $ionicScrollDelegate,$log,$rootScope,$ionicPopup,$cordovaSms,$timeout){
		
		$scope.importContactBack = function() {
			 $state.go('main.import-contact-menu');;
  		};
		/*=== import contact variables ===*/
		$scope.contacts={};
		$scope.formattedContact=[];
		$scope.user_id=ControVarAccessFac.getData("userID");
		$scope.contactFromDB=[];
		$scope.users=[];
		$scope.sorted_users=[];
		
		var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
		
		
		$scope.$on('$ionicView.enter', function (viewInfo, state) {
												
			$scope.contacts={};
			$scope.formattedContact=[];
			$scope.user_id=ControVarAccessFac.getData("userID");
			$scope.contactFromDB=[];
			$scope.users=[];
			$scope.sorted_users=[];
			
			/* This part for testing perpouse */
			/*$scope.contactFromDB=[
							   {"fullname":"Suman Ghosh",
							    "phone":"9614772158",
							   	"email":"suman@gmail.com",
							   	"grid":true},
								{"fullname":"Sujan Ghosh",
							    "phone":"9614772155",
							   	"email":"sujan@gmail.com",
							   	"grid":true},
							   {
								"fullname":"Malay Ghosh",
							    "phone":"9614999990",
							   	"email":"malay@gmail.com",
							   	"grid":false
							   }
							   ];
			$scope.users=$scope.contactFromDB;*/
			/* test end */
			
			
			$scope.importContact();
		});
		
		
		
		/* ===Implementation for the get contact from Phone Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:importContact
				Parameter: None
				Return :None
				Uses:to populate contact getting from phone/facebook/tweeter/linkedin
				Function Called From: import contact page onload.
				Function Developed By :Suman Ghosh
				Date:01/18/2016
				@@@@@@@@@@@@@@@@
			*/			
			$scope.importContact = function() {
				var importType=ControVarAccessFac.getData("selecContactImportType");
				
				if(importType=="PHONE"){
                   
                   /*for(var i=0;i<$rootScope.allPhoneContact.length;i++){
                    alert($rootScope.allPhoneContact[i].fullname+":::"+$rootScope.allPhoneContact[i].email+"::::"+$rootScope.allPhoneContact[i].phone)
                   }*/
					/*alert("PHONE"+$rootScope.allPhoneContact[0].fullname)
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
					 	$cordovaContacts.find({filter: ''}).then(function(result) {
							  //alert(result)
							 $scope.contacts = result;
							 $ionicLoading.hide();
							 angular.forEach($scope.contacts, function(contact){
                   					//console.log(item.id); 
								   
								   //alert(contact.displayName)
								   var phonNumArr=[];
								   angular.forEach(contact.phoneNumbers,function(elm){
										//alert(elm.value)	
										phonNumArr.push(elm.value)
								   })
								   var emailIdArr=[];
								   angular.forEach(contact.emails,function(elm){
										//alert(elm.value)	
										emailIdArr.push(elm.value)
								   })
								  var contactObj={fullname:contact.displayName,email:emailIdArr,phone:phonNumArr}
								  $scope.formattedContact.push(contactObj)
								  
         					})							
							 $scope.sendContactInfoToServer();
							 
						}, function(error) {
					 			console.log("ERROR: " + error);
								$ionicLoading.hide();
						});*/
						
						
						/*for(var i=0;i<$rootScope.allPhoneContact.length;i++){
							alert($rootScope.allPhoneContact[i].fullname+":"+$rootScope.allPhoneContact[i].email+":::"+$rootScope.allPhoneContact[i].phone)
						}*/
                   $scope.sendContactInfoToServer();
			  }
		  }		
	  /* ===Implementation for the get contact from Phone End===*/
	  
			$scope.sendContactInfoToServer= function(){

						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
						
						var link = 'http://grid.digiopia.in/user/import_contact';
																																		                       $http.post(link,{user_id:ControVarAccessFac.getData("user_ID"),contactDetails:$rootScope.allPhoneContact},config).then(function (res){
																																																																						 										//alert(JSON.stringify(res.data.response))
									//alert(res.data)																						  							           
									  //alert(JSON.stringify(res.data.status));
									  		if(res.data.status==true){
													//$scope.subInterestResponse=res.data.response;
													//$state.go('main.home');
													//alert(JSON.stringify(res.data.response.contactDetails))
													$scope.contactFromDB=res.data.response.contactDetails;
													$scope.users=res.data.response.contactDetails;
													$scope.populateContact();
													$ionicLoading.hide();
												}else{
													$ionicPopup.alert({
													  title: 'contact Fail',
													  content: res.data.message
													})
													$ionicLoading.hide();
												}
									},function (error){
										//alert("get Contact error.."+JSON.stringify(error))
										$ionicLoading.hide();
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
								});
			}
			
	/* ========================= */
	
	$scope.openSelectedProfile= function(elementId){
		console.log('elementId:: '+elementId)
		var selectedIndex=parseInt(elementId)
		console.log("elementId"+selectedIndex+"IsGrid::"+$scope.contactFromDB[selectedIndex].grid+'Phone:'+$scope.contactFromDB[selectedIndex].phone)
		if($scope.contactFromDB[selectedIndex].grid==true){
			//alert("other_user_ID::: "+$scope.contactFromDB[selectedIndex-1].user_id)
			ControVarAccessFac.setData("other_user_ID",$scope.contactFromDB[selectedIndex].user_id);
			$state.go('main.profile-other');
		}else{
			/*$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
						
						var link = 'http://grid.digiopia.in/user/friend_invite';
						//alert(ControVarAccessFac.getData("user_ID")+":::"+selectedIndex)
																																		                       $http.post(link,{user_id:ControVarAccessFac.getData("user_ID"),import_user_id:selectedIndex}).then(function (res){
									//alert(res.data);
									
									  		if(res.data.status==true){
													
													$ionicLoading.hide();
												}else{
													$ionicPopup.alert({
													  title: 'contact Fail',
													  content: res.data.message
													})
													$ionicLoading.hide();
												}
									},function (error){
										alert("get Contact error.."+JSON.stringify(error))
										$ionicLoading.hide();
								});*/
																																							   			
			document.addEventListener("deviceready", function() {
 
					  var options = {
						replaceLineBreaks: false, // true to replace \n by a new line, false by default
						android: {
						  intent: 'INTENT' // send SMS with the native android SMS messaging
							//intent: '' // send SMS without open any other app
							//intent: 'INTENT' // send SMS inside a default SMS app
						}
					  };
 
  					$scope.sendSMS = function() {
						//alert($scope.contactFromDB[selectedIndex].phone)
					$cordovaSms
					  .send($scope.contactFromDB[selectedIndex].phone, 'This is some dummy text', options)
					  .then(function() {
									 $scope.showSuccessPopup('Success','Invitation SMS Sent successfully')
						//alert('Success');
						// Success! SMS was sent
					  }, function(error) {
						//alert('Error');
						// An error occurred
						$scope.showSuccessPopup('Sorry!!','Invitation SMS not sent.Please check your balance')
					  });
				  }
			});																																							
																																										
			$scope.sendSMS();																																							
																																										
		}
		//ControVarAccessFac.setData("other_user_ID",elementId);
		//alert(elementId+">>>"+$scope.contactFromDB[elementId].user_id+">>>"+$scope.contactFromDB[elementId].grid)
		//$state.go('main.profile-other');
	}
	
	
	
	$scope.populateContact =function(){
		  var users = $scope.users;
		  var log = [];
		
		  $scope.alphabet = iterateAlphabet();
		
		  //Sort user list by first letter of name
		  var tmp={};
		  for(i=0;i<users.length;i++){
			var letter=users[i].fullname.toUpperCase().charAt(0);
			if( tmp[ letter] ==undefined){
			  tmp[ letter]=[]
			}
			  tmp[ letter].push( users[i] );
			  console.log(letter+" >>> "+users[i].fullname)
		  }
		  $scope.sorted_users = tmp;
		  
	}
  //Click letter event
  $scope.gotoList = function(id){
       //alert(id)
    $location.hash(id);
    $ionicScrollDelegate.anchorScroll();
  }

  //Create alphabet object
  function iterateAlphabet()
  {
     var str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     var numbers = new Array();
     for(var i=0; i<str.length; i++)
     {
        var nextChar = str.charAt(i);
        numbers.push(nextChar);
     }
     return numbers;
  }
  
  
  $scope.groups = [];
  for (var i=0; i<10; i++) {
    $scope.groups[i] = {
      name: i,
      items: []
    };
    for (var j=0; j<3; j++) {
      $scope.groups[i].items.push(i + '-' + j);
    }
  }
  
  /*
   * if given group is the selected group, deselect it
   * else, select the given group
   */
  $scope.toggleGroup = function(group) {
    if ($scope.isGroupShown(group)) {
      $scope.shownGroup = null;
    } else {
      $scope.shownGroup = group;
    }
  };
  $scope.isGroupShown = function(group) {
    return $scope.shownGroup === group;
  };
	
	/************************ */
	 /* success message pop up */
   $scope.showSuccessPopup = function(title,txtMsg) {
	   
  		$scope.data = {};

  // An elaborate, custom popup
  var myPopup = $ionicPopup.show({
    template: '',
    title: title,
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