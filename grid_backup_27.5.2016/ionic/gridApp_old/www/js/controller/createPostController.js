gridApp.controller('CreatePostCtrl',function($scope,$state,$http,ControVarAccessFac,$ionicSideMenuDelegate,$cordovaGeolocation, $ionicLoading,$ionicPlatform,$cordovaDatePicker,$ionicPopup,$ionicTabsDelegate,$rootScope){
		/*
			selectedDate:'Sunday, 10Nov 2015',
			selectedTime:'10:15 AM',
			
			selectedDate:'Select Date and Time',
			selectedTime:'',
			
			*/
		console.log(ControVarAccessFac.getData("user_ID"))
		$scope.createPost = {
			address: '',
			postType:'',
			selectedDate:'Select Date and Time',//'Sunday,1May 2016  6.30 p.m',
			selectedTime:'',
			title:'',
			joineType:'',
			payeeType:'',
			summery:'',
			latLong:'',
			selectedNumberOfPeople:0,
			user_id:ControVarAccessFac.getData("userID"),
			place:'',
			latVal:'',
			longVal:'',
			postPlace:''
			
		};
		var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
		$scope.createPostResponse='';
		
		$scope.items = ["Select the number of people",1,2,3,4,5,6,7,8,9,10];
  		$scope.data = {};
  		$scope.data.index = 0;
		$scope.selectedNumberOfPeople;
		
		
		
		$scope.$on('$ionicView.enter', function (viewInfo, state) {
			//$ionicTabsDelegate.select(0);
			console.log("createpost_tab>>> "+ControVarAccessFac.getData("createpost_tab"))
			if(ControVarAccessFac.getData("createpost_tab")==3){
				$ionicTabsDelegate.$getByHandle('createpost-tabs').select(3);
				ControVarAccessFac.setData("createpost_tab",0)
			}else{
				$ionicTabsDelegate.$getByHandle('createpost-tabs').select(0)
			}
			
		});
		
		
		$scope.stepOneNext=function(){
			
			if($scope.createPost.postType!=''&&$scope.createPost.selectedDate!='Select Date and Time'&&$scope.createPost.title!=''&&$scope.createPost.selectedNumberOfPeople!=0){
				//$ionicTabsDelegate.select(1);
				$ionicTabsDelegate.$getByHandle('createpost-tabs').select(1);
				$scope.onCrtPostStepTwoTabSelct();
			}else{
				var createPostErrorMsg="";
				if($scope.createPost.postType==''){
					createPostErrorMsg+="Select Event Type in Step1"+"<br>"
				}
				if($scope.createPost.selectedDate=='Select Date and Time'){
					createPostErrorMsg+="Select Event Planning Date and Time in Step1"+"<br>"
				}
				if($scope.createPost.title==''){
					createPostErrorMsg+="Give Event Tittle in Step1"+"<br>"
				}
				if($scope.createPost.selectedNumberOfPeople==0){
					createPostErrorMsg+="Select Number Of People in Step1"+"<br>"
				}
				$ionicPopup.alert({
						 title: 'Please fill the following..',
						 content: createPostErrorMsg
				})
			}
		}
		
		$scope.stepTwoNext=function(){
			if($scope.createPost.address!=''){
				//$ionicTabsDelegate.select(2);
				$ionicTabsDelegate.$getByHandle('createpost-tabs').select(2);
			}else{
					$ionicPopup.alert({
						 title: 'Please fill the following..',
						 content: 'Please Select Event Place'
				})
			}
			//$scope.onCrtPostStepThreeTabSelct();
		}
		
		$scope.stepTwoBack=function(){
			//$ionicTabsDelegate.select(0);
			$ionicTabsDelegate.$getByHandle('createpost-tabs').select(0);
		}
		
		$scope.stepThreeNext=function(){
			if($scope.createPost.joineType!=''&&$scope.createPost.payeeType!=''&&$scope.createPost.summery!=''){
				//$ionicTabsDelegate.select(3);
				$ionicTabsDelegate.$getByHandle('createpost-tabs').select(3);
				ControVarAccessFac.setData("createpost_tab",3)
				$scope.onCrtPostStepFourTabSelct();
			}else{
				var createPostErrorMsg="";
				if($scope.createPost.joineType==''){
					createPostErrorMsg+="Select Who can join in the Event in Step3"+"<br>"
				}
				if($scope.createPost.payeeType==''){
					createPostErrorMsg+="Select Who will Pay in Step3"+"<br>"
				}
				if($scope.createPost.summery==''){
					createPostErrorMsg+="Give Event Summery in Step3"+"<br>"
				}
				$ionicPopup.alert({
						 title: 'Please fill the following..',
						 content: createPostErrorMsg
				})
			}
		}
		$scope.stepThreBack=function(){
			
			//$ionicTabsDelegate.select(1);
			$ionicTabsDelegate.$getByHandle('createpost-tabs').select(1);
			
		}
		
		$scope.stepFourBack=function(){
			//$ionicTabsDelegate.select(2);
			$ionicTabsDelegate.$getByHandle('createpost-tabs').select(2);
			
		}
		
		
		
  
 		$scope.selectedPeople = function() {
    		//alert($scope.items[$scope.data.index]);
			if($scope.data.index!=0){
				$scope.createPost.selectedNumberOfPeople=$scope.items[$scope.data.index]
			}else{
				$scope.createPost.selectedNumberOfPeople=0
			}
  		}
		
		
		$scope.selectPostType = function(type) {		
          $scope.createPost.postType=type;
   		}
		 
		 $scope.setDateAndTime_dummy= function() {
			 $scope.createPost.selectedDate="Tuesday, May 23rd 2016";
			 $scope.createPost.selectedTime="8:30 pm";
		 }
		$scope.setDateAndTime= function() {
			var options = {
			date: new Date(),
			mode: 'datetime', // or 'time'
			minDate: new Date() - 10000,
			allowOldDates: false,
			allowFutureDates: true,
			doneButtonLabel: 'DONE',
			doneButtonColor: '#000000',
			cancelButtonLabel: 'CANCEL',
			cancelButtonColor: '#000000'
		  };
		  $cordovaDatePicker.show(options).then(function(date){
					/*alert("Month"+date.getMonth());
					alert("Day"+date.getDay());
					alert("Year"+date.getFullYear());
        			alert("Hours"+date.getHours());
					alert("Min"+date.getMinutes());*/
					/*
					var monthName = "";
                switch (date.getMonth()) {
                    case 0:
                        monthName = "Jan";
                        break;
                    case 1:
                        monthName = "Feb";
                        break;
                    case 2:
                        monthName = "Mar";
                        break;
                    case 3:
                        monthName = "Apr";
                        break;
                    case 4:
                        monthName = "May";
                        break;
                    case 5:
                        monthName = "Jun";
                        break;
                    case 6:
                        monthName = "Jul";
                        break;
                    case 7:
                        monthName = "Aug";
                        break;
                    case 8:
                        monthName = "Sept";
                        break;
                    case 9:
                        monthName = "Oct";
                        break;
                    case 10:
                        monthName = "Nov";
                        break;
                    case 11:
                        monthName = "Dec";
                        break;
                }
                var day = "";
                switch (date.getDay()) {
                    case 0:
                        day = "Sunday";
                        break;
                    case 1:
                        day = "Monday";
                        break;
                    case 2:
                        day = "Tuesday";
                        break;
                    case 3:
                        day = "Wednesday";
                        break;
                    case 4:
                        day = "Thursday";
                        break;
                    case 5:
                        day = "Friday";
                        break;
                    case 6:
                        day = "Saturday";
                        break;
                }
					var format;
                    var hours;*/
					var currentDate=new Date()
					//alert("Selected Time>> "+date.getTime()+"currentTime>>> "+currentDate.getTime())
					
					
					if(date.getTime()>currentDate.getTime()){
						/*var minute=date.getMinutes();
						if (date.getHours() < 12) {
							hours = date.getHours();
							format = "AM";
						} else {
							hours = date.getHours() - 12;
							format = "PM";
						}*/
						
						//$scope.createPost.selectedDate=day+","+date.getDate()+""+monthName+""+date.getFullYear();
						//$scope.createPost.selectedDate=day+","+" "+monthName+" "+date.getDate()+" "+date.getFullYear();
						//$scope.createPost.selectedTime=hours+":"+minute+" "+format;
						//alert($scope.createPost.selectedDate)
						$scope.createPost.selectedDate=moment(date).format("dddd, MMMM Do YYYY");
						$scope.createPost.selectedTime=moment(date).format("h:mm a");
						
					}else{
						$ionicPopup.alert({
						 title: 'Date-Time Selection Error',
						 content: 'Selected Date and Time must be ahead of the current Date and Time'
						})
						$scope.createPost.selectedDate='Select Date and Time';//'Sunday,1May 2016  6.30 p.m',
						$scope.createPost.selectedTime='';
					}
    	  });
		}
		
		
		/* This is very Important part to solve android and Ios autocomplete place search Tap issue*/		
		$scope.disableTap = function(){
    		container = document.getElementsByClassName('pac-container');
   			 // disable ionic data tab
    		angular.element(container).attr('data-tap-disabled', 'true');
    		// leave input field if google-address-entry is selected
   		 angular.element(container).on("click", function(){
        		document.getElementById('map-input').blur();
				//alert("called...")
   		 });
 	 	}
		
		
		
		
		
		$scope.onCrtPostStepTwoTabSelct=function(){
			
			$ionicPlatform.ready(function() {
				$ionicLoading.show({
					template: '<ion-spinner icon="bubbles"></ion-spinner><br/>Acquiring location!'
				});
				 
				var posOptions = {
					enableHighAccuracy: true,
					timeout: 20000,
					maximumAge: 0
				};
				
				$cordovaGeolocation.getCurrentPosition(posOptions).then(function (position) {
					var lat  = position.coords.latitude;
					var long = position.coords.longitude;
					 
					var myLatlng = new google.maps.LatLng(lat, long);
					 $scope.createPost.latLong=myLatlng;
					var mapOptions = {
						center: myLatlng,
						zoom: 10,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};          
					 
					var map = new google.maps.Map(document.getElementById("mapCreate"), mapOptions); 
					
					var marker = new google.maps.Marker({
								map: map,
								anchorPoint: new google.maps.Point(0, -29),
								draggable:true,
					});
					
					
					var input = (document.getElementById('map-input'));
					//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
					 var infowindow = new google.maps.InfoWindow();
					var autocomplete = new google.maps.places.Autocomplete(input);
					autocomplete.bindTo('bounds', map);
				

					autocomplete.addListener('place_changed', function() {
							
							infowindow.close();
							marker.setVisible(false);
							 var place = autocomplete.getPlace();
							$scope.createPost.place=place;
							//if(ControVarAccessFac.getData("user_subscription")=="freeuser")
						var postDistance=distance(lat,long,place.geometry.location.lat(), place.geometry.location.lng())
						var userType=ControVarAccessFac.getData("user_subscription")//=="freeuser"
						console.log(postDistance+">>>"+userType)
							if((postDistance<=ControVarAccessFac.getData("map_radious") && userType=="freeuser" )||(postDistance<=ControVarAccessFac.getData("map_radious") && userType=="paiduser"))
							{
							 if (!place.geometry) {								 
								  $ionicPopup.alert({
												  title: 'Map Error',
												  content: "Autocomplete's returned place contains no geometry"
												})
								  return;
   							 }

							// If the place has a geometry, then present it on a map.
							if (place.geometry.viewport) {
							  map.fitBounds(place.geometry.viewport);
							} else {
							  map.setCenter(place.geometry.location);
							  map.setZoom(15);  // Why 17? Because it looks good.
							}
							/*var address = '';
							if (place.address_components) {
							  address = [
								(place.address_components[0] && place.address_components[0].short_name || ''),
								(place.address_components[1] && place.address_components[1].short_name || ''),
								(place.address_components[2] && place.address_components[2].short_name || '')
							  ].join(',');
							}*/
							console.log("place location::"+place.geometry.location.lat()+":::"+place.geometry.location.lng())
							$scope.createPost.postPlace=place.name;
							$scope.createPost.address=$scope.createPost.postPlace+" : "+place.formatted_address;
							
							//alert("place"+place.formatted_address)
							$scope.createPost.latVal=place.geometry.location.lat()
							$scope.createPost.longVal=place.geometry.location.lng()
							
							marker.setPosition(place.geometry.location);
   							marker.setVisible(true);
							
					google.maps.event.addListener(marker, 'dragend', function(evt){
							//console.log('Current Latitude:',evt.latLng.lat(),'Current Longitude:',evt.latLng.lng());
							
						var postDistance=distance(lat,long,evt.latLng.lat(), evt.latLng.lng())
						var userType=ControVarAccessFac.getData("user_subscription")//=="freeuser"
						console.log(postDistance+">>>"+userType)
							if((postDistance<=ControVarAccessFac.getData("map_radious") && userType=="freeuser" )||(postDistance<=ControVarAccessFac.getData("map_radious") && userType=="paiduser"))
							{
							
							var geocoder = new google.maps.Geocoder();
							var latlng = {lat: parseFloat(evt.latLng.lat()), lng: parseFloat(evt.latLng.lng())};
							$scope.createPost.latLong=latlng;
							$scope.createPost.latVal=parseFloat(evt.latLng.lat());
							$scope.createPost.longVal=parseFloat(evt.latLng.lng());
							
								 geocoder.geocode({'location': latlng}, function(results, status) {
 									if (status === google.maps.GeocoderStatus.OK) {
										//console.log(results[1].formatted_address)
										$scope.createPost.postPlace=results[1].name;
										$scope.createPost.address=$scope.createPost.postPlace+" : "+results[1].formatted_address;
										
										//alert("$scope.createPost.address"+$scope.createPost.address)
										$scope.$apply()
									}

								});
							}else{
								$scope.createPost.latLong=''
								$scope.createPost.place='';
								$scope.createPost.address='';
								$scope.createPost.latVal='';
								$scope.createPost.longVal='';
								marker.setVisible(false);
								$ionicPopup.alert({
												  title: 'Location Selection Error',
												  content: 'Being a free user your area radius is limited. Subscribe to become a paid member to extend your radius.'
								})
							}
							
							});

							//map.removeOverlay(marker);
							//$scope.setPosition();
							}else{
								$ionicPopup.alert({
												  title: 'Location Select Error',
												  content: 'Being a free user your area radius is limited. Subscribe to become a paid member to extend your radius.'
								})
								$scope.createPost.place='';
								$scope.createPost.address='';
								$scope.createPost.latVal='';
								$scope.createPost.longVal='';
								marker.setVisible(false);
							}
					});
					
					$scope.map = map; 
					
					$ionicLoading.hide(); 
					
					
					
					//===================get distance between two points =============
							
								function distance(lat1, lon1, lat2, lon2, unit) {
								var radlat1 = Math.PI * lat1/180
								var radlat2 = Math.PI * lat2/180
								var theta = lon1-lon2
								var radtheta = Math.PI * theta/180
								var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
								dist = Math.acos(dist)
								dist = dist * 180/Math.PI
								dist = dist * 60 * 1.1515
								if (unit=="K") { dist = dist * 1.609344 }
								if (unit=="N") { dist = dist * 0.8684 }
								return dist
								}
								
								//alert(distance(lat, long,place.geometry.location.lat(), place.geometry.location.lng()))
				//=====================================================================
					
					
					  
				}, function(err) {
					$ionicLoading.hide();
					console.log(err);
				});
    		})
		}
		
		
		$scope.selectJoineType = function(type) {		
			  $scope.createPost.joineType=type;
		}
		
		$scope.selectPayeeType = function(type) {		
			  $scope.createPost.payeeType=type;
		}
		//onCrtPostStepFourTabSelct
		
		$scope.onCrtPostStepFourTabSelct=function(){
			$ionicPlatform.ready(function() {
										  
				/*var mapOptions = {
						center: $scope.createPost.latLong,
						zoom: 10,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};          
					 
					var mapView = new google.maps.Map(document.getElementById("mapView"), mapOptions);
					mapView.setCenter($scope.createPost.place.geometry.location);
					 var marker = new google.maps.Marker({
								map: mapView,								
								draggable:false,
							  });
				marker.setPosition($scope.createPost.place.geometry.location);		*/				  
				
				/*$ionicLoading.show({
					template: '<ion-spinner icon="bubbles"></ion-spinner><br/>Acquiring location!'
				});*/
				var posOptions = {
					enableHighAccuracy: true,
					timeout: 20000,
					maximumAge: 0
				};
				$cordovaGeolocation.getCurrentPosition(posOptions).then(function (position) {
					
					var mapOptions = {
						center: $scope.createPost.latLong,
						zoom: 15,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};          
					 
					var mapView = new google.maps.Map(document.getElementById("mapView"), mapOptions);
					mapView.setCenter($scope.createPost.place.geometry.location);
					 var marker = new google.maps.Marker({
								map: mapView,								
								draggable:false,
							  });
					marker.setPosition($scope.createPost.place.geometry.location);
					
					//$ionicLoading.hide(); 															  
				});
				
			});
		};
		
		$scope.makeFeaturedPost=function(){
			ControVarAccessFac.setData("payment_type","featured");
			$state.go('payment');
		}
		
		
		
		
		$rootScope.submitPost=function(featuredVal,trans_id){
		
			if($scope.createPost.address!=''&&$scope.createPost.postType!=''&&$scope.createPost.selectedDate!='Select Date and Time'&&$scope.createPost.title!=''&&$scope.createPost.joineType!=''&&$scope.createPost.payeeType!=''&&$scope.createPost.summery!=''&&$scope.createPost.longVal!=''&& $scope.createPost.latVal!=''){
				
				$ionicLoading.show({
						template: '<ion-spinner icon="android"></ion-spinner>'
				});
				
				var address = $scope.createPost.address;
					
						//alert("submit post:: "+$scope.createPost.user_id)
							//alert($scope.createPost.selectedDate)
							//alert($scope.createPost.selectedTime)
							var link = 'http://grid.digiopia.in/user/create_event';
							$http.post(link, {user_id : ControVarAccessFac.getData("user_ID"),
								  event_type : $scope.createPost.postType,								 
								  date:$scope.createPost.selectedDate,
								  time:$scope.createPost.selectedTime,
								  maxpersonallowed :$scope.createPost.selectedNumberOfPeople,
								  post_title : $scope.createPost.title,
								  post_lat:$scope.createPost.latVal,
								  post_long:$scope.createPost.longVal,
								  post_location : $scope.createPost.address,
								  joinee : $scope.createPost.joineType,
								  payee : $scope.createPost.payeeType,
								  description : $scope.createPost.summery,
								  is_featured:featuredVal,
								  transaction_id:trans_id
							},config).then(function (res){
											$ionicLoading.hide();
										  $scope.createPostResponse=JSON.stringify(res);
										  	//alert("Registration succes.."+res.data.message)
										  	//alert("Registration succes.."+$scope.registerResponse.message)
											//$scope.response = res.data;
											
										ControVarAccessFac.setData("post_createdDate",$scope.createPost.selectedDate);
											if(res.data.status==true){
												$scope.createPost.postType='';
												$scope.createPost.selectedDate='Select Date and Time';
												$scope.createPost.selectedTime='';
												$scope.createPost.selectedNumberOfPeople=0;
												$scope.data.index=0;
												$scope.createPost.title='';
												$scope.createPost.address='';
												$scope.createPost.joineType='';
												$scope.createPost.payeeType='';
												$scope.createPost.summery='';
												$scope.createPost.place='';
												$scope.createPost.latVal='';
												$scope.createPost.longVal='';
												$scope.createPost.postPlace='';
												ControVarAccessFac.setData("post_tab",2);
												ControVarAccessFac.setData("createpost_tab",0)
										  		$state.go('main.post');
											}else{
												$ionicPopup.alert({
												  title: 'Create Post Fail',
												  content: res.data.message
												})
												//$scope.register.email="";
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
				var createPostErrorMsg="";
				if($scope.createPost.postType==''){
					createPostErrorMsg+="Select Event Type in Step 1"+"<br>"
				}
				if($scope.createPost.selectedDate=='Select Date and Time'){
					createPostErrorMsg+="Select Event Planning Date and Time in Step 1"+"<br>"
				}
				if($scope.createPost.title==''){
					createPostErrorMsg+="Give Event Tittle in Step 1"+"<br>"
				}
				if($scope.createPost.address==''){
					createPostErrorMsg+="Select Event Place in Step 2"+"<br>"
				}
				if($scope.createPost.joineType==''){
					createPostErrorMsg+="Select Who can join in the Event in Step 3"+"<br>"
				}
				if($scope.createPost.payeeType==''){
					createPostErrorMsg+="Select Who will Pay in Step 3"+"<br>"
				}
				if($scope.createPost.summery==''){
					createPostErrorMsg+="Give Event Summery in Step 3"+"<br>"
				}
				$ionicPopup.alert({
						 title: 'Please fill the following..',
						 content: createPostErrorMsg
				})
			}
		}
		
		
		/* coinfirm message oppup */
	
		// A confirm dialog
		 $scope.showFeaturePop = function() {
			 var msg='Get your post Featured to be on top! Let your event grab all the attention!';
			 
			// Are you sure to feature your post?
			 
		   var confirmPopup = $ionicPopup.confirm({
			 title: 'Feature Post',
			 template: msg
		   });
		
		   confirmPopup.then(function(res) {
			 if(res) {
				 $scope.makeFeaturedPost()
			   //console.log('You are sure');
			 } else {
			   //console.log('You are not sure');
			 }
		   });
		 };
		
		
});