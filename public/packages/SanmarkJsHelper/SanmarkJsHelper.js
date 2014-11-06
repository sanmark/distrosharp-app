var SanmarkJsHelper = {
	Input: {
		get: function (selector) {
			var val = $(selector).val();
			val = this.removeWhiteSpaces(val);
			return val;
		},
		removeWhiteSpaces: function (value) {
			var leftRightTrim = $.trim(value);
			var afterReplace = leftRightTrim.replace(/\s+/, ' ');
			return afterReplace;
		}
	}
};

