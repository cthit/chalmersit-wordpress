if (printerpage) {
	$(function() {
		$('#printer').click(function(){this.select();});
		$('#printer').autocomplete({source: function(req, callback) {
			var re = req.term.trim();
			var maxNum = 15;
			var res = printers;
			function eachItem(match, item) {
				return item.indexOf(match) != -1;
			}
			$(re.split(' ')).each(function() {
				var that = this;
				res = $.grep(res, function(item) {
					return eachItem(that, item);
				});
			});
			callback(res);
		}});
	});
}