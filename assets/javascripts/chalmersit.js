/*
	Javascript functions for Chalmers.it

-------------------------------------------- */

/*
	jQuery Search and Replace plugin
 */

(function($) {

	$.fn.replaceText = function(search, replace, text_only) {
	return this.each(function() {
	  var node = this.firstChild,
	    val, new_val,

	    // Elements to be removed at the end.
	    remove = [];

	  // Only continue if firstChild exists.
	  if (node) {

	    // Loop over all childNodes.
	    do {

	      // Only process text nodes.
	      if (node.nodeType === 3) {

	        // The original node value.
	        val = node.nodeValue;

	        // The new value.
	        new_val = val.replace(search, replace);

	        // Only replace text if the new value is actually different!
	        if (new_val !== val) {

	          if (!text_only && /</.test(new_val)) {
	            // The new value contains HTML, set it in a slower but far more
	            // robust way.
	            $(node).before(new_val);

	            // Don't remove the node yet, or the loop will lose its place.
	            remove.push(node);
	          } else {
	            // The new value contains no HTML, so it can be set in this
	            // very fast, simple way.
	            node.nodeValue = new_val;
	          }
	        }
	      }

	    } while (node = node.nextSibling);
	  }

	  // Time to remove those elements!
	  remove.length && $(remove).remove();
	});
	}

})(jQuery);

var Chalmers = (function(it) {
	var root = it || {};


	root.linkify = function(text) {
		if (text) {
			text = text.replace(
				/((https?\:\/\/)|(www\.))(\S+)(\w{2,4})(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi,
				function(url){
					var full_url = url;
					if (!full_url.match('^https?:\/\/')) {
						full_url = 'http://' + full_url;
					}

					return '<a href="' + full_url + '">' + url + '</a>';
				});
		    }

		    return text;
	};

	return root;

})(window.Chalmers);

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


	// Create modal for the avatar upload on Profile page
	$("#user-avatar-link").on("click", function(evt){
		evt.preventDefault();
		var iframe = $("<iframe />", {
			src: this.href,
			scrolling: "no",
			id: "avatar-iframe",
			frameborder: "no",
			allowTransparency: "true"
		});

		$("#avatar-modal").append(iframe).modal("show");


		// When clicking the 'close' link in the last step,
		// make sure to hide the modal correctly.

		iframe.on("load", function(evt) {
			$(this).contents().find("#user-avatar-step3-close")
				.removeAttr("onclick")
				.on("click", function(evt){
					evt.preventDefault();
					$("#avatar-modal").modal("hide");
				});
		});
	});


	// Wipe avatar modal on hide
	$("#avatar-modal").on("hidden", function() {
		$(this).find("iframe").remove();
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

	// Setup the main navigation toggle when on smaller screens
	$("#main-nav-toggle").on("click", function(evt) {
		$(".inner-bar").slideToggle(200);
	});

	// Show comment controls on comment textarea focus
	$("#comment")
	.on("focus", function(evt){
		$(this).next(".comment-submit").show().find("#submit").attr("disabled", true);
	})
	.on("input", function(evt) {
		var text = $(this).val(),
			$submit = $(this).next(".comment-submit").find("#submit");

		$submit.attr("disabled", (text === ""));
	});

	// Auto growing textareas
	$(".autosize").autosize({append: "\n\n\n"});

	$.fn.tipsy.elementOptions = function(ele, options) {
	  return $.extend({}, options, {
			gravity: $(ele).data('tooltip-gravity') || 's',
			offset: parseInt($(ele).data('tooltip-offset')) || 0
		});
	};

	// Byt ut alla förekomster av 'sexIT' till 'SEXIT' med större storlek på 'IT'
	// så Anno slutar gnälla ...
	$("h1, h2, p, li, a").replaceText(/sexit/gi, '<span class="sexit">sex<strong>IT</strong></span>');

	// Add support for touch devices for the 'Tools' main nav menu
	if("ontouchstart" in document) {
		$("#tools-menu-trigger").on("touchstart", function(evt) {
			var dropdown = $(this).next(".dropdown-sub");
			if(dropdown.hasClass("open")) {
				dropdown.removeClass("open").slideUp(100);
			}
			else {
				dropdown.addClass("open").slideDown(200);
			}
		});
	}

	// Tooltips
	$('[rel="tooltip"]').tipsy({
		gravity: 's',
		offset: 5
	});

	// Borders on images in posts
	$("article .article-content img:not(.avatar)").each(function() {
		if($(this).parent().is("figure")) {
			$(this).parent().addClass("subtle-border");
		}
	});

	// Set up tabs
	$(".tabs").tabs({
		tabContainer: ".tab-container > div",
		activeClass: "tab-current"
	});


	// Pressing 'Esc' when in search field in header should blur the field
	$("[role='search'] input").on("keyup", function(evt) {
		if(evt.which == 27)
			this.blur();
	});


	// Show Twitter timeline on frontpage

	$('.tweet-list time').each(function() {
		var $this = $(this);
		var time = new Date($this.text());
		$this.text(time.toDateString());
	});

	/*$('.it_twitter').each(function(){
		var that = this;
		var twQContent = $(this).children('meta[name=twitter-content]').attr("content");
		var twQType = $(this).children('meta[name=twitter-type]').attr("content");
		var twQSize = $(this).children('meta[name=twitter-count]').attr("content");
		var twitterUrl = "https://";
		if(twQType === "user"){
			twitterUrl += "api.twitter.com/1/statuses/user_timeline/";
		} // Add hashtag support here

		twitterUrl += twQContent+".json?callback=?";
		console.log(twitterUrl);
		$.getJSON(twitterUrl, function(json, status, xhr) {
			var $list = $("<ul />", {
				"class": "list"
			});

			if(json != null) {
				$.each(json, function(i) {
					var date = new Date(this.created_at),
						text = "<p>" + Chalmers.linkify(this.text) + "</p><time>"+ date.toDateString() +"</time>";
					var element = $("<li />", {
						"html": text
					});

					$list.append(element);

					return i<(twQSize -1);
				});


			}
			else {
				$list.html("<li>Kunde inte hämta tweets från Twitter</li>")
			}

			$(that).find('#tweet-list').append($list);

		});
	});*/

	// Printer autocomplete

	$('#printer').on('click', function() {
		$(this).select();
	});
	$('#printer').autocomplete({
		source: function(req, callback) {
			var re = req.term.trim();
			var res = printers;
			function eachItem(expr, item) {
				return expr.test(item.label + item.desc);
			}
			$(re.split(' ')).each(function() {
				var exp = new RegExp(this, 'i');
				res = $.grep(res, function(item) {
					return eachItem(exp, item);
				});
			});
			var length = res.length;
			var maxLength = 15;
			if (length > maxLength) {
				res.splice(maxLength-1, length - maxLength);
			}
			callback(res);
		}
	}).data('ui-autocomplete')._renderItem = function(ul, item) {
		return $('<li>').append('<a>' + item.label + '<br><small>' + item.desc + '</small></a>').appendTo(ul);
	};

	$('.set-printer').on('click', function() {
		$('#printer').val($(this).data('value'));
	});

	$('.show-more').on('click', function() {
		$(this).remove();
		$('.unusual').show();
	});

});
