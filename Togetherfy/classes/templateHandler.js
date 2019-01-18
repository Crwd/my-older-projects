var TemplateHandler = function() {
	this.Views = {
		"home": {url: "/", name: "NAV_HOME"},
		"faq": {url: "/faq", name: "NAV_FAQ"},
		//"lobby": {url: "/lobby", name: "NAV_LOBBY"},
	};

	this.getViews = function() {
		return this.Views;
	}

	return this;
}

module.exports = TemplateHandler;