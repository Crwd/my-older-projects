//-----------------------------
//
//	Togetherfy: alpha
//
//-----------------------------

var core 			= require("./config/core");
var fs				= require("fs");
var express 		= require("express");
var app	 			= express();
	lang 			= require("i18n");

var bodyParser	 	= require("body-parser");
var cookieParser 	= require('cookie-parser');
var connection 	 	= require("./classes/connection");

var server 		 	= require("http").Server(app);
var io 				= require("socket.io")(server, {'pingInterval': 2000, 'pingTimeout': 5000});
var antiSpam 		= require("socket-anti-spam");

//antiSpam.init({
//  banTime:            30,         // Ban time in minutes 
//  kickThreshold:      200,        // User gets kicked after this many spam score 
//  kickTimesBeforeBan: 1,          // User gets banned after this many kicks 
//  banning:            true,       // Uses temp IP banning after kickTimesBeforeBan 
//  heartBeatStale:     40,         // Removes a heartbeat after this many seconds 
//  heartBeatCheck:     4,          // Checks a heartbeat per this many seconds 
//  io:                 io,  		// Bind the socket.io variable 
//})

/////////////////////////////
//
// LANGUAGE SET
//
/////////////////////////////

lang.configure({
	locales: core.LOCALES,
	defaultLocale: "en",
	directory: __dirname + "/locales",
	cookie: "user_locale",
});

app.use(cookieParser());
app.use(lang.init);

/////////////////////////////
//
// LOCALS SET
//
/////////////////////////////

var templateHandler = require(__dirname + "/classes/templateHandler")();
var views = templateHandler.getViews();

app.use(function(req, res, next) {
	res.locals.view = false;
	res.locals.locales = core.LOCALES;
	res.locals.site_name = core.SITE_NAME;
	res.locals.views = views;
	next();
});


/////////////////////////////
//
// EXPRESS SET
//
/////////////////////////////

app.set("view engine", "ejs");
app.set("views", __dirname + "/views");
app.use("/public", express.static("public"));

app.use(bodyParser.urlencoded({
	extended: true
}));

var router = express.Router();
app.use(router);

/////////////////////////////
//
// TEMPLATE HANDLER
//
/////////////////////////////

for (i in views) {
	var route  = __dirname + "/routes/" + i;
	var handler;

	if (fs.existsSync(route)) {
		handler = require(route)(i)
	} else {
		handler = function(req, res) {
			res.locals.view = this.view;
			res.render(this.view);
		}.bind({view: i});
	}

	router.get(views[i].url, handler);
}

/////////////////////////////
//
// LOBBY SYSTEM
//
/////////////////////////////

var lobbyHandler = require(__dirname + "/classes/lobbyHandler")(connection, io);
router.post("/lobby/create", function(req, res) {
	lobbyHandler.onCreate(res, router);
});

lobbyHandler.loadLobbys(router);

/////////////////////////////
//
// 404 CATCH
//
/////////////////////////////

app.use(function(req, res) {
	res.send("404 - page not found");
});

server.listen(core.PORT);

