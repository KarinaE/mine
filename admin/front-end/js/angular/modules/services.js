app.factory('Filters',function($resource)
{
    return $resource(BASE_URL + "/modules/:action/:id", {action:'@action',id:'@id'},
        {
            'get': {method: 'POST', isArray:true},
            addOption: {method: "POST", isArray:true,
                params: {
                    action:'addOption',
                    id:'@id',
                    name:'@name',
                    type:'@type',
                    data:'@data',
                    ordr:'@ordr'
                }},
            deleteItem: {method: "POST", isArray:false,params: {action:'deleteOption', id:'@id'}}
        });
});