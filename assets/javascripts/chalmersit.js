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
(function($){
	$.fn.jb_animate = function(animation) {
		return this.each(function() {
			$(this).addClass(animation)
			.on("webkitAnimationEnd animationend", function() {
				$(this).removeClass(animation);
			})
		});
	};
})(jQuery);


/*
	Simple jQuery tabs plugin
*/
(function($){
	
	$.fn.tabs = function(options){
		if(options.tabContainer === undefined){
			return false;
		}
		
		var nav = $(this),
			settings = {
				activeClass: "current",
				useHash: true,
				hashPrefix: "tab-",
				el: "a"
			};
		
		settings = $.extend({}, settings, options);
		
		return this.each(function(){
			var $tabContainers = $(settings.tabContainer),
				hash = location.hash && ("#"+ settings.hashPrefix + location.hash.replace("#","")),
				which = (settings.useHash && hash) || ":first";

			$tabContainers.hide().filter(which).show();
			
			$(this).find(settings.el).on("click", function(evt){
				evt.preventDefault();
				var tab = $tabContainers.filter(this.hash);

				$tabContainers.hide();
				tab.show();
					
				nav.find(settings.el).removeClass(settings.activeClass);
				$(this).addClass(settings.activeClass);
				location.hash = tab.attr("id").replace(settings.hashPrefix, "");
				
			});

			if(which == ":first")
				$(this).find(settings.el).filter(which).click();
			else
				$(this).find(settings.el).filter('[href="'+which+'"]').click();
			
		});
	};
	
})(jQuery);


/*
	Load more posts dynamically from frontpage
*/
(function($){
	$.loadMorePosts = function(container) {

		var $container = $(container),
			pageNum = pageOptions.startPage + 1,
			max = pageOptions.maxPages,
			nextLink = pageOptions.nextLink;

		$container.find("footer").remove();

		if(pageNum <= max) {
			$container
				.append('<div class="post-placeholder-'+ pageNum +'"></div>')
				.append('<footer><a href="#" class="btn wide">Fler nyheter</a></footer>');
		}

		$(".news footer a").on("click", function(evt) {
			evt.preventDefault();
			var $button = $(this);

			if(pageNum <= max) {
				$button.text("Laddar ...");

				$(".post-placeholder-"+pageNum).load(nextLink + " [role='article']", function() {
					pageNum++;
					nextLink = nextLink.replace(/\/page\/[0-9]?/, '/page/'+ pageNum);

					$('.news > footer').before('<div class="post-placeholder-'+ pageNum +'"></div>');

					if(pageNum <= max) {
						$button.text("Fler nyheter");
					}
					else {
						$button.text("Inga fler nyheter finns").attr("disabled", true);
					}
						
				});
			}
		});

	};
})(jQuery);

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

	$.fn.tipsy.elementOptions = function(ele, options) {
	  return $.extend({}, options, {
			gravity: $(ele).data('tooltip-gravity') || 's',
			offset: parseInt($(ele).data('tooltip-offset')) || 0
		});
	};

	// Tooltips 
	$('[rel="tooltip"]').tipsy({
		gravity: 's',
		offset: 5
	});

	// Borders on images in posts
	$("article .article-content img").parent().addClass("subtle-border")

	// Set up tabs
	$(".tabs").tabs({
		tabContainer: ".tab-container > div",
		activeClass: "tab-current"
	});
});