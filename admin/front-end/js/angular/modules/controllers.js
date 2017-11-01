app.controller("FiltersModuleCtrl", function ($scope,Filters)
{
    // getting module options
    Filters.get({action:'getOptions'}).$promise.then(function(data){
        $scope.filters = data;
        $scope.filter = data[0];
    });

    // getting categories list
    Filters.get({action:'getCategories'}).$promise.then(function(data){
        $scope.categories = data;
        $scope.category_select = data[0];
    });
    // getting brands list
    Filters.get({action:'getBrands'}).$promise.then(function(data){
        $scope.brands = data;
        $scope.brand_select = data[0];
    });
    // getting product options filter
    Filters.get({action:'getProductOptions'}).$promise.then(function(data){
        $scope.options = data;
        $scope.option_select = data[0];
    });

    // get list of options added to module
    Filters.get({action:'getOptionsList',id:EDIT_ID}).$promise.then(function(data){
        $scope.moduleOptions = data;
    });

    // adding new value
    $scope.addOption = function()
    {
        // picking value for data field in module option
        $scope.data = $scope.filter['id'] == 1 ? $scope.category_select :
            $scope.filter['id'] == 2 ? $scope.brand_select :
                $scope.options_select;

        // sending request to server
        Filters.addOption(
            {
                id: EDIT_ID,
                name: $scope.filter['name'],
                type: $scope.filter['id'],
                data: $scope.data['id'],
                ordr: $scope.order
            }).$promise.then(function(data)
        {
            // fast check if ok
            if(data.length == 0)
                console.log('+');
            // getting new list of module parameters
            Filters.get({action:'getOptionsList',id:EDIT_ID}).$promise.then(function(data){
                $scope.moduleOptions = data;
            });
        },function(error)
        {
            console.error(error);
        });
    }


    // delete data
    $scope.deleteOption = function(id)
    {
        // sending request to server
        Filters.deleteItem({id: id}).$promise.then(function(data)
        {
            // fast check if ok
            if(data[0] == 1)
                console.log("+");

            // getting new list of module parameters
            Filters.get({action:'getOptionsList',id:EDIT_ID}).$promise.then(function(data){
                $scope.moduleOptions = data;
            });
        },function(error)
        {
            console.error(error);
        });
    }
});