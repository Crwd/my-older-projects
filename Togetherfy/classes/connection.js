var core 		= require("../config/core");
var Sequelize	= require("sequelize");

var sequelize = new Sequelize(core.MYSQL_DB, core.MYSQL_USER, core.MYSQL_PASS, {
	host: core.MYSQL_HOST,
	dialect: "mysql",
	logging: false
});

sequelize.__select = function(columns, table, statement, replacements) {
	if (!statement) {
		statement = true;
	}

	return sequelize.query("SELECT " + columns + " FROM " + table + " WHERE " + statement, {
		type: sequelize.QueryTypes.SELECT,
		replacements: replacements
	});
}

sequelize.__insert = function(table, columns, values, replacements) {
	return sequelize.query("INSERT INTO " + table + " (" + columns + ") VALUES (" + values + ") ", {
		type: sequelize.QueryTypes.INSERT,
		replacements: replacements
	});
}

sequelize.__update = function(table, sets, statement, replacements) {
	if (!statement) {
		statement = true;
	}

	return sequelize.query("UPDATE " + table + " SET " + sets + " WHERE " + statement, {
		type: sequelize.QueryTypes.UPDATE,
		replacements: replacements
	});
}

sequelize.__delete = function(table, statement, replacements) {
	if (!statement) {
		statement = true;
	}

	return sequelize.query("DELETE FROM " + table + " WHERE " + statement, {
		type: sequelize.QueryTypes.UPDATE,
		replacements: replacements
	});
}

module.exports = sequelize;