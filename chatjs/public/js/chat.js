var entityMap = {
  '&': '&amp;',
  '<': '&lt;',
  '>': '&gt;',
  '"': '&quot;',
  "'": '&#39;',
  '/': '&#x2F;',
  '`': '&#x60;',
  '=': '&#x3D;'
};

function escapeHtml (string) {
  return String(string).replace(/[&<>"'`=\/]/g, function (s) {
    return entityMap[s];
  });
}

var socket = io();

var userid = 0;

$("#msg-form").on("submit", function(event) {
	socket.emit("onClientMessage", {
		message: $(".msg-input").val(),
	});

	$(".msg-input").val("");
	event.preventDefault()
});

socket.on("onRecieveID", function(id) {
	userid = id;
});

socket.on("onRecieveHistory", function(messages) {
	for (i in messages) {
		var data = messages[i];
		var time = new Date(data.Time);
		var date = "[" + time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds() + "]";
		var box = "<li><span class='msg-date'>" + date + " </span><span class='msg-user'>" + data.User + ": </span><span class='msg-content'>" + escapeHtml(data.Message) + "</span></li>";
		$(".shoutbox-messages").append(box);
		$(".shoutbox-messages").scrollTop($(".shoutbox-messages")[0].scrollHeight);
	}
});

socket.on("onRecieveMessage", function(data) {
	var time = new Date(data.Time);
	var date = "[" + time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds() + "]";
	var box = "<li style='display:none;'><span class='msg-date'>" + date + " </span><span class='msg-user'>" + data.User + ": </span><span class='msg-content'>" + escapeHtml(data.Message) + "</span></li>";
	$(".shoutbox-messages").append(box);
	$(".shoutbox-messages li:last-child").fadeIn();
	$(".shoutbox-messages").scrollTop($(".shoutbox-messages")[0].scrollHeight);
});

socket.on("onUserConnection", function(id) {
	var _class = "";
	if (id == userid) {
		_class = "me";
	}

	$(".lobby-list").append("<li style='display:none;' uid='" + id + "' class='" + _class + "'>User #" + id + "</li>");
	$(".lobby-list li:last-child").fadeIn();
	$(".shoutbox-messages").append("<li style='display:none;'><span class='msg-status'>** user #" + id + " joined **</span></li>");
	$(".shoutbox-messages li:last-child").fadeIn();
});

socket.on("onRecieveUsers", function(users) {
	for (i in users) {
		$(".lobby-list").append("<li uid='" + users[i] + "'>User #" + users[i] + "</li>");
	}
});

socket.on("onUserDisconnect", function(id) {
	$(".lobby-list li[uid='" + id + "']").remove();
	$(".shoutbox-messages").append("<li style='display:none;'><span class='msg-status'>** user #" + id + " disconnected **</span></li>");
	$(".shoutbox-messages li:last-child").fadeIn();
});