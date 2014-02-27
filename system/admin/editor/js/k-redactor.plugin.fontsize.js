if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.fontsize = {
	init: function()
	{
		var fonts = [10, 11, 12, 14, 16, 18, 20, 24, 28, 30, 40, 45, 50, 55, 60, 65, 70, 75, 90, 100];
		var that = this;
		var dropdown = {};

		$.each(fonts, function(i, s)
		{
			dropdown['s' + i] = { title: s + 'px', callback: function() { that.setFontsize(s); } };
		});

		dropdown['remove'] = { title: 'Размер по умолчанию', callback: function() { that.resetFontsize(); } };

		this.buttonAdd( 'fontsize', 'Размер шрифта', false, dropdown);
	},
	setFontsize: function(size)
	{
		this.inlineSetStyle('font-size', size + 'px');
	},
	resetFontsize: function()
	{
		this.inlineRemoveStyle('font-size');
	}
};