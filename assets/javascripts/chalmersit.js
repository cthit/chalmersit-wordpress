/*
	Javascript functions for Chalmers.it
	
-------------------------------------------- */

/**
*	Quick and dirty jQuery animation helper plugin
*
*	Usage:
*	$("#element").jb_animate("<animation name>")
*
*	The single parameter, animation name, should be defined
*	as a CSS class. The class is applied to the element. The class
*	should include a CSS animation. Example:
*
*	.shake {
*		-webkit-animation: shake 1s ease-out;
* 	}
*/
$.fn.jb_animate = function(animation) {
	return this.each(function() {
		$(this).addClass(animation)
		.on("webkitAnimationEnd animationend", function() {
			$(this).removeClass(animation);
		})
	});
};


$(function() {

	/* Front page functions */

	// Shake the login form when clicking the 'Log in' button
	$(".home #login-btn").on("click", function(evt) {
		evt.preventDefault();
		$(".user-area form")
			.jb_animate("shake")
			.find("input[type='text']:first")
			.focus();
	});


	// Set up smooth scrolling links
	$(".smooth").smoothScroll({
		offset: -100
	});

	$(".comment-action").smoothScroll({
		afterScroll: function() {
			$("#comment").focus();
		}
	});


	// Show comment controls on comment textarea focus
	$("#comment").on("focus", function(evt){
		$(this).next(".comment-submit").show();
	});

	// Auto growing textareas
	$(".autosize").autosize({append: "\n\n\n"});

});