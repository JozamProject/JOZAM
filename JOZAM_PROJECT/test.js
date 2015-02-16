angular.module('app', ['ngGridster']);

angular.module('app').controller('MyController', [
  '$scope',
  function ($scope) {
    $scope.options = {
        widget_margins: [10, 10],
        widget_base_dimensions: [140, 140],
        helper: 'clone',
        resize: {
          enabled: true,
          axes: ['x', 'y', 'both'],
          max_size: [2, 2]
        }
    };
    
    $scope.items = [
      1,2,3,6
      ];
	  
	  $scope.items = [
      4,5
      ];  
  }
  
 ]);