/*gridApp.controller('ChatCtrl', function($scope, $ionicSideMenuDelegate,$ionicHistory) {
		
		socket.on('connect',function(){
				//Add user called nickname
       		 socket.emit('add user','nickname');
        	});
		$scope.ChatBack=function(){
			$ionicHistory.goBack(-1)
		}
}) */


gridApp.controller('ChatCtrl',function($scope, $ionicSideMenuDelegate,$ionicHistory,$stateParams,socket,$sanitize,$ionicScrollDelegate,$timeout,ControVarAccessFac,$filter,$cordovaCamera,$cordovaDevice,$cordovaFile,$ionicActionSheet,$cordovaFileTransfer,$cordovaImagePicker,$rootScope) {	
			
			//$scope.message='';
			$scope.messages=[];
			$scope.allGrid_users=[];
			$scope.socket=socket;
			
			$scope.$on('$ionicView.enter', function (viewInfo, state) {
					$scope.allGrid_users=ControVarAccessFac.getData("gridusers");
			})
			
			
			$scope.ChatBack=function(){
				$ionicHistory.goBack(-1)
			}
			
			$scope.getUserInfo=function(user_id){
				var user=$filter('filter')($scope.allGrid_users, {id: user_id}, true);
				console.log(JSON.stringify(user[0]))
				return user[0];
			}
						   
			$scope.socket.emit("join", ControVarAccessFac.getData("user_ID"));
			
			$scope.socket.on("personalchat", function(who, msg){
					console.log("personalchat  who::"+who+" >>>> "+msg)
					$scope.messages.push({content:$sanitize(msg),username:$scope.getUserInfo(who).fullname,image:$scope.getUserInfo(who).image})
					if (!$scope.$$phase) $scope.$apply();
  					$ionicScrollDelegate.scrollBottom();
			});
						 
			$scope.socket.on("personalchatSelf", function(who, msg){
					console.log("personalchatSelf  who::"+who+" >>>> "+msg)
					$scope.messages.push({content:$sanitize(msg),username:$scope.getUserInfo(who).fullname,image:$scope.getUserInfo(who).image})
					if (!$scope.$$phase) $scope.$apply();
  					$ionicScrollDelegate.scrollBottom();
			 });
			
			$scope.sendMessage=function(){				
				selectedUserID=ControVarAccessFac.getData("other_user_ID");
				console.log("selectedUserID::: "+selectedUserID)
				$scope.socket.emit("send", $scope.message,selectedUserID);
				$scope.message='';
			}
			
			/*
			$scope.socket.on("imageShow", function(who, msg){
					alert.log("personalchat  who::"+who+" >>>> "+msg)
					$scope.messages.push({content:msg,username:$scope.getUserInfo(who).fullname,image:$scope.getUserInfo(who).image})
  					$ionicScrollDelegate.scrollBottom();
			});
						 
			$scope.socket.on("imageShowSelf", function(who, msg){
					alert("personalchatSelf  who::"+who+" >>>> "+msg)
					$scope.messages.push({content:msg,username:$scope.getUserInfo(who).fullname,image:$scope.getUserInfo(who).image})
  					$ionicScrollDelegate.scrollBottom();
			 });
			
			*/
			
			
		/*========adding Image======*/	
			
		$scope.selectUploadFile= function(){
		
		 $scope.hideSheet = $ionicActionSheet.show({
		  buttons: [
			{ text: '<i class="icon ion-camera"></i> Take photo' },
			{ text: '<i class="icon ion-images"></i> Photo from library' }
		  ],
		  titleText: 'Add images',
		  cancelText: 'Cancel',
		  buttonClicked: function(index) {
			  if(index==0){
				$scope.captureChatImage(index);
			  }else{
				$scope.selectChatImage();
			  }
		  }
		});
	}
	$scope.captureChatImage = function(type) {
		console.log("captureProfileImage")
		$scope.hideSheet();
		//$scope.getImage(type);
		/*var options = {
			  quality: 100,
			  destinationType: Camera.DestinationType.FILE_URI,
			  sourceType: Camera.PictureSourceType.CAMERA,
			  allowEdit: false,
			  encodingType: Camera.EncodingType.JPEG,
			  targetWidth: 100,
			  targetHeight: 100,
			  popoverOptions: CameraPopoverOptions,
			  saveToPhotoAlbum: false,
			  correctOrientation:true
   	 	};*/
		
		document.addEventListener("deviceready", function () {
			var options = {
			  quality: 50,
			  destinationType: Camera.DestinationType.DATA_URL,
			  sourceType: Camera.PictureSourceType.CAMERA,
			  allowEdit: true,
			  encodingType: Camera.EncodingType.JPEG,
			  targetWidth: 100,
			  targetHeight: 100,
			  popoverOptions: CameraPopoverOptions,
			  saveToPhotoAlbum: false,
			  correctOrientation:true
			};

			$cordovaCamera.getPicture(options).then(function(imageData) {
			   var img = 'data:image/jpeg;base64,' + imageData;
			  // socket.emit('sendImage',img,ControVarAccessFac.getData("other_user_ID"));
			  
			 $rootScope.$broadcast('event:file:selected',img);  
			}, function(err) {
			  // error
			});

 		 }, false);
		
		 $rootScope.$on('event:file:selected',function(event,data){
				alert("image selected::: "+data)
				alert("TO ID "+ControVarAccessFac.getData("other_user_ID"))
               // $scope.socket.emit('sendImage',data.image,ControVarAccessFac.getData("other_user_ID"));
			  // $scope.socket.emit('event:new:image',data);
			 // var imageUrl=data.replace(/data:image\/jpeg;base64,/g, '');
			 	var imageUrl=data
			  $scope.socket.emit("send", imageUrl,ControVarAccessFac.getData("other_user_ID"));
                //scope.messages.unshift(data);
                scope.$apply();
        });
		
 	}
	
	
	
	
	
	$scope.selectChatImage=function(){
		//alert("selectProfileImage")
		$scope.hideSheet();
		var options = {
  		 maximumImagesCount: 1,
  		 /*width: 800,
   		 height: 600,*/
   		 quality: 100
  		};
		$cordovaImagePicker.getPictures(options)
    			.then(function (results) {
      				
					
					if(results[0]==''||results[0]=='undefined'||results[0]==undefined){
						//alert("image NOT taken...")
					}else{
						//alert("image taken...")
						$scope.profile.capProImage=results[0];
						$scope.saveProfileImg();
					}
    			}, function(error) {
      				// error getting photos
					//alert("error:::"+error)
    	});
	}
	$scope.saveProfileImg= function(){
		$scope.profile.profileImageEdit=false;
	   	$ionicLoading.show({template: 'Saving Image...'});
		var fileURL = $scope.profile.capProImage;
		var options = new FileUploadOptions();
		options.fileKey = "file";
		options.fileName = fileURL.substr(fileURL.lastIndexOf('/') + 1);
		options.mimeType = "image/jpeg";
		options.chunkedMode = true;
		options.httpMethod = 'POST';		
		/*options.headers = {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
         }*/
		 
		var md5_hash = calcMD5('admin'+'GRID API');
		var params = {};
		//params.user_id = 'aaaa';
		params.user_id =ControVarAccessFac.getData("user_ID");
		params.Client_Service='GRIDAPP-CLIENT';
		params.Auth_Key= md5_hash;
		//alert('Client_Service=>'+params.Client_Service);
		//alert('Auth_Key=>'+params.Auth_Key);
       //params.value2 = "otherparams";

		options.params = params;

		var ft = new FileTransfer();
		ft.upload(fileURL, encodeURI("http://grid.digiopia.in/user/profile_image"), function(res){
		var resObj = JSON.parse(res.response)
		//alert(res.response)
		$scope.profile.profileImage=resObj.response.image_path;
		//alert(res.response);
		//alert(resObj.response.image_path)
		$scope.$apply();
		$ionicLoading.hide();
		 $scope.showSuccessPopup('Profile Image save successfully')
		},function(error) {$ionicLoading.show({template: 'Image not saved...'});
		$ionicLoading.hide();}, options);
	   
   }
	
	
			
			
			
	//socket.emit("join","Suman");
									   
	/*
  	
  	var self=this;
  	var typing = false;
  	var lastTypingTime;
  	var TYPING_TIMER_LENGTH = 400;
  	
  	//Add colors
  	var COLORS = [
	    '#e21400', '#91580f', '#f8a700', '#f78b00',
	    '#58dc00', '#287b00', '#a8f07a', '#4ae8c4',
	    '#3b88eb', '#3824aa', '#a700ff', '#d300e7'
	  ];

	 //initializing messages array
	self.messages=[]

  	socket.on('connect',function(){
  	  
  	  connected = true
  	 
  	  //Add user
  	 // socket.emit('add user', $stateParams.nickname);//ControVarAccessFac.setData("userID"
		socket.emit('add user', ControVarAccessFac.setData("userID"));																			

  	  // On login display welcome message
  	  socket.on('login', function (data) {
	    //Set the value of connected flag
	    self.connected = true
	    self.number_message= message_string(data.numUsers)
	  	
	  });

	  // Whenever the server emits 'new message', update the chat body
	  socket.on('new message', function (data) {
	  	if(data.message&&data.username)
	  	{
	   		addMessageToList(data.username,true,data.message)
	  	}
	  });

	  // Whenever the server emits 'user joined', log it in the chat body
	  socket.on('user joined', function (data) {
	  	addMessageToList("",false,data.username + " joined")
	  	addMessageToList("",false,message_string(data.numUsers)) 
	  });

	  // Whenever the server emits 'user left', log it in the chat body
	  socket.on('user left', function (data) {
	    addMessageToList("",false,data.username+" left")
	    addMessageToList("",false,message_string(data.numUsers))
	  });

	  //Whenever the server emits 'typing', show the typing message
	  socket.on('typing', function (data) {
	    addChatTyping(data);
	  });

	  // Whenever the server emits 'stop typing', kill the typing message
	  socket.on('stop typing', function (data) {
	    removeChatTyping(data.username);
	  });	
  	})

  	//function called when user hits the send button
  	self.sendMessage=function(){
  		socket.emit('new message', self.message)
  		addMessageToList($stateParams.nickname,true,self.message)
  		socket.emit('stop typing');
  		self.message = ""
  	}

  	//function called on Input Change
  	self.updateTyping=function(){
  		sendUpdateTyping()
  	}

  	// Display message by adding it to the message list
  	function addMessageToList(username,style_type,message){
  		username = $sanitize(username)
  		removeChatTyping(username)
  		var color = style_type ? getUsernameColor(username) : null
  		self.messages.push({content:$sanitize(message),style:style_type,username:username,color:color})
  		$ionicScrollDelegate.scrollBottom();
  	}

  	//Generate color for the same user.
  	function getUsernameColor (username) {
	    // Compute hash code
	    var hash = 7;
	    for (var i = 0; i < username.length; i++) {
	       hash = username.charCodeAt(i) + (hash << 5) - hash;
	    }
	    // Calculate color
	    var index = Math.abs(hash % COLORS.length);
	    return COLORS[index];
  	}

  	// Updates the typing event
  	function sendUpdateTyping(){
  		if(connected){
  			if (!typing) {
		        typing = true;
		        socket.emit('typing');
		    }
  		}
  		lastTypingTime = (new Date()).getTime();
  		$timeout(function () {
	        var typingTimer = (new Date()).getTime();
	        var timeDiff = typingTimer - lastTypingTime;
	        if (timeDiff >= TYPING_TIMER_LENGTH && typing) {
	          socket.emit('stop typing');
	          typing = false;
	        }
      	}, TYPING_TIMER_LENGTH)
  	}

	// Adds the visual chat typing message
	function addChatTyping (data) {
	    addMessageToList(data.username,true," is typing");
	}

	// Removes the visual chat typing message
	function removeChatTyping (username) {
	  	self.messages = self.messages.filter(function(element){return element.username != username || element.content != " is typing"})
	}

  	// Return message string depending on the number of users
  	function message_string(number_of_users)
  	{
  		return number_of_users === 1 ? "there's 1 participant":"there are " + number_of_users + " participants"
  	}*/
});

