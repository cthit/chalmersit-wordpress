$(function() {

	var $form = $("#courses-form"),
		$inputs = $form.find("input"),
		$container = $("#courses-container");

	$inputs.on("change", function(evt) {

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

	});

});