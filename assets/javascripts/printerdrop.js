if ($('#drophere')[0] !== undefined) {
	(function() {
		var formData,
			$drop = $('#upload'),
			$body = $('body'),
			$form = $('#print-form');

		$drop.html('Släpp filen eller klicka här!');

		function cancelEvent(e) {
			e.stopPropagation();
			e.preventDefault();
		}
		function toggleDragOver(e) {
			e.stopPropagation();
			if (!$drop.hasClass('dropped')) {
				$drop.toggleClass('hover');
			}
		}
		function setFormData(file) {
			if (formData === undefined) {
				formData = new FormData($form[0]);
			}
			if (file !== undefined) {
				formData.append('upload', file, file.name);
			}
		}
		$body.on('dragenter', toggleDragOver);
		$body.on('dragleave', toggleDragOver);
		$drop.on('drop', function(e) {
			console.log(e.type);
			$drop.toggleClass('hover');
		});
		$form.on('beforesubmit', function() {
			if (window.sessionStorage) {
				var name = window.sessionStorage.getItem('tmp_name');
				$('#sessionStorage').val(name);
			}
		});
	}());
}