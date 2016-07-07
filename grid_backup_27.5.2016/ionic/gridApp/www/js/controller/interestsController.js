gridApp.controller('InterestCtrl',function($scope,$state,$filter,$http,ControVarAccessFac,$ionicLoading,$ionicPopup){
									   
	
										   
	/**
				Variavle delcaration for the Interest 
			**/							   
			$scope.interestResponse={};
			$scope.interestsArr=[];
			$scope.interestsArrID=[];
			$scope.interestsTempArr=[];
			$scope.filteredInterestArr=[];
			$scope.selectedInterestArr=[];
			$scope.filterInterest = {};
			
			var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
			
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
		
        //console.log('CTRL - $ionicView.loaded', viewInfo, state);
		$scope.getInterests();
    });
	
		$scope.interestBack = function() {
			 $state.go('main.profile');
  		};
										   
	/* ===Implementation for the get Interest Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:getInterests
				Parameter: None
				Return :None
				Uses:to populate interests coming from DB
				Function Called From: Interest page onload.
				Function Developed By :Suman Ghosh
				Date:01/15/2016
				@@@@@@@@@@@@@@@@
			*/
			$scope.getInterests = function() {
				
			
						
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
						//alert("getInterests..called")					
						var link = 'http://grid.digiopia.in/interest/getinterest';
						$http.post(link,{},config
								  ).then(function (res){
									  			$ionicLoading.hide();
												if(res.data.status==true){
													$scope.interestResponse=res.data.response;
													$scope.populateInterest();
												}else{
													/*$ionicPopup.alert({
													  title: 'Forget Password Fail',
													  content: res.data.message
													})*/
												}
									},function (error){
										$ionicLoading.hide();
										//alert("get Interest error.."+error)
										$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
								});
				 }
		
	  /* ===Implementation for the get Interest End===*/
	  
	  $scope.populateInterest = function(){
		  $scope.interestsArr=[];
		  $scope.interestsTempArr=[];
		  $scope.interestsArrID=[];
		  /*$ionicPopup.alert({
								 title: 'Add Interests!',
								content: 'The more interest you have, the more posts you will see!'
						})*/
		  angular.forEach($scope.interestResponse, function(item){
                   //console.log(item.id); 
				   
				   $scope.interestsArr.push(item.interest_name);
				   $scope.interestsTempArr.push(item.interest_name);
				   $scope.interestsArrID.push(item.id);
          })
		  
		  
	  }
	
	
	
	
	$scope.update=function(){
		
		$scope.interestsArr=$scope.interestsTempArr;
		//alert("xxxx"+$scope.interestsArr)
	}
	
	$scope.interestsNext = function(index) {
		var selectedElm=$scope.filterInterest.filteredInterestArr[index];
		var selectedElmID=$scope.interestsArrID[$scope.interestsArr.indexOf(selectedElm)];
		ControVarAccessFac.setData("selectedInterest",selectedElm);
		ControVarAccessFac.setData("selectedInterestID",selectedElmID);
       	$state.go('interests-sub');
    }
	
	
})