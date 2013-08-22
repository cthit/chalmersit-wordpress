if (printerpage) {
	$(function() {
		$('#printer').on('click', function() {
			$(this).select();
		});
		$('#printer').autocomplete({
			source: function(req, callback) {
				var re = req.term.trim();
				var res = printers;
				function eachItem(expr, item) {
					return expr.test(item.printer + item.desc);
				}
				$(re.split(' ')).each(function() {
					var exp = new RegExp(this, 'i');
					res = $.grep(res, function(item) {
						var eachItemRes = eachItem(exp, item);
						return eachItemRes;
					});
				});
				var length = res.length;
				var maxLength = 15;
				if (length > maxLength) {
					res.splice(maxLength-1, length - maxLength);
				}
				callback(res);
			},
			select: function(event, ui) {
				$('#printer').val(ui.item.printer);
				return false;
		}}).data('ui-autocomplete')._renderItem = function(ul, item) {
			return $('<li>').append('<a>' + item.printer + '<br><small>' + item.desc + '</small></a>').appendTo(ul);
		};
	});
}
