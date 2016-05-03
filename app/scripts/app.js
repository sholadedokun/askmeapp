 var app = angular.module('AskMeApp', ['ionic', 'ngCordova', 'angucomplete','starter.controllers', 'appServices', 'ngResource'])

app.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

    app.config(function($stateProvider, $urlRouterProvider) {
      $urlRouterProvider.otherwise('/')
	  $stateProvider
      .state('tab', {
      url: '/tab',
      abstract: true,
      templateUrl: 'templates/tabs.html'
    })

    // Each tab has its own nav history stack:

    .state('tab.home', {
      url: '/home',
      views: {
        'tab-home': {
          templateUrl: 'templates/search.html',
          controller: 'SearchCtrl'
        }
      }
    })

    .state('tab.review', {
        url: '/review',
        views: {
          'tab-review': {
            templateUrl: 'templates/review.html',
            controller: 'reviewCtrl'
          }
        }
      })
      .state('tab.profile', {
        url: '/profile',
        views: {
          'tab-profile': {
            templateUrl: 'templates/profile.php',
            controller: 'AccountCtrl'
          }
        }
      })

    .state('tab.account', {
      url: '/account',
      views: {
        'tab-account': {
          templateUrl: 'templates/tab-account.html',
          controller: 'AccountCtrl'
        }
      }
    });

    // if none of the above states are matched, use this as the fallback
    $urlRouterProvider.otherwise('/tab/home');
    })
