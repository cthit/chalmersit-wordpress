if (printerpage) {
	$(function() {
		$('#printer').autocomplete({source: function(req, callback) {
			var re = $.ui.autocomplete.escapeRegex(req.term);
			var matcher = new RegExp('^' + re, "i");
			var maxNum = 15;
			var a = $.grep(printers, function(item) {
				var matches = matcher.test(item);
				if (matches && maxNum-- < 0) return false;
				return matches;
			});
			callback(a);
		}});
	});
}