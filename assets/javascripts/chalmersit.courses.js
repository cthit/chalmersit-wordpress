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

			if (year || period) {
				$('#clear-config').attr("disabled", false);
			}

			// Sets the input boxes' values
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
					year: $year.filter(":checked").val(),
					period: $period.filter(":checked").val()
				});

				clear.attr("disabled", false);
				save.attr("disabled", true);
			});

			clear.on("click", function(evt) {
				evt.preventDefault();
				$.persist.clear();
				$year.val('-1');
				$period.val('-1');
				clear.attr("disabled", true);
			});

		});

	};

})(jQuery);

$(function() {

	function showHideClass(general, value) {
		if (value != '-1') {
			$('.' + general).hide();
			$('.'+ value).show();
		} else {
			$('.' + general).show();
		}
	}

	var $form = $("#courses-form"),
		$inputs = $form.find("input"),
		$container = $("#courses-container");

	$form.persist({
		year: "#years-field input",
		period: "#periods-field input",
		save: "#save-config",
		clear: "#clear-config",
		callback: function(evt) {

			var year = $form.find("#years-field input").filter(":checked").val(),
				periods = $form.find("#periods-field input").filter(":checked").val();

			showHideClass('year', year);
			showHideClass('lp', periods);

	}
	});

});
