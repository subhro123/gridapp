gridApp.controller('MapCtrl', function($scope, $ionicSideMenuDelegate,$cordovaGeolocation, $ionicLoading,$ionicPlatform) {

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
             
            var mapOptions = {
                center: myLatlng,
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };          
             
            var map = new google.maps.Map(document.getElementById("map"), mapOptions);          
             
            $scope.map = map;   
            $ionicLoading.hide(); 
			
			// Create marker
			var marker = new google.maps.Marker({
			  map: $scope.map,
			  position: myLatlng,
			  draggable:true,
			  title: 'I am here..'
			});
			
			// Add circle overlay and bind to marker
			var circle = new google.maps.Circle({
			  map: $scope.map,
			  radius: 10000,    // metres
			  fillColor: '#AA0000'
			});
			circle.bindTo('center', marker, 'position');
			
			//drag marker and get current position
			google.maps.event.addListener(marker, 'dragend', function(evt){
        		console.log('Current Latitude:',evt.latLng.lat(),'Current Longitude:',evt.latLng.lng());
			});
			
			//info window
			var contentString = '<div id="content">'+
			  '<div id="siteNotice">'+
			  '</div>'+
			  '<h1 id="firstHeading" class="firstHeading">Me</h1>'+
			  '<div id="bodyContent">'+
			  '<p>My name is Suman</p>'+
			  '</div>'+
			  '</div>';
			  
			  var infowindow = new google.maps.InfoWindow({
    				content: contentString
  			 });
			  marker.addListener('click', function() {
    				infowindow.open(map, marker);
  			});



             
        }, function(err) {
            $ionicLoading.hide();
            console.log(err);
        });
		
		
		
		
    }) 
	
	/*
	var options = {timeout: 10000, enableHighAccuracy: true};
 
  $cordovaGeolocation.getCurrentPosition(options).then(function(position){
 
    var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
 
    var mapOptions = {
      center: latLng,
      zoom: 15,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
 
    $scope.map = new google.maps.Map(document.getElementById("map"), mapOptions);
 
  }, function(error){
    console.log("Could not get location");
  });
  
	  //Wait until the map is loaded
	google.maps.event.addListenerOnce($scope.map, 'idle', function(){
	 
	  var marker = new google.maps.Marker({
		  map: $scope.map,
		  animation: google.maps.Animation.DROP,
		  position: latLng
	  });      
	 
	  var infoWindow = new google.maps.InfoWindow({
		  content: "Here I am!"
	  });
	 
	  google.maps.event.addListener(marker, 'click', function () {
		  infoWindow.open($scope.map, marker);
	  });
	 
	});*/
});