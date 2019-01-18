var express = require("express");
var app 	= express();
var server 	= require("http").Server(app);
var io 		= require("socket.io")(server, {'pingInterval': 2000, 'pingTimeout': 5000});

app.set("view engine", "ejs");
app.set("views", __dirname + "/views");

app.use("/public", express.static("public"));

app.get("/", function(req, res) {
	res.render("home");
});

var chat_messages = new Array();
var userid = 0;
var users = new Array();

io.on("connection", function(socket) {
	userid++;

	socket.UserID = userid;
	socket.emit("onRecieveHistory", chat_messages);
	socket.emit("onRecieveID", userid);

	//console.log(users.length);

	console.log("Connect: #" + userid);

	socket.client_users = new Array();
	for (i in users) {
		if (!users[i].connected) {
			users.splice(i, 1);
			io.emit("onUserDisconnect", i);
		} else {
			socket.client_users.push(users[i].UserID);
		}
	}

	socket.emit("onRecieveUsers", socket.client_users);
	io.emit("onUserConnection", userid);
	users.push(socket);

	socket.on("disconnect", function() {
		for (i in users) {
			if (users[i].UserID == socket.UserID) {
				console.log("Disconnect: #" + socket.UserID);
				users.splice(i, 1);
				io.emit("onUserDisconnect", socket.UserID);
			}
		}
	});

	socket.on("onClientMessage", function(data) {
		var content = {
			User: "User #" + this.UserID,
			Message: data.message,
			Time: Date.now()
		};

		chat_messages.push(content);
		io.emit("onRecieveMessage", content);
	});
});

server.listen(80);