/////////////////////////////
//
// VARS
//
/////////////////////////////

var api = document.createElement('script');
api.src = "//www.youtube.com/iframe_api";
var init = document.getElementsByTagName('script')[0];
init.parentNode.insertBefore(api, init);

var player = false;
var changingTime = false;
var lobbyData = {};
var connectionReady = false;

var socket = io();

/////////////////////////////
//
// INFOBOX
//
/////////////////////////////

function showInfobox(title, message, time) {
	var duration = 3000;
	if (parseInt(time)) {
		duration = time;
	}

	$(".notification").remove();
	$('body').append('<div class="notification" style="display:none;"><div class="notification-header">' + title + '</div><p>' + message + '</p></div>');
	$(".notification").slideDown();

	var box = $(".notification:last-child");
	setTimeout(function() {
		box.slideUp(false, function() {
			$(this).remove();
		});
	}, duration);
}

/////////////////////////////
//
// TIME CONVERSION
//
/////////////////////////////

function formatSeconds(seconds) {
	var date = new Date(null);
	date.setSeconds(seconds);
	return date.toISOString().substr(11, 8);
}

function formatTime(time) {
	var date = new Date(time);
	var h = date.getHours();
	var m = date.getMinutes();

	if (h < 10) { h = "0" + h}
	if (m < 10) { m = "0" + m}

	return h + ":" + m;
}

function converTime(PT, format) {
	var output = [];
	var durationInSec = 0;
	var matches = PT.match(/P(?:(\d*)Y)?(?:(\d*)M)?(?:(\d*)W)?(?:(\d*)D)?T?(?:(\d*)H)?(?:(\d*)M)?(?:(\d*)S)?/i);
	var parts = [
	{ // years
	  pos: 1, 
	  multiplier: 86400 * 365
	},
	{ // months
	  pos: 2,
	  multiplier: 86400 * 30
	},
	{ // weeks
	  pos: 3,
	  multiplier: 604800
	},
	{ // days
	  pos: 4,
	  multiplier: 86400
	},
	{ // hours
	  pos: 5,
	  multiplier: 3600
	},
	{ // minutes
	  pos: 6,
	  multiplier: 60
	},
	{ // seconds
	  pos: 7,
	  multiplier: 1
	}
	];

	for (var i = 0; i < parts.length; i++) {
	if (typeof matches[parts[i].pos] != 'undefined') {
	  durationInSec += parseInt(matches[parts[i].pos]) * parts[i].multiplier;
	}
	}
	var totalSec = durationInSec;
	// Hours extraction
	if (durationInSec > 3599) {
	output.push(parseInt(durationInSec / 3600));
	durationInSec %= 3600;
	}
	// Minutes extraction with leading zero
	output.push(('0' + parseInt(durationInSec / 60)).slice(-2));
	// Seconds extraction with leading zero
	output.push(('0' + durationInSec % 60).slice(-2));
	if (format === undefined)
	return output.join(':');
	else if (format === 'sec')
	return totalSec;
}

/////////////////////////////
//
// PLAYER EVENTS / STATES
//
/////////////////////////////

function isPlayerPaused() {
	var state = player.getPlayerState();
	if (state == -1 || state == 0 || state == 2 || state == 5) {
		return true;
	} else if(state == 1) {
		return false;
	}
}

function isPlayerStable() {
	var state = player.getPlayerState();

	//if (state == 3 || state == -1) {
	if (state == 3) {
		return false;
	}

	return true;
}

function setPlayerState(bool) {
	if (bool) {
		player.playVideo();
	} else {
		player.pauseVideo();
	}
}

function onPlaybackQualityChange() {
	$(".quality-levels ul li").removeClass("active");
	$(".quality-levels ul li[data='" + player.getPlaybackQuality() + "']").addClass("active");
}

function onPlaybackRateChange() {
	$(".speed-levels ul li").removeClass("active");
	$(".speed-levels ul li[data='" + player.getPlaybackQuality() + "']").addClass("active");
}

function onPlayerStateChange(event) {
	if (isPlayerPaused()) {
		$(".play-button").html("<span class='glyphicon glyphicon-play'>");
	} else {
		$(".play-button").html("<span class='glyphicon glyphicon-pause'>");

		$(".timeline-bar").slider({
			min: 0,
			max: player.getDuration(),
		});

		$(".timeline-bar").slider("value", player.getCurrentTime());
		$(".player-time").html(formatSeconds(player.getCurrentTime()) + " / " + formatSeconds(player.getDuration()));
	}

	var levels = player.getAvailableQualityLevels();
	var levelNames = {
		"highres": "UHD",
		"hd1080": "1080p",
		"hd720": "720p",
		"large": "420p",
		"medium": "360p",
		"small": "240p",
		"tiny": "140p",
		"auto": "auto",
	};

	$(".quality-levels ul").html("");
	$(".speed-levels ul").html("");

	for (i in levels) {
		if (levels[i] != undefined && levelNames[levels[i]] != undefined) { 
			$(".quality-levels ul").append("<li data='" + levels[i] + "'>" + levelNames[levels[i]] + "</li>");
		}
	}

	var speeds = player.getAvailablePlaybackRates();
	for (i in speeds) {
		if (speeds[i] != undefined) { 
			$(".speed-levels ul").append("<li data='" + speeds[i] + "'>" + speeds[i] + "x</li>");
		}
	}

	$(".quality-levels ul").find("li[data='" + player.getPlaybackQuality() + "']").addClass("active");
	$(".speed-levels ul").find("li[data='" + player.getPlaybackRate() + "']").addClass("active");

	$(".quality-levels ul li").click(function() {
		player.setPlaybackQuality($(this).attr("data"));
	});

	$(".speed-levels ul li").click(function() {
		onRequestPlaybackRate($(this).attr("data"));
	});

	if (connectionReady == true) {
		var playing = !isPlayerPaused();
		if (lobbyData.playing != playing && event.data != 3 && event.data != -1) {
			socket.emit("onSwitchVideoState", playing);
		}

		if (event.data == 0) {
			socket.emit("onVideoFinished", lobbyData.video);
		}
	}

	$(".lobby-title").html(player.getVideoData().title);
}

function onTick() {
	if (!isPlayerPaused()) {
		if (!changingTime) {
			$(".timeline-bar").slider("value", player.getCurrentTime());
			$(".player-time").html(formatSeconds(player.getCurrentTime()) + " / " + formatSeconds(player.getDuration()));
		}
	}
}

function onStartTimeChange() {
	changingTime = true;
}

function onStopTimeChange() {
	var value = $(".timeline-bar").slider("value");
	player.seekTo(value);
	changingTime = false;
	onRequestSeek(value);
}

function onPlayerReady() {
	$(".timeline-bar").slider({
		min: 0,
		max: player.getDuration(),
	});

	player.stopVideo();
	setPlayerState(false);
	player.setVolume(50);

	$(".volume-bar").slider("value", player.getVolume());
	setInterval(onTick, 500);

	$(".player-time").html("00:00:00 / " + formatSeconds(player.getDuration()));
	$(".lobby-title").html(player.getVideoData().title);

	$(".quality-levels ul").html("<li>No found</li>");
	$(".speed-levels ul").html("<li>No found</li>");

	socket.emit("onRequestLobby", LOBBY_ID, LOBBY_CODE);

	socket.on("sendLobbyInfo", function(data) {
		lobbyData.video = data.video;
		player.loadVideoById(data.video, 0);
		player.stopVideo();
		player.setPlaybackRate(data.speed);

		onLobbyLoaded(data);
	});
}

function onYouTubePlayerAPIReady() {
	player = new YT.Player("video-player", {
	  height: "100%",
	  width: "100%",
	  videoId: "",
	  playerVars: {"autoplay": 0, "controls": 0, "disablekb": 1, "rel":0, "showinfo": 0, "modestbranding": 1, "iv_load_policy": 3},

	  events: {
	    'onReady': onPlayerReady,
	    'onStateChange': onPlayerStateChange,
	    'onPlaybackQualityChange': onPlaybackQualityChange,
	    'onPlaybackRateChange': onPlaybackRateChange,
	  }
	});
}

var screen = document.querySelector.bind(document);

/////////////////////////////
//
//  PLAYER CONTROLS
//
/////////////////////////////

function onVolumeChange(event, ui) {
	player.setVolume(ui.value);
}

$(".timeline-bar").slider({
	orientation: "horizontal",
	range: "min",
	max: 1,
	value: 0,
	animate: false,
	start: onStartTimeChange,
	stop: onStopTimeChange,
});

$(".volume-bar").slider({
	orientation: "vertical",
	range: "min",
	max: 100,
	value: 50,
	change: onVolumeChange,
});

$(".action-control").click(function(event) {
	event.stopPropagation();
	if ($(this).find(".control-item").hasClass("active")) {
		var attr = $(event.target).attr("menu");
		if (typeof attr === typeof undefined || attr === false) {
			$(".settings-menu ul div").hide();
			$(this).find(".plop-area").slideUp(200);
			$(this).find(".control-item").removeClass("active");
		}
	} else {
		$(".settings-menu ul div").hide();
		$(".action-control").find(".control-item").removeClass("active");
		$(".action-control").find(".plop-area").slideUp(200);
		$(this).find(".plop-area").slideDown(200);
		$(this).find(".control-item").addClass("active");
	}
});

$(".settings-menu ul li").click(function() {
	$(".settings-menu ul").find("div").hide();
	$(".settings-menu ul").find("div[menu='" + $(this).attr("menu") + "']").show(500);
});

$(window).click(function() {
	$(".action-control").find(".control-item").removeClass("active");
	$(".action-control").find(".plop-area").slideUp(200);
	$(".settings-menu ul div").hide();
});

$(".play-button").click(function() {
	var state = player.getPlayerState();
	if (state == -1 || state == 0 || state == 2 || state == 5) {
		setPlayerState(true);
	} else if(state == 1) {
		setPlayerState(false);
	}
});

$(".open-fullscreen").click(function() {
	var iframe = screen("#video-player");
	var requestFullScreen = iframe.requestFullScreen || iframe.mozRequestFullScreen || iframe.webkitRequestFullScreen;
	if (requestFullScreen) {
		requestFullScreen.bind(iframe)();
	};

	$(".action-control").find(".control-item").removeClass("active");
	$(".action-control").find(".plop-area").slideUp(200);
});

$(".control-area").mouseenter(function() {
	$(".timeline-bar span").fadeIn();
}).mouseleave(function() {
	$(".timeline-bar span").fadeOut();
});

/////////////////////////////
//
// PLAYLISTS 
//
/////////////////////////////

$("#switch-checkbox").bootstrapSwitch({
	size: "small",
	onText: "",
	offText: "",
	wrapperClass: "shuffle-option",
	onColor: "success",
	offColor: "default",
});

$("#switch-checkbox").on("switchChange.bootstrapSwitch", function(event, state) {
	socket.emit("switchShuffleState", state);
});

socket.on("onShuffleSwitch", function(state) {
	$("#switch-checkbox").bootstrapSwitch("state", state, true);
});

$(".playlist-settings").click(function() {
	$(".playlist-manager").modal("show");
});

$(".add-playlist").click(function() {
	var name = $(".playlist-add-input").val();
	if (name.length < 1) {
		return showInfobox("Adding playlist", "Playlist name invalid", 5000);
	}

	socket.emit("addPlaylist", name);
});

$(".playlist-names").change(function() {
	socket.emit("changePlaylist", $(this).val());
});
