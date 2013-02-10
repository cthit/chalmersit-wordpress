(function($){
	if(!window.localStorage)
		return false;

	var store = window.localStorage;

	$.persist = {
		clear: function() {
			store.removeItem("period");
			store.removeItem("year");
		},

		save: function(data) {
			store.setItem("period", data.period);
			store.setItem("year", data.year);

			console.log("Saved: ", data);
		},

		load: function(opts) {
			var year = store.getItem("year"),
				period = store.getItem("period");

			opts.year.filter("[value='"+year+"']").attr("checked", true);
			opts.period.filter("[value='"+period+"']").attr("checked", true);

			opts.callback();
		}
	};

	$.fn.persist = function(options) {

		return this.each(function() {
			var $year = $(this).find(options.year),
				$period = $(this).find(options.period),
				save = $(options.save),
				clear = $(options.clear);

			$.persist.load({
				year: $year,
				period: $period,
				callback: options.callback
			});

			$year.add($period).on("change", function(evt) {
				save.attr("disabled", false);
				options.callback(evt);
			});

			save.on("click", function(evt) {
				evt.preventDefault();
				$.persist.save({
					year: parseInt($year.filter(":checked").val()),
					period: parseInt($period.filter(":checked").val())
				});

				clear.attr("disabled", false);
				save.attr("disabled", true);
			});

			clear.on("click", function(evt) {
				evt.preventDefault();
				$.persist.clear();
				clear.attr("disabled", true);
			});

		});

	}

})(jQuery);

$(function() {

	var $form = $("#courses-form"),
		$inputs = $form.find("input"),
		$container = $("#courses-container");

	$form.persist({
		year: "#years-field input",
		period: "#periods-field input",
		save: "#save-config",
		clear: "#clear-config",
		callback: function(evt) {

			$form.find(".loading").fadeIn();

			var data = {
				action: "it_courses_filter",
				year: parseInt($form.find("#years-field input").filter(":checked").val()),
				period: parseInt($form.find("#periods-field input").filter(":checked").val())
			};

			$.get(pageOptions.ajaxURL, data, function(response) {
				$form.find(".loading").hide();
				$container.html(response);
			});

	}
	});

});