function saveResult (code) {

	$.ajax({
	    url: "/pbci/saved/ajaxaddsaved",
	    type: "POST",
	    data: {'csrf_token_name': $.cookie('csrf_cookie_name'), 'code': code},
	    success: function(html){
	       code = code.replace(".","");
	       code = code.replace("/","");
	       $('#add' + code).replaceWith("<span class=\"saved\">This selection has been saved</span>");
	       alert(html);
	    },
	    error: function(){
	       alert("There was a problem adding your entry");
	    }
	});

}
