var con=angular.module('starter.controllers', []);

con.controller('AskMe', ['$scope', '$rootScope',  '$log', 'searchData', '$state', '$ionicPopup', 'AuthService', 'AUTH_EVENTS', function ($scope, $rootScope, $log, searchData,$state, $ionicPopup, AuthService, AUTH_EVENTS) {
  $scope.searchPara={searchType:'P', searchED:'N',  searchDesc:'',  searchEL:'N', searchLocation:''};
  $scope.username='';
  $scope.Uid='';
  $scope.logout = function() {
    AuthService.logout();
    $state.go('login');
  };
  //$scope.username = AuthService.username();

  $scope.$on(AUTH_EVENTS.notAuthorized, function(event) {
    var alertPopup = $ionicPopup.alert({
      title: 'Unauthorized!',
      template: 'You are not allowed to access this resource.'
    });
  });

  $scope.$on(AUTH_EVENTS.notAuthenticated, function(event) {
    AuthService.logout();
    $state.go('login');
    var alertPopup = $ionicPopup.alert({
      title: 'Session Lost!',
      template: 'Sorry, You have to login again.'
    });
  });
  $scope.$on(AUTH_EVENTS.wrongPass, function(event) {
    var alertPopup = $ionicPopup.alert({
      title: 'Wrong Username or Password!',
      template: 'We can\'t let you in, please verify your your username and password.'
    });
  });
  $scope.setCurrentUsername = function(user) {
    $scope.username = user.token.split('.')[0];
	$scope.Uid=user.id
  };

  $rootScope.previousState;
  $rootScope.currentState;
  $rootScope.$on('$stateChangeSuccess', function(ev, to, toParams, from, fromParams) {
     $rootScope.previousState = from.name;
     $rootScope.currentState = to.name;
     console.log('Previous state:'+$rootScope.previousState)
     console.log('Current state:'+$rootScope.currentState)
   });
}]);
con.controller('LoginCtrl', ['$scope', '$rootScope','$state', '$ionicPopup','AuthService', '$cordovaCamera',  '$ionicLoading', function($scope, $rootScope, $state, $ionicPopup, AuthService, $cordovaCamera, $ionicLoading) {
  $scope.data = {};
  $scope.login = function(data) {
    AuthService.login(data.username, data.password).then(function(authenticated) {
	console.log(authenticated);
	$scope.setCurrentUsername(authenticated);
	$scope.data.id=authenticated.id;
	if($rootScope.previousState==''){
      $state.go('tab.search', {}, {reload: true});      
	}
	else{$state.go($rootScope.previousState, {}, {reload: false});}
    }, function(err) {
      var alertPopup = $ionicPopup.alert({
        title: 'Login failed!',
        template: 'Please verify your username and password!'
      });
    });
  };
}])
con.controller('RegisterCtrl', ['$scope', '$state', '$ionicPopup', '$http', 'AuthService', function($scope, $state, $ionicPopup, $http,AuthService) {
  $scope.regData={};
  $scope.loading=false;
    $scope.doRegister = function() {
	 $scope.loading=true;
    $http.jsonp('http://172.20.10.4/projects/askmeapp/www/server/register_user.php?callback=JSON_CALLBACK',{ params:$scope.regData}).
        success(function(responseData, status, headers, config) {
            $scope.data=responseData;
			$scope.loading=false;
            $state.go('tab.review', {}, {reload: true});
            AuthService.storeUserCredentials($scope.data)
            $scope.setCurrentUsername($scope.data.token.split('.')[0]);
        },
        function(err) {
		  $scope.loading=false;
          var alertPopup = $ionicPopup.alert({
          title: 'Registration failed!',
          template: 'Please try again!',
        });
    });
  };
}])
con.controller('SearchCtrl', ['$scope','$http', 'searchPara', function($scope,$http,searchPara) {
  $scope.loading=false;
  $scope.searchPara=searchPara.data();

$scope.searchSer=function(){
	$scope.loading=true;
  $http.jsonp('http://172.20.10.4/projects/askmeapp/www/server/fetchServiceRQ.php?callback=JSON_CALLBACK',{ params:$scope.searchPara}).
      success(function(responseData, status, headers, config) {
          $scope.searchResult=responseData;
		  $scope.loading=false;
      }),
        function(err) {
		  $scope.loading=false;
		  $scope.info=true;
		  $scope.message='Error Searching for service Please try again.';
		}
}
}]);

con.controller('reviewCtrl', ['$scope', 'searchData', 'reviewData', '$http', 'AuthService', function($scope, searchData, reviewData, $http, AuthService) {
	$scope.loading=false;
	$scope.info=false;
	$scope.selectedItem;
	$scope.Categories;
	$scope.Sdata=searchData.data();
	$scope.rD=reviewData.data();
	//if($scope.rD.action=='st')$scope.action='Post Review'; else $scope.action='Add A new Service';
	$scope.stateList=[{name:'Select State', value:0},{name:'Lagos', value:'Lagos'},{name:'Ogun', value:'Ogun'},{name:'Oyo', value:'oyo'},{name:'Anambra', value:'anambra'},{name:'Abuja', value:'Abuja'},{name:'Kaduna', value:'Kaduna'},{name:'Imo', value:'Imo'},{name:'Kwara', value:'Kwara'}];
	$scope.localList=[{name:'Select Local Governement', value:0}, {name:'Ikeja', value:'Ikeja'},{name:'Surulere', value:'Surulere'},{name:'Mushin', value:'Mushin'},{name:'Yaba', value:'Yaba'}];
	$scope.Industry=[{name:'Select Industry', value:0},{name:'Fast Food', value:'Fast Food'},{name:'Restaurant and Bars', value:'Restaurant and Bars'},{name:'Tourism and Travels', value:'Tourism and Travels'},{name:'Hotels', value:'Hotels'}];
  $scope.selectResults=function(re){console.log('herese'+re.id)}
  $scope.sendReview=function(id){
	  $scope.loading=true;
		$scope.para={review:$scope.reviewForm, userId:id};
    $http.jsonp('http://172.20.10.4/projects/askmeapp/www/server/review-upload.php?callback=JSON_CALLBACK',{ params:$scope.searchPara}).
        success(function(responseData, status, headers, config) {
          console.log(responseData);
		  $scope.loading=false;
		  $scope.info=true;
		  $scope.message='Your Review was posted Successfully';
        }),
        function(err) {
		  $scope.loading=false;
		  $scope.info=true;
		  $scope.message='Error Posting Reviews, Please try again. '+err;
		}
	}
	//this code retireves all categories associated ***flouts the programmer's code of conduct DRY - this is already implement in autocomplete.js***
	$scope.get_servicetypes=function(){
	$scope.loading=true;
    $scope.urlp='http://172.20.10.4/projects/askmeapp/www/server/fetchSRQ.php?service_no='+$scope.Sdata[0].service_id;
    $http.jsonp($scope.urlp+'&callback=JSON_CALLBACK').
        success(function(responseData, status, headers, config) {
          $scope.Categories=responseData;
        }),
        function(err) {
		  $scope.loading=false;
		  $scope.info=true;
		  $scope.message='Error Searching for service Please try again. '+err;
		}
  }
  $scope.reveal=function(){	  
  	$scope.rD.Categories= $scope.Categories;	  
	$scope.rD.formreveal=1;	
	$scope.rD.action='Post your Review';	 
	$scope.info=false; 
  }
	$scope.get_post=function(pnum, ptype, ind){
	$scope.loading=true;
    $scope.para={parent_no:pnum, p_type:ptype}
    $http.jsonp('http://172.20.10.4/projects/askmeapp/www/server/fetchPRQ.php?callback=JSON_CALLBACK',{ params:$scope.para}).
        success(function(responseData, status, headers, config) {
		  $scope.loading=false;
          res=responseData;
      		$scope.rD.post.push([]);
    			for($i=0; $i<res.length; $i++){
    				var total_rate=0;
    				var t_rater=0;
    				$scope.rD.Categories[ind].post=[];
    				for(property in res){
    				  if(res.hasOwnProperty(property)){
    					  var properti=res[property];
    					  var ptype=properti.p_ctype;
    					  if(ptype=='.c.'){
    						$scope.rD.Categories[ind].post.push([properti]);
    						console.log($scope.rD.Categories)
    					  }
    					  if(ptype=='.r.'){
    						  total_rate+=parseInt(properti.p_cont);
    						  t_rater++;
    						}
    					}
    				}
    			}
    			$scope.rD.Categories[ind].rate=parseInt(total_rate/t_rater);
    			$scope.rD.Categories[ind].t_rate=t_rater;
    			var rev={}
    			rev.newp='N';
    			rev.parent=pnum;
    			rev.ptype=ptype;
    			$scope.rD.post[ind]=rev;
    			newr=['N', pnum, ptype];
    			$scope.rD.Categories[ind].newrr=newr;
    		}),
        function(err) {
		  $scope.loading=false;
		  $scope.info=true;
		  $scope.message='Error retrieving Posts, Please try again. '+err;
		}
	}
	$scope.getLocation=function(){
        navigator.geolocation.getCurrentPosition(onSuccess, onError);
    }
	var onSuccess = function(position) {
        // alert('Latitude: '          + position.coords.latitude          + '\n' +
        //       'Longitude: '         + position.coords.longitude         + '\n' +
        //       'Altitude: '          + position.coords.altitude          + '\n' +
        //       'Accuracy: '          + position.coords.accuracy          + '\n' +
        //       'Altitude Accuracy: ' + position.coords.altitudeAccuracy  + '\n' +
        //       'Heading: '           + position.coords.heading           + '\n' +
        //       'Speed: '             + position.coords.speed             + '\n' +
        //       'Timestamp: '         + position.timestamp                + '\n');
              $scope.laglong=position.coords.latitude+','+position.coords.longitude;
              $http({method:'GET', url:'http://maps.googleapis.com/maps/api/geocode/json?latlng='+$scope.laglong+'&sensor=true'}).
                  then(function successCallback(response) {
                     console.log(response.data.results);
					 $scope.locationGm=response.data.results
                     $scope.addAddress={formatted_address:'Add Address', place_id:0, location:''};
					 $scope.locationGm.push($scope.addAddress);
              		},
                  function errorCallback(err) {
                    var alertPopup = $ionicPopup.alert({
                    title: 'Cannot get your current Location!',
                    template: 'Sorry we couldn\'t get your current Location; Please try again later!',
                  });
            })
    };

    // onError Callback receives a PositionError object
    //
    function onError(error) {
        alert('code: '    + error.code    + '\n' +
              'message: ' + error.message + '\n');
    }
	$scope.selectedLoc=function(si){
		$scope.rD.reviewForm.Place_Id=si.place_id;
		$f=si.address_components.length-1;
		$scope.rD.reviewForm.country=si.address_components[$f].long_name;
		if(si.address_components[$f-1])	$scope.rD.reviewForm.province=si.address_components[$f-1].long_name
		if(si.address_components[$f-2])$scope.rD.reviewForm.city=si.address_components[$f-2].long_name
		if(si.address_components[$f-3])$scope.rD.reviewForm.nighbourhood=si.address_components[$f-3].long_name
		if(si.address_components[$f-4])$scope.rD.reviewForm.route_street=si.address_components[$f-4].long_name
	}
	$scope.postReview=function(user, type){
		$scope.loading=true;
		if(type=='Post your Review'){
			var post=$scope.rD.post;
			var s_no=$scope.rD.formreveal;
			p_type='st';
			console.log(post)
      $scope.para={user:user, post:JSON.stringify(post), type:p_type, serv_no:s_no};
      $http.jsonp('http://172.20.10.4/projects/askmeapp/www/server/post.php?callback=JSON_CALLBACK',{ params:$scope.para}).
          success(function(responseData, status, headers, config) {
			$scope.loading=false;
			$scope.info=true;
			$scope.message='Your review as been successfully posted. Thank you.'
            for(r=0; r<post.length; r++){
				if (post[r].newp=='Y'){
					$scope.get_post(post[r].parent, 'st', r)
					post[r].newp=='N'; post[r].newc=='';post[r].newr=='';
				}
			}
			}),
			function(err) {
			  $scope.loading=false;
			  $scope.info=true;
			  $scope.message='Error Posting your reviews, Please try again. '+err;
			}
		}
		else{
			var serv=$scope.rD.reviewForm;
			
      $scope.para={user:user, post:JSON.stringify(serv)};
	  console.log($scope.para)
      $http.jsonp('http://172.20.10.4/projects/askmeapp/www/server/create_service.php?callback=JSON_CALLBACK',{ params:$scope.para}).
          success(function(responseData, status, headers, config) {
          id=responseData;
		  console.log(id);
		  $scope.loading=false;
		  $scope.info=true;
		  $scope.message=serv.B_Name+' was successfully added to our directory, click the review button below to add a review. ';
		  $scope.Sdata[0].service_id=id 
		  $scope.Sdata[0].service_name=serv.B_Name
		  $scope.Sdata[0].service_province=serv.province
		   $scope.Sdata[0].service_country=serv.country
		  if(serv.route_street)$scope.Sdata[0].service_address=serv.route_street;
		  if(serv.nighbourhood)$scope.Sdata[0].service_address=$scope.Sdata[0].service_address+', '+serv.nighbourhood;
		  if(serv.city)$scope.Sdata[0].service_address=$scope.Sdata[0].service_address+', '+serv.city;
		  $scope.get_servicetypes();
		  }),
        function(err) {
		  $scope.loading=false;
		  $scope.info=true;
		  $scope.message='Error creating new service, Please try again. '+err;
		}
		}
	}
}]);
con.controller('AccountCtrl', function($scope) {
	$scope.settings = {
	enableFriends: true
  };
});
con.directive("userDetails", ['$http', function($http) {
  return {
    template: "<span>{{fullname}}</span>",
    scope: {
      userId: "="
    },
    link: function(scope) {
		scope.para={userNo:scope.userId};
		$http.jsonp('http://172.20.10.4/projects/askmeapp/www/server/fetchURQ.php?callback=JSON_CALLBACK',{ params:scope.para}).
			success(function(res, status, headers, config) {
			try{scope.fullname=res[0].fname+' '+res[0].lname;}
			catch(e){scope.fullname='Unknown User';}
            //console.log(responseData);
			})
      }
  }
}]);