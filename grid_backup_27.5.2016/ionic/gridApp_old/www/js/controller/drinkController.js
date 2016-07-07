gridApp.controller('DrinkCtrl', function($scope, $ionicSideMenuDelegate,$http) {
		var clientToken = "eyJ2ZXJzaW9uIjoyLCJhdXRob3JpemF0aW9uRmluZ2VycHJpbnQiOiJlMjAwMjEzN2U3MTI3ZDY2NDg1NTNlYWI1YmJhMzMyY2MxMTljNWExODUzNWExMjYzYzUwMTg2YmVhNmYyYTVifGNyZWF0ZWRfYXQ9MjAxNi0wNC0wOFQwOTozNzozNC4yODYwMjg0MzcrMDAwMFx1MDAyNm1lcmNoYW50X2lkPWM3Yzd5MmtxYzc1Z2c4cmtcdTAwMjZwdWJsaWNfa2V5PWZ5M2QyaG1kZ2ttcXNyMjkiLCJjb25maWdVcmwiOiJodHRwczovL2FwaS5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tOjQ0My9tZXJjaGFudHMvYzdjN3kya3FjNzVnZzhyay9jbGllbnRfYXBpL3YxL2NvbmZpZ3VyYXRpb24iLCJjaGFsbGVuZ2VzIjpbXSwiZW52aXJvbm1lbnQiOiJzYW5kYm94IiwiY2xpZW50QXBpVXJsIjoiaHR0cHM6Ly9hcGkuc2FuZGJveC5icmFpbnRyZWVnYXRld2F5LmNvbTo0NDMvbWVyY2hhbnRzL2M3Yzd5MmtxYzc1Z2c4cmsvY2xpZW50X2FwaSIsImFzc2V0c1VybCI6Imh0dHBzOi8vYXNzZXRzLmJyYWludHJlZWdhdGV3YXkuY29tIiwiYXV0aFVybCI6Imh0dHBzOi8vYXV0aC52ZW5tby5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tIiwiYW5hbHl0aWNzIjp7InVybCI6Imh0dHBzOi8vY2xpZW50LWFuYWx5dGljcy5zYW5kYm94LmJyYWludHJlZWdhdGV3YXkuY29tL2M3Yzd5MmtxYzc1Z2c4cmsifSwidGhyZWVEU2VjdXJlRW5hYmxlZCI6ZmFsc2UsInBheXBhbEVuYWJsZWQiOnRydWUsInBheXBhbCI6eyJkaXNwbGF5TmFtZSI6IkdyaWQiLCJjbGllbnRJZCI6bnVsbCwicHJpdmFjeVVybCI6Imh0dHA6Ly9leGFtcGxlLmNvbS9wcCIsInVzZXJBZ3JlZW1lbnRVcmwiOiJodHRwOi8vZXhhbXBsZS5jb20vdG9zIiwiYmFzZVVybCI6Imh0dHBzOi8vYXNzZXRzLmJyYWludHJlZWdhdGV3YXkuY29tIiwiYXNzZXRzVXJsIjoiaHR0cHM6Ly9jaGVja291dC5wYXlwYWwuY29tIiwiZGlyZWN0QmFzZVVybCI6bnVsbCwiYWxsb3dIdHRwIjp0cnVlLCJlbnZpcm9ubWVudE5vTmV0d29yayI6dHJ1ZSwiZW52aXJvbm1lbnQiOiJvZmZsaW5lIiwidW52ZXR0ZWRNZXJjaGFudCI6ZmFsc2UsImJyYWludHJlZUNsaWVudElkIjoibWFzdGVyY2xpZW50MyIsImJpbGxpbmdBZ3JlZW1lbnRzRW5hYmxlZCI6dHJ1ZSwibWVyY2hhbnRBY2NvdW50SWQiOiJncmlkIiwiY3VycmVuY3lJc29Db2RlIjoiVVNEIn0sImNvaW5iYXNlRW5hYmxlZCI6ZmFsc2UsIm1lcmNoYW50SWQiOiJjN2M3eTJrcWM3NWdnOHJrIiwidmVubW8iOiJvZmYifQ==";

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
  
});	
//http://grid.digiopia.in/user/get_braintree

$scope.userPayment = function(form) {
	var link = 'http://grid.digiopia.in/user/get_braintree';
				$http.post(link, {
							}).then(function (res){
									alert("xxxxxxx")		
											if(res.data.status==true){
												
											}else{
												
											}
							},function (error){
								
								alert("Payment error.."+error)
							});
}
			
})