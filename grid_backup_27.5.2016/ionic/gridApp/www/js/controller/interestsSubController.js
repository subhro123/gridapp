gridApp.controller('InterestSubCtrl',function($scope,$state,$http,ControVarAccessFac,$ionicLoading){
	
	
	$scope.getInterestsPageBack = function() {
			 $state.go('interests');
  	};
	
	/**
		Variavle delcaration for the SubInterest 
	**/
	$scope.subInterestResponse={};
	
	$scope.interestsSubArrRef=[];
	$scope.interestsSubArr=[];
	$scope.interestsSubTempArr=[];
	$scope.filteredSubInterestArr=[];
	$scope.selectedSubInterestArr=[];
	$scope.interestsSubArrID=[];
	$scope.filterSubInterest = {};
	
	var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }
	
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
		
        //console.log('CTRL - $ionicView.loaded', viewInfo, state);
		$scope.selInterest=ControVarAccessFac.getData("selectedInterest");	
		$scope.selInterestID=ControVarAccessFac.getData("selectedInterestID");
		$scope.loginType=ControVarAccessFac.getData("loginType")
		$scope.user_id=ControVarAccessFac.getData("userID");
		$scope.getSubInterests();
    });
	
	
	/* ===Implementation for the getSubInterests Start===*/
			/* 
				@@@@@@@@@@@@@@@@
				Function Name:getSubInterests
				Parameter: None
				Return :None
				Uses:to populate sub interests depending on the selected interest coming from DB
				Function Called From: sub interest page onload.
				Function Developed By :Suman Ghosh
				Date:01/15/2016
				@@@@@@@@@@@@@@@@
			*/
			$scope.getSubInterests = function() {
						$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
						var link = 'http://grid.digiopia.in/subinterest/getsubinterest';
						$http.post(link,{interest_id : $scope.selInterestID},
								   config
								  ).then(function (res){
												if(res.data.status==true){
													$ionicLoading.hide();
													$scope.subInterestResponse=res.data.response;
													$scope.populateSubInterest();
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
		
	  /* ===Implementation for the get Interest End===*///id,sub_id,interest_name,subinterest_name
	
			 $scope.populateSubInterest = function(){
				  angular.forEach($scope.subInterestResponse, function(item){
						   $scope.interestsSubArr.push(item.subinterest_name);
						   $scope.interestsSubTempArr.push(item.subinterest_name);
						   $scope.interestsSubArrRef.push(item.subinterest_name);
						   $scope.interestsSubArrID.push(item.sub_id);
					   })
			  }
			$scope.SelectItem=function(index){		
				var selectedSubElm=$scope.filterSubInterest.filteredSubInterestArr[index];	
				$scope.selectedSubInterestArr.push(selectedSubElm)		
				$scope.filterSubInterest.filteredSubInterestArr.splice(index,1);		
				var selInd=$scope.interestsSubTempArr.indexOf(selectedSubElm);
				$scope.interestsSubTempArr.splice(selInd,1);		
				$scope.interestsSubArr=$scope.filterSubInterest.filteredSubInterestArr;
			}
			
			$scope.removeSelectItem=function(index){	
				$scope.filterSubInterest.filteredSubInterestArr.push($scope.selectedSubInterestArr[index]);
				$scope.interestsSubTempArr.push($scope.selectedSubInterestArr[index]);
				$scope.interestsSubArr=$scope.filterSubInterest.filteredSubInterestArr;
				$scope.selectedSubInterestArr.splice(index,1);
			}
			
			$scope.update=function(){
				$scope.interestsSubArr=$scope.interestsSubTempArr;
			}
			
			$scope.saveInterests = function() {
				var selectedSubInterestID=[];
				for(var i=0;i<$scope.selectedSubInterestArr.length;i++){
					selectedSubInterestID.push(parseInt($scope.interestsSubArrID[$scope.interestsSubArrRef.indexOf($scope.selectedSubInterestArr[i])]));
				}
				//$scope.selectedSubInterestArr=[];
				//alert("user_id:: "+$scope.user_id+" :::interest ID "+$scope.selInterestID+"::: selectedSubInterestID "+selectedSubInterestID+"   :::loginType "+$scope.loginType)
				if($scope.selectedSubInterestArr.length!=0){
					$ionicLoading.show({
									 template: '<ion-spinner icon="android"></ion-spinner>'
						});
					var link = 'http://grid.digiopia.in/user/save_interest';
						$http.post(link,{email : $scope.user_id,loginType:$scope.loginType,interest_id:$scope.selInterestID,subinterest_ids:selectedSubInterestID},config
								  ).then(function (res){
									  $ionicLoading.hide();
									 $scope.interestsSubArr=[];
						   			 $scope.interestsSubTempArr=[];
						  			 $scope.interestsSubArrRef=[];
						  			 $scope.interestsSubArrID=[];
									 $scope.selectedSubInterestArr=[];
									  
												if(res.data.status==true){
													//$scope.subInterestResponse=res.data.response;
													//$state.go('main.home');
													//$state.go('main.create-post');
													$state.go('main.profile');
												}else{
													/*$ionicPopup.alert({
													  title: 'Forget Password Fail',
													  content: res.data.message
													})*/
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
	
})