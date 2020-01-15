/*common.js*/
common_messages = Array();
common_messages["error_connect_to_server"] = "Ошибка связи с сервером. Пожалуйста попробуйте ещё раз.";
common_messages["empty_login"] = "Не указано имя пользователя.";
common_messages["empty_password"] = "Не указан пароль.";
common_messages["forbidden"] = "Доступ запрещён.";

jQuery(document).ready(function () {

	$('body').on("click", "fieldset.collapse .legend", fieldset_collapse);
	$('body').on("click", "a.checkbox", a_checkbox);
	$('body').on("click", "a.radio", a_radio);
	$('body').on("keydown", ".ui-autocomplete-input", autoCompleteKeyPress);
	$('body').on("focus", "input", inputFocus);
	$('body').on("mouseleave", ".errorMessage", function() { $(this).hide(); });
	$('body').on("mouseleave", ".error.errorMessage", function() { $(this).hide(); });

	$('.errorMessage').setErrPos();
	
	$("input").each(function () {
		if($(this).attr("defaultvalue") != "") {
			if($(this).val() == "") { $(this).val($(this).attr("defaultvalue")); $(this).addClass("default"); }
			$(this).focus(clearDefVal);
			$(this).blur(setDefVal);
		} 
	});

  $( window ).bind( "resize scroll", function() {
    var top_ = $(".top_banner_position").length>0?$(".top_banner_position").offset().top:0;
    var bottom_ = $("#bottom_banner_position").length>0?$("#bottom_banner_position").offset().top:500;
    if($(".fixed-menu").length>0)   posFixedMenu(".fixed-menu",7,top_ ,bottom_);
    if($(".fixed-banner").length>0) posFixedMenu(".fixed-banner",126,top_+146,bottom_);
  });

  $( window ).load( function() {
    var top_ = $(".top_banner_position").length>0?$(".top_banner_position").offset().top:0;
    var bottom_ = $("#bottom_banner_position").length>0?$("#bottom_banner_position").offset().top:500;
    if($(".fixed-menu").length>0)   posFixedMenu(".fixed-menu",7,top_ ,bottom_);
    if($(".fixed-banner").length>0) posFixedMenu(".fixed-banner",126,top_+146,bottom_);
  });
	

});

function print_r(arr, level) {
    var print_red_text = "";
    if(!level) level = 0;
    var level_padding = "";
    for(var j=0; j<level+1; j++) level_padding += "    ";
    if(typeof(arr) == 'object') {
        for(var item in arr) {
            var value = arr[item];
            if(typeof(value) == 'object') {
                print_red_text += level_padding + "'" + item + "' :\n";
                print_red_text += print_r(value,level+1);
		} 
            else 
                print_red_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
        }
    } 

    else  print_red_text = "===>"+arr+"<===("+typeof(arr)+")";
	return print_red_text;
}

function ajax_server_error(data, err_type, is_dialog) {
	var m_ajax_error_messages = new Array();
	m_ajax_error_messages[1] = "Ошибка связи с сервером. Пожалуйста проверьте подключение к интернету и попробуйте ещё раз.";
	m_ajax_error_messages[2] = "Ошибка связи с сервером.";
	err_type = err_type || 1;
	if(data.status == 403) {
		alert(common_messages["forbidden"]);
	}
	else {
		alert(data.responseText);
		if(is_dialog) {
			var pos = $(".ui-dialog-titlebar").offset();
			var cssObj = { left: pos.left+5, top: pos.top+5 };
			$("#ajax-error-message").css(cssObj);
		} else {
			var cssObj = { left: 10, top: 10 };
			$("#ajax-error-message").css(cssObj);
		}
		$("#ajax-error-message").html(m_ajax_error_messages[err_type]).show();
	}
}

function ajax_server_error_hide() { $("#ajax-error-message").hide(); }

function ajax_selectbox_load(m_this, url, data, target, after_func) {
	jQuery.ajax({'type':'POST','url':url,'data':data,'cache':false,'success':function(html) { jQuery(target).html(html); jQuery(target).selectbox("detach").selectbox("attach"); if(after_func) after_func(); }});
	return false;
}

function fieldset_collapse()
{
	m_fieldset = $(this).parent();
	if(m_fieldset.hasClass("collapsed")) {
		m_fieldset.children(".inner").css({ 'display': 'none' });
		m_fieldset.removeClass("collapsed");
		m_fieldset.children(".inner").slideDown();
	} else {
		m_fieldset.children(".inner").slideUp(400, function() { m_fieldset.addClass("collapsed"); });
	}
}

function a_checkbox()
{
	if($(this).hasClass("active")) {
		$(this).removeClass("active");
		$('input[name="'+$(this).attr("name")+'"][value="'+$(this).attr("rel")+'"]').removeAttr("checked");
	} else {
		$(this).addClass("active");
		$('input[name="'+$(this).attr("name")+'"][value="'+$(this).attr("rel")+'"]').attr("checked", "checked");
	}
	return false;
}

function a_radio()
{
	if(!$(this).hasClass("active")) {
		$('a[name="'+$(this).attr("name")+'"]').removeClass("active");
		$(this).addClass("active");
		$('input[name="'+$(this).attr("name")+'"]').removeAttr("checked");
		$('input[name="'+$(this).attr("name")+'"][value="'+$(this).attr("rel")+'"]').attr("checked", "checked");
	}
	return false;
}

(function( $ ){
   $.fn.setErrPos = function() {
      $(this).each(function(){$(this).css('left', ($(this).parent().width() / 2 -$(this).width()/2-5) );});
   }; 
})( jQuery );

function autoCompleteResponse(event, ui) {//alert($("#"+$(this).attr("name")).parent().children(".errorMessage").length);
	if(!ui.content.length) $(this).parent().children(".errorMessage").text(messages["ac_not_found"]).show().setErrPos();
}

function autoCompleteKeyPress() {
	$(this).parent().children(".errorMessage").hide();
}

function inputFocus() {
	$(this).parent().children(".errorMessage").hide();
}

function setDefVal() {
	if($(this).val() == "") { $(this).val($(this).attr("defaultvalue")); $(this).addClass("default"); 
		if( $(this).attr("defaultvalue")=="Пароль") 
			if ( $(this).attr('type')== "password")
			    this.setAttribute("type", "text"); 
			else
				this.setAttribute("type", "password"); 				
	
	}
	
}

function clearDefVal() {
	if($(this).val() == $(this).attr("defaultvalue")) { $(this).val(""); $(this).removeClass("default"); 
		  this.setAttribute("type", "text");
		if( $(this).attr("defaultvalue")=="Пароль") 
			if ( $(this).attr('type')== "password")
			    this.setAttribute("type", "text"); 
			else
				this.setAttribute("type", "password"); 
	}
	
}


function posFixedMenu(object,fix, from_top , from_bottom) {
  var banner_position = from_top-$(document).scrollTop();
  last_banner = $('#fixed-banner-wrapper').children('div').last();
  b = last_banner.offset().top + last_banner.height() ;

  c = $('#fixed-banner-wrapper .fixed-menu').innerHeight()+ $('#fixed-banner-wrapper .fixed-banner').innerHeight();

  if ($(document).scrollTop()+fix+c <  from_bottom  && last_banner.hasClass('sticky')    ){
    last_banner.removeClass('sticky');
  }

  if( from_bottom <= b  || last_banner.hasClass('sticky')   )
  {
    magic_number =  $('#fixed-banner-wrapper .fixed-banner').length>0 ?   ( object == '.fixed-banner' ? 135 :0  )  : 10;
    banner_position =  magic_number+ from_bottom-(fix+$(document).scrollTop()+c ) ;
    last_banner.addClass('sticky');
  }


  if(banner_position< fix && !last_banner.hasClass('sticky')   ){
    banner_position = fix;
  }

  $(object).css("top", banner_position);


  if( $( window ).width()<970){
    $(object).css("left",460-$( window ).scrollLeft()+'px'   );
    $(object).css("right",'auto'   );
  }else{
    $(object).css("left",'50%'   );
    $(object).css("right",'auto'   );

  }

}