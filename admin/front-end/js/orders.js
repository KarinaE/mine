$(document).ready(function()
{
    $('.orders td i').dblclick(function(e)
    {
        $('select#edit').unbind('blur');
       	//get clicked element
		var t = e.target || e.srcElement;
		//getting tag name
		var elm_name = t.tagName.toLowerCase();
		// false if input
		if(elm_name == 'input')	{return false;}
		//getting value
		var value  = $(this).html();
        var id     = $(this).data('id');
        var db     = $(this).data('db');
        var type   = $(this).data('type');
        var option = $(this).data('option');
        
        // actions are different for different types
        if(type == 'select')
        {
            $(this).fadeOut(0);

            if(option)
                $(this).next('.'+option).fadeIn(400);
            else
                $(this).next().fadeIn(400);

            $('#edit').focus();
        }
        else
        {
            // 2 types of input
            var code = $(this).data('type') == 'input' ? '<input type="text" id="edit" value="'+value+'" />' : '<textarea id="edit">'+val+'</textarea>';
    		$(this).empty().html(code);
    		$('#edit, textarea').focus();          
        }

        // action save
        $('#edit, textarea').blur(function()	
        {
            // getting values
            var object = $(this);
            var data = new FormData();
            data.append('type', type);
            data.append('db', db);
            data.append('value', $(this).val());
            data.append('option', option);
            // final action on AJAX success
            $.ajax({
                type: 'POST',
                processData: false,
                contentType: false,               
                data: data,
                url: BASE_URL + '/order/changeValue/' + id,
                success: function(jsonData)
                {
                    if(type == 'select')
                    {
                        if(option || db.split('-')[1] == 'size')
                        {
                            object.prev('i').empty().html(jsonData.split(',')[0]);
                            object.fadeOut(0);
                            object.prev().fadeIn(400);
                        }
                        else
                        {
                            object.prev('i').empty().html(jsonData.split(',')[0]);
                            object.fadeOut(0);
                            object.prev().fadeIn(400);
                            object.closest('tr').removeClass(object.closest('tr').attr("class"));
                            object.closest('tr').addClass(jsonData.split(',')[1]);
                        }
                    }    
                    else
                        object.parent().empty().html(jsonData);
                }
            });
            return false;
		});    
    });
});