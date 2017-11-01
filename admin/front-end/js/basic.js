$(document).ready(function()
{
    $('a.clearSrch').click(function(e)
    {
        e.preventDefault();

        $('#filter')[0].reset();
        $('input[name="dtfrom"]').val('');
        $('input[name="dtbefore"]').val('');
        $('input[name="diamonds"]').val('');
        $('select[name="category"]').val('');
        $('select[name="status"]').val('');
        $('select[name="users_group"]').val('');
        $('input[name="fulltext"]').val('');
        $('form#filter').submit();
    });
    
    $('a.delete').click(function()
    {
        if (window.confirm(DELETE_MESSAGE))
        {
            url = $(this).data('pk');
            baseform.action = url + 'delmulti';
            document.baseform.submit();
        }
    });
    
    $('a.activate').click(function()
    {
        url = $(this).data('pk');
        baseform.action = url + 'activate';
        document.baseform.submit();
    });
    
    $('a.deactivate').click(function()
    {
        url = $(this).data('pk');
        baseform.action = url + 'deactivate';
        document.baseform.submit();
    });
    
    $('a.save').click(function()
    {
        url = $(this).data('pk');
        editForm.action = url;
        document.editForm.submit();
    });
    
    $('a.save-exit').click(function()
    {
        url = $(this).data('pk');
        editForm.action = url + '/redirect/quit';
        document.editForm.submit();
    });
    
    $('a.save-add').click(function()
    {
        url = $(this).data('pk');
        editForm.action = url + '/redirect/add';
        document.editForm.submit();
    });
    
    $('a.exit').click(function()
    {
        if (window.confirm(EXIT_MESSAGE))
            window.location = $(this).data('pk');
    });

    $('input[name="select_all"]').click(function()
    {
        if ($(this).is(":checked"))
            $(':checkbox.il').prop('checked', true);
        else
            $(':checkbox.il').removeAttr('checked');
    });
});

function PathGenerator(theForm)
{
    var form = form2array(theForm);
    var link = theForm.action+makeLink(form);
    
    window.location = link;
    return false;
}

function makeLink(data)
{
    var out = '';
    for( key in data )
    {
        if(data[key])
            out = out+key+'/'+data[key]+'/';
    }

    return out;
}

function form2array(theForm) {
    var type;
    var arr = new Object;
    for(i=0; i<theForm.elements.length; i++)
    {
        type = theForm.elements[i].type;
        if(type == "text" || type == "textarea" || type == "password" || type == "hidden" /*|| type == "button"*/ || type == "select-one")
        {
            arr[theForm.elements[i].name] = theForm.elements[i].value;
        } 
        else if(type == "checkbox" || type == "radio")
        {
            if ( theForm.elements[i].checked ) 
            {
                arr[theForm.elements[i].name] = theForm.elements[i].value;
            }
        }
    }
    return arr;
}