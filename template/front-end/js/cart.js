$(document).ready(function()
{    
    // show preview
    $('div.cart-block').on('click', function(e)
    {
        var itemId = $(this).attr("data-id");
        // remove active
        $(".cart-item-active").fadeOut(1);
        $(".cart-item-active").removeClass("cart-item-active").addClass("cart-item");
        // add active to selected
        $("#item-"+itemId).fadeIn("slow");
        $("#item-"+itemId).addClass("cart-item-active");   
    });
    
    // change decription view
    var descrd = 0;
    $('.cart-diamond').on('click', function()
    {
        if(descrd == 0)
        {
            $(".item-image").fadeOut(1);
            $(".cart-diamond-description").fadeIn("slow");
            descrd = 1;  
        }
        else
        {
            $(".cart-diamond-description").fadeOut(1);
            $(".item-image").fadeIn("slow");      
            descrd = 0;       
        }  
    });
    
    // changing dial country code on country change
    $('select[name=country]').on('change', function() 
    {
        //remove selected one
        $('option:selected', 'select[name="code"]').removeAttr('selected');
        //Using the value
        $('select[name="code"]').find('option[value="'+ $("select[name=country] option:selected").text() +'"]').attr("selected",true);
        //Using the text
        $('span.filter-option').html($('option:selected', 'select[name="code"]').html());
    })
    
    $('#checkoutForm button').on('click', function(e)
    {
        e.preventDefault();
        // show loading block
        $(".loading").show();
        $("#ppplus").show();
        
        // slide down
        // slide up
        $('#checkout').animate({
            scrollTop: $("#ppplus").offset().top
        }, 1600); 
        // get costumer form values
        var values = {};
        $("#checkoutForm .form-control").each(function()
        {
            values[$(this).attr('name')] = $(this).val();
        });
        
        // post data for ajax
        var data = new FormData();
        data.append('checkout', 1);
        data.append('costumer',JSON.stringify(values));

        // add order to basket
        $.ajax({
            type: 'POST',               
            processData: false,
            contentType: false,
            data: data,
            url: base+"core/helpers/ajax/basket.php",
            success:
                function(jsonData)
            {
                //create payment
                $.ajax({
                    type: 'POST',               
                    processData: false,
                    contentType: false,
                    data: data,
                    url: base+"/sample/payments/CreatePaymentUsingPayPal.php",
                    success: function(jsonData)
                    {
                        $(".loading").hide();
                        var ppp = PAYPAL.apps.PPP({
                            "approvalUrl": jsonData,
                            "placeholder": "ppplus",
                            "mode": "sandbox",
                            "country": values["country"]                            
                        });
                    }
                });
            }
        });
    });   
});

// deleting item from basket
function itemDelete(messege,id)
{    
    if (confirm(messege)) 
    {
        var data = new FormData();
        // remove big preview
        data.append('removeId', id);
        
        $.ajax({
            type: 'POST',               
            processData: false,
            contentType: false,
            data:data,
            url: base+"core/helpers/ajax/basket.php",
            success: function()
            {
                location.reload();                        
            }
        })
    }
};