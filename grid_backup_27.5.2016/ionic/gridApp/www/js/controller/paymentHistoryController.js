gridApp.controller('PaymentHisCtrl',function($scope,$state,$http,$ionicPopup,ControVarAccessFac,$ionicLoading,$cordovaDatePicker){
$scope.paymentHisResponse='';
$scope.paymentHis={
	startDate:'Please Insert From Date',
	fromval:'',
	endDate:'Please Insert To Date',
	toval:'',
	paymentHisFlag:true,
}
var config = {
                headers : {
                    'Authorizations':ControVarAccessFac.getData("token") ,
					'User-Id':ControVarAccessFac.getData("user_ID"),
					'Content-Type': 'application/json; charset=utf-8'
                }
            }		
$scope.$on('$ionicView.enter', function (viewInfo, state) {
	//alert(moment().format("dddd, MMMM Do YYYY"))
	//alert(moment().subtract(30, 'days').format("dddd, MMMM Do YYYY"))
	
	$scope.paymentHis.startDate=moment().subtract(30, 'days').format("dddd, MMMM Do YYYY")
	$scope.paymentHis.endDate=moment().format("dddd, MMMM Do YYYY")
	$scope.getPaymentHistory();		
});

$scope.getPaymentHistory=function(){
	var link = 'http://grid.digiopia.in/user/get_transaction_history';
				$http.post(link,{
						   user_id:ControVarAccessFac.getData("user_ID"),
						   start_date:$scope.paymentHis.startDate,
						   end_date:$scope.paymentHis.endDate,
			 	},
				config
			).then(function (res){
				$scope.paymentHisResponse=res.data.response;
				if(res.data.status==true){
						$scope.paymentHis.paymentHisFlag=true
				}else{
						$scope.paymentHis.paymentHisFlag=false
				 }
				
					 // var clientToken =res.data.response.client_token;
					//$scope.payment.paymentVal=res.data.response.paymentVal
					
			},function (error){
						//alert("get  error.."+error)
						$ionicPopup.alert({
												  title: 'Network Connection Error',
												  content: 'Please check your network connection'
											})
			});
}



$scope.setPaymentDateAndTime= function(type) {
			var options = {
			date: new Date(),
			mode: 'date', // or 'time'
			/*minDate: new Date() - 10000,*/
			allowOldDates: true,
			allowFutureDates: false,
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
					if(type=='fromDate'){
						//$scope.paymentHis.startDate=day+","+date.getDate()+""+monthName+""+date.getFullYear();
						$scope.paymentHis.startDate=moment(date).format("dddd, MMMM Do YYYY");
						$scope.paymentHis.fromval=new Date(date).getTime()
					}else{
						//$scope.paymentHis.endDate=day+","+date.getDate()+""+monthName+""+date.getFullYear();
						$scope.paymentHis.endDate=moment(date).format("dddd, MMMM Do YYYY");
						$scope.paymentHis.toval=new Date(date).getTime()
					}
					if($scope.paymentHis.fromval!='' && $scope.paymentHis.toval!=''){
						if($scope.paymentHis.toval>$scope.paymentHis.fromval){
							/*$ionicPopup.alert({
								title: 'date success',
								content: 'correct date'
							})*/
							$scope.getPaymentHistory();
						}else{
							$ionicPopup.alert({
								title: 'date fail',
								content: 'todate must be greater than fromdate'
							})
						}
					}
					/*
					$scope.createPost.selectedDate=day+","+date.getDate()+""+monthName+""+date.getFullYear();
					$scope.createPost.selectedTime=hours+":"+minute+" "+format;
					*/
    	  });
		}


})