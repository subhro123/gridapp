gridApp.controller('DashboardCtrl',function($scope,$state,$ionicLoading,$http,ControVarAccessFac,$ionicPlatform,$ionicTabsDelegate,$ionicPopup){
		
	
	$scope.dashboard={
		user_id:ControVarAccessFac.getData("user_ID"),
		event_list_arr:[],
		user_img_arr:[],
		dashboard_type:'request',
		dashboardFlag:true,
		requestCount:0
	}
	var config = {
                	headers : {
						'Authorizations':ControVarAccessFac.getData("token") ,
						'User-Id':ControVarAccessFac.getData("user_ID"),
						'Content-Type': 'application/json; charset=utf-8'
                	}
            	}
	
	$scope.$on('$ionicView.enter', function (viewInfo, state) {
			//
		$ionicPlatform.ready(function() {				
				//$scope.populateDashboardView($scope.dashboard.dashboard_type)
				
		});
		//$ionicTabsDelegate.$getByHandle('dashboard-tabs').select(0)
	});
	
	$scope.openOtherProfile= function(index){
		ControVarAccessFac.setData("other_user_ID",$scope.dashboard.event_list_arr[index].user_id);
		console.log($scope.dashboard.event_list_arr[index].user_id)
		$state.go('main.profile-other');
	}
	
	$scope.openPostDetails= function(index){
		//ControVarAccessFac.setData("other_user_ID",$scope.dashboard.event_list_arr[index].user_id);
		//console.log($scope.dashboard.event_list_arr[index].user_id)
		//$state.go('main.profile-other');
		//$rootScope.openPostDetailsView($scope.dashboard.event_list_arr[index].post_id,$scope.dashboard.event_list_arr[index].user_id)
		ControVarAccessFac.setData("post_ID",$scope.dashboard.event_list_arr[index].post_id);
		
		
		if($scope.dashboard.event_list_arr[index].event_creator_id==ControVarAccessFac.getData("user_ID")){
					console.log("owner...")
					$state.go('main.post-owner-view');
					
				}else{
					console.log("other...")
					$state.go('main.post-other-view');
				}
		
	}
	//
	
	
	$scope.populateDashboardView=function(dashboardType){		
		
			$ionicLoading.show({
				template: '<ion-spinner icon="android"></ion-spinner>'
			});
			//alert(ControVarAccessFac.getData("user_ID"))
			var link = 'http://grid.digiopia.in/user/display_event_user_dashboard';
			$http.post(link, {
						user_id : ControVarAccessFac.getData("user_ID"),
						type : dashboardType
					},
					config).then(function (res){
							$ionicLoading.hide();
							if(res.data.status==true){	
								$scope.dashboard.event_list_arr=res.data.response;
								$scope.dashboard.requestCount=res.data.request_count;
									 if($scope.dashboard.event_list_arr.length>0){
										$scope.dashboard.dashboardFlag=true
									 }else{
										$scope.dashboard.dashboardFlag=false
									 }
								
								for(var i=0;i<$scope.dashboard.event_list_arr.length;i++){
									if($scope.dashboard.event_list_arr[i].image!=''){
										$scope.dashboard.user_img_arr[i]="http://grid.digiopia.in/uploads/profile/thumb/"+$scope.dashboard.event_list_arr[i].image;
									}else{
										$scope.dashboard.user_img_arr[i]="img/pro01.jpg"
									}
								}
								
							}else{
								$ionicPopup.alert({
									title: 'Dash Board Fail',
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
	$scope.onDashTabSelect=function(selectedType){
		$scope.dashboard.dashboard_type=selectedType;
		$scope.populateDashboardView($scope.dashboard.dashboard_type)
	}
	$scope.formateDate=function(dateStr){
		var strLength=dateStr.length;
		 return dateStr.slice(0,strLength-4);
	}
	
	
});