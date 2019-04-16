function removeResult (code) {

	$.ajax({
	    url: "/pbci/saved/ajaxremovesaved",
	    type: "POST",
	    data: {'csrf_token_name': $.cookie('csrf_cookie_name'), 'code': code},
	    success: function(html){
	       code = code.replace(".","");
	       code = code.replace("/","");
	       $('#section' + code).replaceWith("");
	       alert(html);
	    },
	    error: function(){
	       alert("There was a problem adding your entry");
	    }
	});

}
