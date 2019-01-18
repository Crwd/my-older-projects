<script>
var me = false;

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function onLobbyLoaded(data) {
	me = data.me;

	socket.emit("onRequestVideoData");

	for (i in data.users) {
		var user = data.users[i];
		var addclass = "";

		if (user.id == me) {
			addclass = "me";
		}

		$(".user-profiles").append('<div class="user-profile ' + addclass + '" user="' + user.id + '"><div class="user-name">' + user.name + '</div></div>');
	}

	for (i in data.history) {
		addHistory(data.history[i]);
	}

	$(".playlist-names").html("");

	var cPlaylist = false;

	lobbyData.currentPlaylist = data.currentPlaylist;
	lobbyData.playlists = data.playlists;

	refreshPlaylists(lobbyData.currentPlaylist, lobbyData.playlists);

	$("#switch-checkbox").bootstrapSwitch("state", data.shuffle, true);
}

function onRequestSeek(seconds) {
	if (isPlayerStable()) {
		socket.emit("onRequestSeek", seconds);
	}
}

function onRequestPlaybackRate(rate) {
	if (isPlayerStable()) {
		socket.emit("onRequestPlaybackRate", rate);
	}
}

function onVideoClick() {
	if (isPlayerStable()) {
		var video = $(this).parent();
		var id = video.attr("video-data");

		if (id) {
			socket.emit("onRequestVideo", id);
		}
	}
}

function addHistory(video) {
	var li = $("<li></li>");
	li.html("<div class='video-thumb'><img src='" + video.thumbnail + "' /><div>");
	li.append("<div class='video-title'><span>" + video.title + "</span><br><span class='selector'>picked by " + video.selector + "</span></div>");
	li.attr("video-data", video.id);

	li.click(function() {
		socket.emit("onRequestVideo", $(this).attr("video-data"));
	});

	$(".history-videos ul").append(li);
}

function addPlaylistVideo(video) {
	var li = $("<li></li>");
	li.html("<div class='video-thumb'><img src='" + video.thumbnail + "' /><div>");
	li.append("<div class='video-title'><span>" + video.title + "</span><br><span class='selector'>picked by " + video.selector + "</span></div>");
	li.append("<span class='glyphicon glyphicon-remove remove-video'></span><span class='glyphicon glyphicon-sort sort-video'></span>");

	li.attr("video-data", video.id);
	li.attr("video-sort", video.sort);

	var cPlaylist = false;

	lobbyData.playlists = lobbyData.playlists;

	for (i in lobbyData.playlists) {
		var list = lobbyData.playlists[i];
		if (list.id == lobbyData.currentPlaylist) {
			cPlaylist = list;
		}
	}

	if (cPlaylist) {
		if (video.sort == cPlaylist.position) {
			li.addClass("current");
		}
	}

	li.find(".remove-video").click(function() {
		socket.emit("onRemovePlaylistVideo", $(this).parent().attr("video-sort"));
	});


	li.find(".video-title").click(function() {
		if (isPlayerStable()) {
			socket.emit("onRequestVideo", $(this).parent().attr("video-data"), $(this).parent().attr("video-sort"));
		}
	});

	$(".playlist-videos ul").append(li);
}


var established = false;

socket.on("sendVideoData", function(data) {
	lobbyData = data;
	player.seekTo(data.position, false);

	player.addEventListener("onStateChange", function(e) {
		if (!established) {
			if ((e.data == 0) || (e.data == 1) || (e.data == 2)) {
				established = true;
				connectionReady = true;
				setPlayerState(data.playing);
			}
		}
	});
});

socket.on("setPlaybackRate", function(rate) {
	player.setPlaybackRate(rate);
});

socket.on("setVideoPosition", function(seconds) {
	player.seekTo(seconds);
});

socket.on("requestVideoData", function() {
	socket.emit("onVideoData", {
		position: player.getCurrentTime(),
		playing: !isPlayerPaused(),
	});
});

socket.on("switchVideoState", function(state) {
	lobbyData.playing = state;
	setPlayerState(state);
});

socket.on("onUserJoin", function(user) {
	var message = ('<%= __("LOBBY_CHAT_USER_JOINED") %>').replace("{name}", user.name);
	$(".user-profiles").append('<div class="user-profile" user="' + user.id + '"><div class="user-name">' + user.name + '</div></div>');
	$(".chat-box ul").append('<li><div class="chat-status">** ' + message + ' **</div></li>');
	$(".chat-box").scrollTop($(".chat-box")[0].scrollHeight);
});

socket.on("onUserLeave", function(user) {
	var message = ('<%= __("LOBBY_CHAT_USER_LEFT") %>').replace("{name}", user.name);
	$(".user-profile[user='" + user.id + "']").remove();
	$(".chat-box ul").append('<li><div class="chat-status">** ' + message + ' **</div></li>');
	$(".chat-box").scrollTop($(".chat-box")[0].scrollHeight);
});

socket.on("onRecieveMessage", function(data) {
	var time = formatTime(data.time);
	var addClass = "";
 
	if (data.userid == me) {
		addClass = "me";
	}

	$(".chat-box ul").append(
		'<li style="display:none;" class="' + addClass + '">' +
			'<div class="chat-message">' + 
				'<span class="user-name">' + escapeHtml(data.name) + '</span> ' + 
				escapeHtml(data.message) + 
			'</div>' + 
			'<div class="chat-date">' + time + '</div>' + 
		'</li>'
	);

	$(".chat-box ul li:last-child").fadeIn();

	$(".chat-box").scrollTop($(".chat-box")[0].scrollHeight);
});

function addToPlaylist(video) {
	if (video) {
		socket.emit("onAddPlaylistVideo", video);
	}
}

function addBrowserVideo(result, durations) {
	var col = $("<div class='col-md-4'></div");
	var box = $("<div class='video-box'></div>");
	box.attr("video-data", result.id);

	var thumbnail = $("<div class='thumbnail'></div>");
	thumbnail.html("<div class='video-duration'>" + converTime(durations[result.id]) + "</div><img src='" + result.thumbnails.high.url + "' />");
	box.append(thumbnail);
	thumbnail.click(onVideoClick);

	var title = $("<div class='video-title'><span>" + result.title + "</span></div>");
	box.append(title);
	title.click(onVideoClick);

	var channel = $("<div class='video-publisher'><span>" + result.channelTitle + "</span></div>");
	box.append(channel);

	var playlist = $("<div class='action-area'><button class='btn btn-sm btn-default show-actions'><span class='glyphicon glyphicon-th-large'></span></button></div>");
	var menu = $("<div class='action-menu'><a action='play'><span class='glyphicon glyphicon-play'></span> Play</a><a action='playlist'><span class='glyphicon glyphicon-plus'></span> Add to playlist</a></div>");
	playlist.append(menu);
	box.append(playlist);

	playlist.hover(function() {
		$(this).find(".action-menu").stop();
		$(this).find(".action-menu").slideDown();
	}, function(){
		$(this).find(".action-menu").stop();
		$(this).find(".action-menu").slideUp();
	});

	$(menu).find("a").click(function() {
		$(this).find(".action-menu").stop();
		$(this).parent().slideUp();

		var action = $(this).attr("action");
		if (action == "play") {
			if (isPlayerStable()) {
				socket.emit("onRequestVideo", result.id);
			}
		} else {
			addToPlaylist(result.id);
		}
	});

	col.append(box);

	$(".video-list").append(col);
}

socket.on("onSearchResponse", function(data, durations, tokens) {
	$(".video-list").html("");
	
	for (i in data) {
		var result = data[i];
		addBrowserVideo(result, durations);
	} 

	$(".load-more-action").data("page-token", tokens.nextPageToken);
});

socket.on("onSearchMoreResponse", function(data, durations, tokens) {	
	for (i in data) {
		var result = data[i];
		addBrowserVideo(result, durations);
	} 

	$(".load-more-action").data("page-token", tokens.nextPageToken);
});

socket.on("onVideoChange", function(video, videoData) {
	if (video && isPlayerStable()) {
		lobbyData.video = video;
		lobbyData.playing = true;
		player.loadVideoById(video, 0);
		player.seekTo(0);

		addHistory(videoData);

		//player.playVideo();
	}
});

function refreshPlaylists(current, playlists) {
	$(".playlist-table").html("");
	$(".playlist-names").html("");
	$(".playlist-videos ul").html("");

	lobbyData.currentPlaylist = current;
	lobbyData.playlists = playlists;

	var cPlaylist = false;

	for (i in playlists) {
		var list = playlists[i];
		$(".playlist-names").append("<option value='" + list.id + "'>" + list.name + "</option>");

		if (list.id == current) {
			cPlaylist = list;
		}

		var tr = $("<tr></tr>");
		tr.append("<td>" + list.name + "</td>");

		var remove = $('<button type="button" class="btn btn-danger btn-sm remove-playlist">Delete</button>');
		var importer = $('<button type="button" class="btn btn-primary btn-sm">Playlist importieren</button>');

		remove.attr("playlist", list.id);

		$(remove).click(function() {
			if (confirm("Do you really want to delete this playlist ?")) {
				socket.emit("removePlaylist", $(this).attr("playlist"));
			}
		});
			
		var buttons = $("<td></td>");		
		buttons.append(remove);
		buttons.append(importer);
		tr.append(buttons);

		$(".playlist-table").append(tr)
	}

	if (cPlaylist) {
		var listVideos = cPlaylist.videos;
		for (i in listVideos) {
			addPlaylistVideo(listVideos[i]);
		}

		$(".playlist-names option[value='" + cPlaylist.id + "']").attr("selected", "");
	}
}

socket.on("onPlaylistUpdate", refreshPlaylists);

$(".input-message").on("input", function() {
	$(".input-message").removeClass("error");

	if ($(this).data("ui-tooltip")) {
		$(this).tooltip("destroy");
	}
});

var lastMessage = 0;

$("#chat-form").on("submit", function(event) {
	event.preventDefault();

	var now = Date.now()/1000;
	var message = $(".input-message").val();

	if ((now - lastMessage) <= <%= settings.CHAT_MESSAGE_SPAM_TIME %>) {
		return showInfobox('<%= __("LOBBY_SPAM_PROTECTION_TITLE") %>', '<%= __("LOBBY_SPAM_PROTECTION_TEXT") %>', 3000);
	}

	if (message.length <= <%= settings.CHAT_MESSAGE_MAX_LENGTH %>) {
		socket.emit("onUserMessage", {
			message: message,
		});

		lastMessage = now;

		$(".input-message").val("");
	} else {
		$(".input-message").addClass("error");
		$(".input-message").attr("title", '<%= __("LOBBY_CHAT_MESSAGE_TOO_LONG") %>');

		$(".input-message").tooltip({
			show: null,
			tooltipClass: "tooltip-error",
			position: {
				my: "left center",
				at: "right+10 center-10"
			},

			open: function( event, ui ) {
				ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
			}
		});
	}
});

$("#search-form").on("submit", function(event) {
	event.preventDefault();

	var search = $(".search-input").val();
	$(".load-more-action").data("search", search);
	socket.emit("onSearch", search);
	//$(".search-input").val("");
});

$(".search-button").click(function() {
	var search = $(".search-input").val();
	$(".load-more-action").data("search", search);
	socket.emit("onSearch", search);
	//$(".search-input").val("");
});

$(".load-more-action").click(function() {
	var token = $(this).data("page-token");
	var search = $(this).data("search");

	if (token && search) {
		socket.emit("onSearchMore", search, token);
	}
});

$(".video-sorting").sortable({
	sort: true,
	handle: ".sort-video",
	update: function() {
		var sorts = [];
		$(".video-sorting li").each(function() {
			sorts.push($(this).attr("video-sort"));
		});

		socket.emit("onPlaylistSort", sorts);
	}
});


</script>