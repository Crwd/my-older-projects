var Template = function(view) {
	this.view = view;

	this.export = function(req, res) {
		res.locals.view = this.view;
		res.render(this.view);
	}
}

module.exports = Template;