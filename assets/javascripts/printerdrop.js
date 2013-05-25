if (printerpage) {
	$(function() {
		$('#printer').click(function(){this.select();});
		$('#printer').autocomplete({source: function(req, callback) {
			var maxNum = 15;
			var a = $.grep(printers, function(item) {
				var matches = req.test(item);
				if (matches && maxNum-- < 0) return false;
				return matches;
			});
			callback(a);
		}});
	});
}