$(document).ready(function(){

	var password = $('.password');

	$('.show-pass').on("click", function(){

	    if (password.attr('type') == 'password') {
	    	password.attr('type', 'text');
	    } else {
	    	password.attr('type', 'password');
	    }


	});

	$('.confirm').click(function () {
		return confirm('Are You Sure to delete this membre');
	});

});