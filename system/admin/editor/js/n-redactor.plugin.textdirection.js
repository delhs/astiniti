if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.textdirection = {
	init: function()
	{
		var that = this;
		var dropdown = {};

		dropdown['ltr'] = { title: 'Слева направо', callback: function () { that.ltrTextDirection(); } };
		dropdown['rtl'] = { title: 'Справа налево', callback: function () { that.rtlTextDirection(); } };

		this.buttonAdd('direction', 'Направление текста', false, dropdown);
	},
	rtlTextDirection: function()
	{
		this.bufferSet();
		this.blockSetAttr('dir', 'rtl');
	},
	ltrTextDirection: function()
	{
		this.bufferSet();
		this.blockRemoveAttr('dir');
	}
};