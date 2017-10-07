app.factory('Filters',function($resource)
{
    return $resource(BASE_URL + "/content/:action/:id", {action:'@action',id:'@id'},
        {
            'get': {method: 'POST', isArray:true},
            deleteItem: {method: "POST", isArray:false,params: {action:'deleteOption', id:'@id'}}
        });
});