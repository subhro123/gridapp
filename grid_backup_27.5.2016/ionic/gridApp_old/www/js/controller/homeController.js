gridApp.controller('HomeCtrl', function($scope, $ionicSideMenuDelegate,$cordovaContacts,PaypalService) {
			$scope.getContactList = function() {
					 $cordovaContacts.find({filter: ''}).then(function(result) {
					 $scope.contacts = result;
				}, function(error) {
					 console.log("ERROR: " + error);
				});
			}
			 

			$scope.makePayPalAmount = function() {			
				
				PaypalService.initPaymentUI().then(function () {
                  PaypalService.makePayment(100, "Total").then(function(){
							//alert("Payment Done")															  
					})
   				 });
				
			}
			
			
			
})