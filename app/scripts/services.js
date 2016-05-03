var appServices=angular.module('appServices', ['ngResource'])
/*appServices.config(function ($httpProvider) {
  $httpProvider.interceptors.push('AuthInterceptor');
});*/
appServices.service('searchData', function(){
  var savedData =  [	 {service_term:'', service_loc:'', service_id:'', service_desc:''}	]
  return{
     data:function() {   return savedData;	 },
  }
})
appServices.service('searchPara', function(){
  var savedData =  {searchType:'P', searchED:'N',  searchDesc:'',  searchEL:'N', searchLocation:'lagos'}
  return{
     data:function() {   return savedData;	 },
  }
})
appServices.service('reviewData', function(){
  var savedData =  {
    reviewForm:{Place_Id:1, Industry:0, B_Name:''},
    formreveal:'a', // to hide the form
    post:[], //stores current post
    recentR:[], // stores recent reveiws
    action:'Post your Review',
    Categories:[]
  }
  return{
     data:function(){ return savedData;},
  }
})
appServices.filter('posttime', function(){
  return function (input) {
    var n =moment.unix(input).fromNow();
    return n;
  }
});
//appServices.service('AuthService', function($q, $http, USER_ROLES) {
appServices.service('AuthService', function($q, $http) {
  var LOCAL_TOKEN_KEY = 'myAskToken';
  var username = '';
  var isAuthenticated = false;
  var role = '';
  var authToken={'token':'', 'id':''};
  function loadUserCredentials() {
    var token = window.localStorage.getItem(LOCAL_TOKEN_KEY);
    if (token) {
      useCredentials(token);
    }
  }
  var storeUserCredentials=function (token) {
    window.localStorage.setItem(LOCAL_TOKEN_KEY, token);
    useCredentials(token);
  }
  function useCredentials(token) {
    username = token.token.split('.')[0];
    user_ro= token.token.split('.')[1];
	user_id= token.id;
    isAuthenticated = true;
    authToken = token;
	if (user_ro == 'admin') {
      role = USER_ROLES.admin
    }
    else{
      role = USER_ROLES.public
    }	
	// Set the token as header for your requests!
    $http.defaults.headers.common['X-Auth-Token'] = token;
  }

  function destroyUserCredentials() {
    authToken = undefined;
    username = '';
    isAuthenticated = false;
    $http.defaults.headers.common['X-Auth-Token'] = undefined;
    window.localStorage.removeItem(LOCAL_TOKEN_KEY);
  }

  var login = function(name, pw) {
    return $q(function(resolve, reject) {
      if (name != '' && pw != '') {
        var logData={email:name, pass:pw}
        $http.jsonp('http://172.20.10.4/projects/askmeapp/www/server/login.php?callback=JSON_CALLBACK',{ params:logData}).
        success(function(responseData, status, headers, config) {
          var logResult=responseData
          if(logResult.errorM){reject(logResult.errorM)}
          else{storeUserCredentials(responseData);
          resolve(responseData);}
        },
        function(err) {reject('Login Failed.'); });

      } else {
        reject('Login Failed.');
      }
    });
  };

  var logout = function() {
    destroyUserCredentials();
  };

  var isAuthorized = function(authorizedRoles) {
    if (!angular.isArray(authorizedRoles)) {
      authorizedRoles = [authorizedRoles];
    }
    return (isAuthenticated && authorizedRoles.indexOf(role) !== -1);
  };

  //loadUserCredentials();

/*  return {
    login: login,
    logout: logout,
    isAuthorized: isAuthorized,
    isAuthenticated: function() {return isAuthenticated;},
    username: function() {return username;},
    role: function() {return role;}
  };*/
})
/*appServices.factory('AuthInterceptor', function ($rootScope, $q, AUTH_EVENTS) {
  return {
    responseError: function (response) {
      $rootScope.$broadcast({
        401: AUTH_EVENTS.notAuthenticated,
        403: AUTH_EVENTS.notAuthorized
      }[response.status], response);
      return $q.reject(response);
    }
  };
})*/
