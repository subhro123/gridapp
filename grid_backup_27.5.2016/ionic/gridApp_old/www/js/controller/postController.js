gridApp.controller('PostCtrl',function($scope,$state,$ionicLoading,$http,ControVarAccessFac,$cordovaGeolocation,$ionicPlatform,$cordovaDatePicker,$ionicPopup,$filter,$ionicTabsDelegate,$ionicPopover,$compile,$ionicHistory){
		
		$scope.post = {
			post_lat: '',
			post_long:'',
			current_lat:'',
			current_long:'',
			radius:ControVarAccessFac.getData("map_radious"),
			user_id:ControVarAccessFac.getData("user_ID"),
			postResponse:'',
			postDate:'',
			selectedDate:moment().format("dddd, MMMM Do YYYY"),//$filter('date')(new Date()),
			dateSelectType:'',
			postView:true,
			reault_event_latArr:[],
			reault_event_longArr:[],
			mapViewMarkerIconArr:[],
			mapViewPost:'',
			markersArr:[],
			circle:'',
			filterType:'',
			postFilter:'dining',
			tabselected:'myfriend'
			
		};
		
		var input ='';
		var infowindow = '';
		var autocomplete = '';
		
		$scope.postResponse=[];
		$scope.postResponseFilter=[];
		
		var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
		
		
		$ionicPopover.fromTemplateUrl('templates/popup/post-search-pop.html', {
    			scope: $scope,
				animation: 'am-flip-x-linear'
  		}).then(function(popover) {
   			 $scope.popover = popover;
  		});
		
		$scope.openPopover = function($event) {
   			 $scope.popover.show($event);
  		};
  		$scope.closePopover = function() {
   			 $scope.popover.hide();
 		};
		
		/* This is very Important part to solve android and Ios autocomplete place search Tap issue*/		
		$scope.disableTap = function(){
			console.log("xxxxx_myfriend")
    		container = document.getElementsByClassName('pac-container');
   			 // disable ionic data tab
    		angular.element(container).attr('data-tap-disabled', 'true');
			//var backdrop = document.getElementsByClassName('backdrop');
           //	angular.element(backdrop).attr('data-tap-disabled', 'true');
    		// leave input field if google-address-entry is selected
			
   		 angular.element(container).on("click", function(){
        		document.getElementById('locationSearch').blur();
				//document.getElementsByClassName('locationSearch_around').blur();
				//document.getElementsByClassName('locationSearch_myevent').blur();
				//alert("called...")
   		 });
 	 	}
		
		$scope.disableTap_around = function(){
			console.log("xxxxxxxx_around")
    		container = document.getElementsByClassName('pac-container');
   			 // disable ionic data tab
    		angular.element(container).attr('data-tap-disabled', 'true');
			//var backdrop = document.getElementsByClassName('backdrop');
                //angular.element(backdrop).attr('data-tap-disabled', 'true');
    		// leave input field if google-address-entry is selected
   		 angular.element(container).on("click", function(){
        		document.getElementById('locationSearch_around').blur();
				//alert("called...")
   		 });
 	 	}
		
		$scope.disableTap_myevent = function(){
			console.log("xxxxxxxx_myevent")
    		container = document.getElementsByClassName('pac-container');
   			 // disable ionic data tab
    		angular.element(container).attr('data-tap-disabled', 'true');
			//var backdrop = document.getElementsByClassName('backdrop');
                //angular.element(backdrop).attr('data-tap-disabled', 'true');
    		// leave input field if google-address-entry is selected
   		 angular.element(container).on("click", function(){
        		document.getElementById('locationSearch_myevent').blur();
				//alert("called...")
   		 });
 	 	}
		
		$scope.checkSearchEmpty = function(){
			console.log("checkSearchEmpty..called...")
			if(document.getElementById('locationSearch').value==''){
				$scope.post.post_lat  =  $scope.post.current_lat;
				$scope.post.post_long = $scope.post.current_long ;
				console.log("location default"+$scope.post.post_lat+">>>"+$scope.post.post_long)
				$scope.populateEventList($scope.post.tabselected);
			}
 	 	}
		
		$scope.checkSearchEmpty_around = function(){
			
			if(document.getElementById('locationSearch_around').value==''){
				$scope.post.post_lat  =  $scope.post.current_lat;
				$scope.post.post_long = $scope.post.current_long ;
				console.log("location default"+$scope.post.post_lat+">>>"+$scope.post.post_long)
				$scope.populateEventList($scope.post.tabselected);
			}
 	 	}
		
		$scope.checkSearchEmpty_myevent = function(){
			
			if(document.getElementById('locationSearch_myevent').value==''){
				$scope.post.post_lat  =  $scope.post.current_lat;
				$scope.post.post_long = $scope.post.current_long ;
				console.log("location default"+$scope.post.post_lat+">>>"+$scope.post.post_long)
				$scope.populateEventList($scope.post.tabselected);
			}
 	 	}
		
		
		
		$scope.postFilterByType=function(postFilterType){
			console.log("postFilterType:: "+postFilterType)
			$scope.post.postFilter=postFilterType;
			if(postFilterType!="all"){
			$scope.postResponseFilter = $filter('filter')($scope.postResponse, { event_type: postFilterType });				
			}else{
			$scope.postResponseFilter =$scope.postResponse;	
			}
			
			if($scope.postResponseFilter.length==0){
					$ionicPopup.alert({
							title: 'Ooops..!!!',
							content: "No Post Record Found...!!"
					})
					//if($scope.post.postView==false){
						$scope.post.reault_event_latArr=[];
						$scope.post.reault_event_longArr=[];
						$scope.post.mapViewMarkerIconArr=[];
						if($scope.post.markersArr.length!=0){
							for(var i=0;i<$scope.post.markersArr.length;i++){
								$scope.post.markersArr[i].setMap(null);
							}
							$scope.post.circle.setMap(null)
						}
						$scope.post.markersArr=[];
					//}
			}else{					
					$scope.post.reault_event_latArr=[];
					$scope.post.reault_event_longArr=[];
					$scope.post.mapViewMarkerIconArr=[];
					
					for(var i=0;i<$scope.postResponseFilter.length;i++){											
						$scope.post.reault_event_latArr[i]=Number($scope.postResponseFilter[i].post_lat)
						$scope.post.reault_event_longArr[i]=Number($scope.postResponseFilter[i].post_long)
						$scope.post.mapViewMarkerIconArr[i]=$scope.getPostMapMarkerIcon($scope.getPostIcon($scope.postResponseFilter[i].event_type,$scope.postResponseFilter[i].event_status),$scope.postResponseFilter[i].event_date_timestamp);
					}
				if($scope.post.postView==false){				
					$scope.createPostMapView();
				}
			}
			
			//activity/drinks/dining
			console.log($scope.postResponse)
			//$scope.apply();
			$scope.popover.hide();
		}

		/*$scope.$on('$ionicView.leave', function () {
				$scope.post.dateSelectType='';
				alert("$ionicView.leave called")
		});*/
		
			$scope.$on('$ionicView.enter', function (viewInfo, state) {
													 
					console.log("page enter................")
					console.log("tabselected"+$scope.post.tabselected)
					console.log("Back Title:: "+$ionicHistory.backTitle())			
					console.log("selectedDate post:: "+$scope.post.selectedDate)
					
					$scope.post.radius=ControVarAccessFac.getData("map_radious")
					$scope.postResponse=[];
					$scope.postResponseFilter=$scope.postResponse;
					$scope.post.dateSelectType='calendar';
					document.getElementById('locationSearch').value='';					
					//document.getElementById('locationSearch_around').value='';
					//document.getElementById('locationSearch_myevent').value='';
					
					if(ControVarAccessFac.getData("post_tab")==2){
						//$ionicTabsDelegate.select(2);
						
						$ionicTabsDelegate.$getByHandle('post-tabs').select(2)
						//alert("tab selected = 2")
						//$scope.post.tabselected='myevent';				
						//ControVarAccessFac.setData("post_tab",0);
						//$scope.populateEventList($scope.post.tabselected);
						
					}else{
						//alert("tab selected = xxx")
						if($scope.post.tabselected=='myfriend')	{
							$scope.post.selectedDate=ControVarAccessFac.getData("myFriendSelectedDate");
						}else if($scope.post.tabselected=='around'){				
							$scope.post.selectedDate=ControVarAccessFac.getData("arroundSelectedDate");
						}else if($scope.post.tabselected=='myevent'){
							$scope.post.selectedDate=ControVarAccessFac.getData("myEventSelectedDate");
						}
						//$scope.populateEventList($scope.post.tabselected);
						
					}								 
													 
													 
												 
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
							$scope.post.post_lat  = position.coords.latitude;
							$scope.post.post_long = position.coords.longitude;
							$scope.post.current_lat  = position.coords.latitude;
							$scope.post.current_long = position.coords.longitude;
							var myLatlng = new google.maps.LatLng($scope.post.post_lat, $scope.post.post_long);
							var mapOptions = {
											center: myLatlng,
											zoom: 10,
											mapTypeId: google.maps.MapTypeId.ROADMAP
							};          
						if($scope.post.tabselected=='myfriend')	{		 
							$scope.post.mapViewPost = new google.maps.Map(document.getElementById("postMapView_myfriend"), mapOptions);				//input = (document.getElementById('locationSearch'));
							
						}else if($scope.post.tabselected=='around'){
							$scope.post.mapViewPost = new google.maps.Map(document.getElementById("postMapView_around"), mapOptions);
							//input = (document.getElementById('locationSearch_around'));
						}else if($scope.post.tabselected=='myevent'){
							$scope.post.mapViewPost = new google.maps.Map(document.getElementById("postMapView_myevent"), mapOptions);
							//input = (document.getElementById('locationSearch_myevent'));
						}
							
							/*infowindow = new google.maps.InfoWindow();
							autocomplete = new google.maps.places.Autocomplete(input);
							autocomplete.bindTo('bounds', $scope.post.mapViewPost);	
							autocomplete.addListener('place_changed', $scope.onPlaceChangedHandler);*/
							
							$ionicLoading.hide();							
								//$scope.populateEventList('myfriend');
								if(ControVarAccessFac.getData("post_tab")==2){
									if(ControVarAccessFac.getData("post_createdDate")==undefined||ControVarAccessFac.getData("post_createdDate")=="undefined"){
										$scope.post.selectedDate=ControVarAccessFac.getData("myEventSelectedDate");
									}else{
										$scope.post.selectedDate=ControVarAccessFac.getData("post_createdDate");
									}
									
									
									$scope.post.dateSelectType='calendar';
									$scope.post.post_lat  =  $scope.post.current_lat;
									$scope.post.post_long = $scope.post.current_long ;
									$scope.populateEventList('myevent');
								}else{
									$scope.populateEventList($scope.post.tabselected);
								}
								
								
							
						});
					
					});
					
					$scope.populateEventList=function(filterType){
					$scope.post.reault_event_latArr=[];
					$scope.post.reault_event_longArr=[];
					$scope.post.mapViewMarkerIconArr=[];
				
					$scope.post.filterType=filterType;
				
				console.log("filterType:  "+$scope.post.filterType)
				console.log("event_date:  "+$scope.post.selectedDate)
				//alert($scope.post.post_lat.toString()+$scope.post.post_long.toString()+$scope.post.radius+$scope.post.selectedDate+$scope.post.dateSelectType)
					$ionicLoading.show({
								template: '<ion-spinner icon="android"></ion-spinner>'
							});
							var link = 'http://grid.digiopia.in/user/display_event';
							$http.post(link, {
									post_lat : $scope.post.post_lat,
									post_long: $scope.post.post_long,
							  		radius:$scope.post.radius,
									event_date:$scope.post.selectedDate,
									date_select_type:$scope.post.dateSelectType,
									user_id:ControVarAccessFac.getData("user_ID"),
									post_filter:$scope.post.filterType
							},config).then(function (res){
									$ionicLoading.hide();
									//$scope.postResponse=JSON.stringify(res);
									if(res.data.status==true){
										/*$scope.post.selectedDate='';
										$scope.post.dateSelectType='';*/
										
										if(res.data.response!=''){
											$scope.postResponse=res.data.response;
											$scope.postResponseFilter=$scope.postResponse;
											$scope.post.postDate=$scope.postResponse[0].formated_date;
											
											if($scope.post.tabselected=='myfriend')	{		 
											ControVarAccessFac.setData("myFriendSelectedDate",$scope.post.postDate);
											input = (document.getElementById('locationSearch'));
											}else if($scope.post.tabselected=='around'){
											ControVarAccessFac.setData("arroundSelectedDate",$scope.post.postDate);
											input = (document.getElementById('locationSearch_around'));
											}else if($scope.post.tabselected=='myevent'){
											ControVarAccessFac.setData("myEventSelectedDate",$scope.post.postDate);
											input = (document.getElementById('locationSearch_myevent'));
											}
											
											infowindow = new google.maps.InfoWindow();
											autocomplete = new google.maps.places.Autocomplete(input);
											autocomplete.bindTo('bounds', $scope.post.mapViewPost);	
											autocomplete.addListener('place_changed', $scope.onPlaceChangedHandler);
											
											
											$scope.post.reault_event_latArr=[];
											$scope.post.reault_event_longArr=[];
											$scope.post.mapViewMarkerIconArr=[];
											
											for(var i=0;i<$scope.postResponse.length;i++){											
											$scope.post.reault_event_latArr[i]=Number($scope.postResponse[i].post_lat)
											$scope.post.reault_event_longArr[i]=Number($scope.postResponse[i].post_long)
								$scope.post.mapViewMarkerIconArr[i]=$scope.getPostMapMarkerIcon($scope.getPostIcon($scope.postResponse[i].event_type,$scope.postResponse[i].event_status),$scope.postResponse[i].event_date_timestamp);
											}
											if($scope.post.postView==false){
												$scope.createPostMapView();
											}
											
										}else{
											
											
											if(res.data.message=="No date found!!"){
												$scope.post.postDate=res.data.event_date;
												$ionicPopup.alert({
												  title: 'Oops!',
												  content: "No post can be found on your desired date! Why not check some other date?"
												})
											}else{
												$scope.post.postDate=res.data.event_date;
												$scope.post.reault_event_latArr=[];
												$scope.post.reault_event_longArr=[];
												$scope.post.mapViewMarkerIconArr=[];
												$scope.postResponse=[];
												$scope.postResponseFilter=$scope.postResponse;
												
											if($scope.post.tabselected=='myfriend')	{		 
											ControVarAccessFac.setData("myFriendSelectedDate",$scope.post.postDate);
											}else if($scope.post.tabselected=='around'){
											ControVarAccessFac.setData("arroundSelectedDate",$scope.post.postDate);
											}else if($scope.post.tabselected=='myevent'){
											ControVarAccessFac.setData("myEventSelectedDate",$scope.post.postDate);
											}
												
												$ionicPopup.alert({
												  title: 'Oops!',
												  content: "No post can be found within your area radius! Extend your radius to enjoy unlimited posts."
												})
												
												
												
											}
											//console.log("$scope.post.postDate::"+$scope.post.postDate)
											
											//$scope.post.postDate=$scope.postResponse[0].formated_date;
											
										}
									
									}else{
										$ionicPopup.alert({
										title: 'Post Fail',
												  content: res.data.message
										})
												//$scope.register.email="";
									}
							},function (error){
								$ionicLoading.hide();
								//alert("Post error.."+error)
								$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
							});				
				
				
			}
					$scope.getPostIcon=function(postType,postStatus){
				
				if(postType=='dining' && postStatus==''){
					return 'postType_DnrBg_avail'
				}else if(postType=='dining' && postStatus=='request'){
					return 'postType_DnrBg_pending'
				}else if(postType=='dining' && postStatus=='accept'){
					return 'postType_DnrBg_going'
				}else if(postType=='dining' && postStatus=='unavailable'){
					return 'postType_DnrBg_reserve'
				}else if(postType=='drinks' && postStatus==''){
					return 'postType_DrinkBg_avail'
				}else if(postType=='drinks' && postStatus=='request'){
					return 'postType_DrinkBg_pending'
				}else if(postType=='drinks' && postStatus=='accept'){
					return 'postType_DrinkBg_going'
				}else if(postType=='drinks' && postStatus=='unavailable'){
					return 'postType_DrinkBg_reserve'
				}else if(postType=='activity' && postStatus==''){
					return 'postType_EvntBg_avail'
				}else if(postType=='activity' && postStatus=='request'){
					return 'postType_EvntBg_pending'
				}else if(postType=='activity' && postStatus=='accept'){
					return 'postType_EvntBg_going'
				}else if(postType=='activity' && postStatus=='unavailable'){
					return 'postType_EvntBg_reserve'
				}
			}
			
					$scope.getPostMapMarkerIcon=function(ioconType,timestamp){
				//console.log("ioconType:: "+ioconType)
				if(ioconType=='postType_DnrBg_avail'){
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/dnr_green_map.png'
					}else{
							return 'img/dnr_green_map_exp.png'
					}							
				}else if(ioconType=='postType_DnrBg_pending'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/dnr_orange_map.png'
					}else{
							return 'img/dnr_orange_map_exp.png'
					}
				}else if(ioconType=='postType_DnrBg_going'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/dnr_blue_map.png'
					}else{
							return 'img/dnr_blue_map_exp.png'
					}
				}else if(ioconType=='postType_DnrBg_reserve'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/dnr_red_map.png'
					}else{
							return 'img/dnr_red_map_exp.png'
					}
				}else if(ioconType=='postType_DrinkBg_avail'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/drink_green_map.png'
					}else{
							return 'img/drink_green_map_exp.png'
					}
				}else if(ioconType=='postType_DrinkBg_pending'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/drink_orange_map.png'
					}else{
							return 'img/drink_orange_map_exp.png'
					}
				}else if(ioconType=='postType_DrinkBg_going'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/drink_blue_map.png'
					}else{
							return 'img/drink_blue_map_exp.png'
					}
				}else if(ioconType=='postType_DrinkBg_reserve'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/drink_red_map.png'
					}else{
							return 'img/drink_red_map_exp.png'
					}
				}else if(ioconType=='postType_EvntBg_avail'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/event_green_map.png'
					}else{
							return 'img/event_green_map_exp.png'
					}
				}else if(ioconType=='postType_EvntBg_pending'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/event_orange_map.png';
					}else{
							return 'img/event_orange_map_exp.png';
					}
				}else if(ioconType=='postType_EvntBg_going'){					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/event_blue_map.png'
					}else{
							return 'img/event_blue_map_exp.png'
					}
				}else if(ioconType=='postType_EvntBg_reserve'){
					
					if($scope.checkPostExpirey(timestamp)=='postActive'){
							return 'img/event_red_map.png'
					}else{
							return 'img/event_red_map_exp.png'
					}
				}
			}
			
			$scope.nextBackEvent= function(type) {
				$scope.post.dateSelectType=type;
				$scope.post.selectedDate=$scope.post.postDate;
				$scope.populateEventList($scope.post.filterType);
			}
			
			
			$scope.changePostView=function(){
				$scope.post.postView=!$scope.post.postView;
				if($scope.post.postView==false){
					$scope.createPostMapView();
				}
			}
			
					$scope.createPostMapView=function(){
				//console.log("xxxxxxxx")
				//$scope.post.mapViewPost
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
						
						//$scope.post.post_lat  = position.coords.latitude;
						//$scope.post.post_long = position.coords.longitude;
						var myLatlng = new google.maps.LatLng($scope.post.post_lat, $scope.post.post_long);
						var mapOptions = {
										center: myLatlng,
										zoom: 10,
										mapTypeId: google.maps.MapTypeId.ROADMAP
						}; 
						 
								if($scope.post.tabselected=='myfriend')	{		 
									$scope.post.mapViewPost = new google.maps.Map(document.getElementById("postMapView_myfriend"), mapOptions);
									
								}else if($scope.post.tabselected=='around'){
									$scope.post.mapViewPost = new google.maps.Map(document.getElementById("postMapView_around"), mapOptions);
									
								}else if($scope.post.tabselected=='myevent'){
									$scope.post.mapViewPost = new google.maps.Map(document.getElementById("postMapView_myevent"), mapOptions);
									
								}
								
						
			
			
						$ionicLoading.hide(); 
						
						var  man_marker = new google.maps.Marker({
									  map: $scope.post.mapViewPost,									 
									  position: {lat: $scope.post.post_lat, lng: $scope.post.post_long},
									  title: 'I am here..',
									  icon: 'img/nevigator_man.png'
						});			  
																				  
						if($scope.post.markersArr.length!=0){
							for(var i=0;i<$scope.post.markersArr.length;i++){
								$scope.post.markersArr[i].setMap(null);
							}
							$scope.post.circle.setMap(null)
						}
						$scope.post.markersArr=[];
						var marker;
				
				for(var j=0;j<$scope.post.mapViewMarkerIconArr.length;j++){
									//console.log($scope.post.mapViewMarkerIconArr[j])
									 marker = new google.maps.Marker({
									  map: $scope.post.mapViewPost,									 
									  position: {lat: $scope.post.reault_event_latArr[j], lng: $scope.post.reault_event_longArr[j]},
									  
									  icon: $scope.post.mapViewMarkerIconArr[j]
									});
									 
									 //info window
									 
									 
									 
									/*var contentString = '<div id="content" ng-click="openPostDetailsView("'+$scope.postResponseFilter[j].id+','+$scope.postResponseFilter[j].user_id+')">'+
									  '<div id="siteNotice">'+
									  '</div>'+
									  '<h5 id="firstHeading" class="firstHeading">'+$scope.postResponseFilter[j].post_title+'</h5>'+
									  '<div id="bodyContent">'+
									  '<span class="ion-ios-location gray_color"></span> '+$scope.postResponseFilter[j].post_location+''+
									  '<p><b>@ </b>'+$scope.postResponseFilter[j].formated_time+'</p>'+
									  '<p><b>Posted By: </b>'+$scope.postResponseFilter[j].fullname+'</p>'+
									  '</div>'+
									  '</div>';*/
									  
									  var contentString = '<div id="content" ng-click="openPostDetailsView('+$scope.postResponseFilter[j].id+','+$scope.postResponseFilter[j].user_id+','+$scope.postResponseFilter[j].event_date_timestamp+')">'+
									  '<div id="siteNotice">'+
									  '</div>'+
									  '<h5 id="firstHeading" class="firstHeading">'+$scope.postResponseFilter[j].post_title+'</h5>'+
									  '<div id="bodyContent">'+
									  '<span class="ion-ios-location gray_color"></span> '+$scope.postResponseFilter[j].post_location+''+
									  '<p><b>@ </b>'+$scope.postResponseFilter[j].formated_time+'</p>'+
									  '<p><b>Posted By: </b>'+$scope.postResponseFilter[j].fullname+'</p>'+
									  '</div>'+
									  '</div>';
									  var compiledContent = $compile(contentString)($scope);
									  
									 //marker.content=contentString; 
									 marker.content=compiledContent[0]; 
									 
									  var infoWindow = new google.maps.InfoWindow();
									
									
									google.maps.event.addListener(marker, 'click', function () {
                                		infoWindow.setContent(this.content);
                                			infoWindow.open($scope.post.mapViewPost, this);
                           			});
									 
									$scope.post.markersArr[j]=marker;
				}
				
				// Add circle overlay and bind to marker
				$scope.post.circle = new google.maps.Circle({
								  map: $scope.post.mapViewPost,
								  radius: 1609.34*parseInt($scope.post.radius),//miles    // //10000,metres
								  strokeColor: '#666666',
      							  strokeOpacity: 0.8,
								  strokeWeight: 2,
								  fillColor: '#f7f0f0',//'#FF0000',
								  fillOpacity: 0.15,

				});
				$scope.post.circle.bindTo('center', man_marker, 'position');														  
				});
				
		   });
				
			}

			
			
				/*==================postdetailviewopen ======*/
				
					$scope.openPostDetailsView= function(post_id,user_id,event_date_timestamp){
					//console.log("post_id::"+post_id+">>>>"+"user_id:: "+user_id+":::"+ControVarAccessFac.getData("user_ID"))
					/* console.log(event_date)
					 console.log( "Current_date::::"+moment().format())
					console.log( "Given...event_date::::"+moment(event_date).format())
					
					 var userDate = moment(event_date).format();
					 var currentDate = moment(new Date()).format();
					 console.log( "Current_date::::"+currentDate.getTime())
					console.log( "Given...event_date::::"+userDate.getTime())
					var difference = currentDate.getTime()-userDate.getTime();
					//var difference=currentDate.diff(userDate)
  			console.log("The date entered differs from today's date by " + difference + " milliseconds");*/
			
				//alert(toTimestamp('02/13/2009 23:31:30'))
					//alert(toTimestamp(event_date))
					
					/*function toTimestamp(strDate){
						var datum = Date.parse(strDate);
						return datum/1000;
					}*/
					
					
					 var userDate = event_date_timestamp//new Date(event_date);
					 var currentDate = new Date().getTime()/1000;
					console.log( "event_date::::"+userDate)
					console.log( "currentDate::::"+currentDate)
					var difference = userDate-currentDate;
  			console.log("The date entered differs from today's date by " + difference + " milliseconds");	
			
			
					
					if(difference>0){
						ControVarAccessFac.setData("post_ID",post_id)
						if(user_id==ControVarAccessFac.getData("user_ID")){
							console.log("owner...")
							$state.go('main.post-owner-view');
						}else{
							console.log("other...")
							$state.go('main.post-other-view');
						}
					}else{
						$ionicPopup.alert({
									  title: 'Post Expired',
									  content:  'This Post Has Already Expired.'
						})
					}
					
				}
				/*============================================*/
			/*$scope.setDateAndTimePostDummy= function() {
					$scope.post.dateSelectType='calendar'
					$scope.post.selectedDate="Saturday,30Apr2016"			//day+","+date.getDate()+""+monthName+""+date.getFullYear();
					$scope.populateEventList($scope.post.filterType);
			}*/
			
					$scope.setDateAndTimePost= function() {
				//alert("setDateAndTimePost..called")
				var options = {
				date: new Date(),
				mode: 'date', // or 'time'
				minDate: new Date() - 10000,
				allowOldDates: false,
				allowFutureDates: true,
				doneButtonLabel: 'DONE',
				doneButtonColor: '#000000',
				cancelButtonLabel: 'CANCEL',
				cancelButtonColor: '#000000'
		  		};
		 		$cordovaDatePicker.show(options).then(function(date){
					
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
                    var hours;
					var minute=date.getMinutes();
                    if (date.getHours() < 12) {
                        hours = date.getHours();
                        format = "AM";
                    } else {
                        hours = date.getHours() - 12;
                        format = "PM";
                    }
					$scope.post.dateSelectType='calendar'
					//$scope.post.selectedDate=day+","+" "+monthName+" "+date.getDate()+" "+date.getFullYear();
					$scope.post.selectedDate=moment(date).format("dddd, MMMM Do YYYY")
					//alert($scope.post.selectedDate)
					//$scope.post.selectedDate=$filter('date')(date, 'EEEE, MMMM d, y');
					$scope.populateEventList($scope.post.filterType);
    	  });
		}
		
		
					$scope.onPostTabOneSelect=function(){
				console.log('PostTabOneSelect....')
				//alert("TabOneSelect")
				$scope.post.selectedDate=ControVarAccessFac.getData("myFriendSelectedDate");
				$scope.post.dateSelectType='calendar';
				$scope.postResponse=[];
				$scope.postResponseFilter=$scope.postResponse;
				$scope.post.tabselected='myfriend';
				$scope.post.post_lat  =  $scope.post.current_lat;
				$scope.post.post_long = $scope.post.current_long ;
				//document.getElementById('locationSearch').value='';
				
				$scope.populateEventList('myfriend');
		}
		
					$scope.onPostTabTwoSelect=function(){
						//alert("TabTwoSelect")
							$scope.post.selectedDate=ControVarAccessFac.getData("arroundSelectedDate");
							$scope.post.dateSelectType='calendar';
							$scope.postResponse=[];
							$scope.postResponseFilter=$scope.postResponse;
							$scope.post.tabselected='around';
							$scope.post.post_lat  =  $scope.post.current_lat;
							$scope.post.post_long = $scope.post.current_long ;
							//document.getElementById('locationSearch').value='';
							$scope.populateEventList('around');
					}		
		
			$scope.onPostTabThreeSelect=function(){
				//alert("TabThreeSelect")
			if(ControVarAccessFac.getData("post_tab")==2){
				$scope.post.selectedDate=ControVarAccessFac.getData("post_createdDate");								
				ControVarAccessFac.setData("post_tab",0);					
			}else{
				$scope.post.selectedDate=ControVarAccessFac.getData("myEventSelectedDate");
			}
			
			$scope.post.dateSelectType='calendar';
			$scope.postResponse=[];
			$scope.postResponseFilter=$scope.postResponse;
			$scope.post.tabselected='myevent';
			$scope.post.post_lat  =  $scope.post.current_lat;
			$scope.post.post_long = $scope.post.current_long ;
			//document.getElementById('locationSearch').value='';
			//alert("$scope.post.selectedDate:: "+$scope.post.selectedDate)
			$scope.populateEventList('myevent');
		}
		
					$scope.onPlaceChangedHandler=function(){
								infowindow.close();							
								 var place = autocomplete.getPlace();
								 if (place.geometry) {	
									 $scope.post.post_lat  =  place.geometry.location.lat();
									 $scope.post.post_long = place.geometry.location.lng();
									 console.log("location "+$scope.post.post_lat+">>>"+$scope.post.post_long)
									 $scope.populateEventList($scope.post.tabselected);
								 }else{
									 $scope.post.post_lat  =  $scope.post.current_lat;
									 $scope.post.post_long = $scope.post.current_long ;
									 console.log("location default"+$scope.post.post_lat+">>>"+$scope.post.post_long)
									 $scope.populateEventList($scope.post.tabselected);
								 }
					}
						
			});
			
			
	$scope.formateDate=function(dateStr){
		var strLength=dateStr.length;
		 return dateStr.slice(0,strLength-4);
	}
	
	$scope.checkPostExpirey=function(event_date_timestamp){
					 var userDate = event_date_timestamp;
					 var currentDate = new Date().getTime()/1000;					
					var difference = userDate-currentDate;
					if(difference<0){
						return 'postExpired'
					}else{
						return 'postActive'
					}
	}
			
		
});