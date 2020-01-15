$(document).ready(function () {
    $(".contact-radio-container button").click( 
        function(){ 
            type = $(this).attr('data-input-id');
            $("#ContactForm_type").val(type);
            $("#contact_category").removeAttr('disabled');
            $("#contact_category").css('display', 'block');
            $(".contact-radio-container button").removeClass('selected');
            $(this).addClass('selected');
            $("#contact_category").empty();
            
            $("#contact_category").append( $('<option value="">Выбрать</option>'));
            for(var i in registry.cat[type]) {
                if (!registry.cat[type].hasOwnProperty(i)) continue;
                $("#contact_category").append( $('<option value="'+i+'">' + registry.cat[type][i] + '</option>'));
            } 
            return false;
        });  
        
        $("#contact_category").change( 
        function(){ 
            if($("#contact_category option:selected").val() != ''){
                $("input").removeAttr('disabled');
                $("textarea").removeAttr('disabled');
            } else {
                $("input").attr('disabled', 'disabled');
                $("textarea").attr('disabled', 'disabled');
            }
        });
});