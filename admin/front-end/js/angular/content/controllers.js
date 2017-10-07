app.controller("ContentModuleCtrl", function ($scope,Filters)
{
    // getting product options filter
    Filters.get({action:'getAddedProducts',id:EDIT_ID}).$promise.then(function(data){
        $scope.addedList = data;
    });

    $scope.showList = function(search)
    {
        // search only after 2 symbols
        if(search.length > 2)
            Filters.get({action:'getProductsList',val:search}).$promise.then(function(data){
                $scope.productsList = data;
            });
        else
            $scope.productsList = null;
    }

    // adding new value
    $scope.addProduct = function(id)
    {
        // if added succesfully - list updates
        Filters.get({action:'addProduct',content_id:EDIT_ID,product_id:id}).$promise.then(function(data) {
            $scope.addedList = data;
        },function(error)
        {
            console.error(error);
        });
    }

    // delete data
    $scope.deleteProduct = function(id)
    {
        // sending request to server
        Filters.get({action:'deleteProduct',id:id,content_id:EDIT_ID}).$promise.then(function(data)
        {
            if(data[0] == 1)
                console.log("+");
            $scope.addedList = data;
        },function(error)
        {
            console.error(error);
        });
    }
});