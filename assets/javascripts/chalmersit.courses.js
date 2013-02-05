$(function() {

	var $form = $("#courses-form"),
		$inputs = $form.find("input"),
		$container = $("#courses-container");

	$inputs.on("change", function(evt) {

		var data = {
			action: "it_courses_filter",
			year: parseInt($form.find("#years-field input").filter(":checked").val()),
			period: parseInt($form.find("#periods-field input").filter(":checked").val())
		};

		console.log(data);

		$.get(pageOptions.ajaxURL, data, function(response) {
			$container.html(response);
		});

	});

});