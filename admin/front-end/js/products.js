var opts = new Object;

$(document).ready(function()
{
    $('a.upload').on('click', function(e)
    {
        e.preventDefault();   
        $('div.loading').css('display', 'block');
        var data = new FormData();
        // allow form data
        data.append('img', $('input[type="file"]').prop('files')[0]);
        data.append('main', $('input[type="checkbox"]').prop('checked'));
    
        $.ajax({
            type: 'POST',               
            processData: false,
            contentType: false,
            data: data,
            url: BASE_URL + '/product/uploadImage/' + id,
            success: function(jsonData)
            {
                if(jsonData)
                    alert(jsonData);
                getImages();
                getOptionsImages();
                $('div.loading').css('display', 'none');
            }
        }); 
    });


    $('a.upload-opt').on('click', function(e)
    {
        e.preventDefault();   
        $('div.loading').css('display', 'block');
        var data = new FormData();
        // allow form data
        data.append('option', $('select[name="option"]').val());
        data.append('order', $('input[name="order"]').val());
            

        $.ajax({
            type: 'POST',               
            processData: false,
            contentType: false,
            data: data,
            url: BASE_URL + '/product/addOption/' + id,
            success: function(jsonData)
            {
                if(jsonData)
                    alert(jsonData);
                getOptions();
                renderOptions();
                getOptionsImages();
                $('div.loading').css('display', 'none');
            }
        }); 
    });
});

// getting ajax content
getImages();
getOptions();
getOptionsImages();
renderOptions();
getSizes();
// getting images
function getImages()
{
    $.get(BASE_URL + '/product/getlist/' + id, null, function(data)
    {
        $('.col-md-5 #images').html(data);
    });
}
// getting options
function getOptions()
{
    $.get(BASE_URL + '/product/getOptions/' + id, null, function(data)
    {
        $('.col-md-12 #options').html(data);
    });
} 
// getting options images
function getOptionsImages(options)
{
    $.get(BASE_URL + '/product/getOptionsImages/' + id, options, function(data)
    {
        $('.col-md-12 #optionsImages').html(data);
    });   
}
// render options
function renderOptions()
{
    $.get(BASE_URL + '/product/renderOptions/' + id, null, function(data)
    {
        $('.col-md-12 #optionsView').html(data);
    });
}
function getSizes(options)
{
    $.get(BASE_URL + '/product/getSizes/' + id, options, function(data)
    {
        $('.col-md-12 #sizesTable').html(data);
    });
}
// check if basic options 
function checkBase()
{
    var optsFlush;
        
    $.each(opts, function(index, value) 
    {
        if(value == 1 && optsFlush != false)
            optsFlush = true;
        else
            optsFlush = false;      
    });
    
    if(optsFlush == true)
        opts = new Object;
}