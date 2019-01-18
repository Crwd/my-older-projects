var settings = require("../config/lobby");
var core = require("../config/core");
var ytSearch = require("youtube-search");
var ytInfo = require("./videoInfo");

function shuffleArray(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
}

var LobbyHandler = function(connection, io) {
	this.Connection = connection;
	this.IO 		= io;
	this.Lobbys 	= {};
	this.LobbyCodes = {};

	this.DefaultRoom = {
		video: settings.DEFAULT_VIDEO,
		users: [],
		history: [],
		speed: settings.DEFAULT_SPEED,
		userid: 0,

		playlists: [
			{name: "Default", videos: [], id: 1, position: -1},
		],

		currentPlaylist: 1,

		shuffle: false,
	};

	this.onRequest = function(req,res) {
		res.locals.lobby = this.lobby;
		res.locals.lobbyID = this.id;
		res.render("lobby", {settings: settings});
	}

	this.onJoin = function(socket, lobby, code) {
		if (this.Lobbys[lobby].Code == code) {
			var userid = this.Lobbys[lobby].Room.userid + 1;
			var username = "Random #" + userid;

			this.Lobbys[lobby].Room.userid = userid;
			this.Lobbys[lobby].Room.users.push({
				socket: socket,
				id: userid,
				name: username,
			});

			socket.UserID = userid;
			socket.Lobby = lobby;

			var Room = this.Lobbys[lobby].Room;

			var clientUsers = [];
			for (i in Room.users) {
				clientUsers.push({
					name: Room.users[i].name,
					id: Room.users[i].id
				});

				var socketUser = Room.users[i].socket;

				if (socketUser != socket) {
					socketUser.emit("onUserJoin", {
						id: socket.UserID,
						name: username
					});
				}
			}

			socket.emit("sendLobbyInfo", {
				video: Room.video,
				speed: Room.speed,
				me: userid,
				history: Room.history,
				users: clientUsers,
				playlists: Room.playlists,
				currentPlaylist: Room.currentPlaylist,
				shuffle: Room.shuffle,
			});
		}
	}

	this.loadLobbys = function(router) {
		this.Connection.__select("ID, Code", "lobbys").then(function(data) {
			for (i in data) {
				var row = data[i];
				this.Lobbys[row.ID] = row;
				this.LobbyCodes[row.Code] = true;

				this.Lobbys[row.ID].Room = JSON.parse(JSON.stringify(this.DefaultRoom)); //this.DefaultRoom;

				router.get("/lobby/" + data[i].Code, this.onRequest.bind({lobby: data[i].Code, id: data[i].ID}));
			}
		});
	}
	
	this.createLobbyCode = function() {
		var code = false;
		var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

		while (code === false && this.LobbyCodes[code] == undefined) {
			code = "";

			for (var i = 0; i < 8; i++) {
				code += chars.charAt(Math.floor(Math.random() * chars.length));
			}
		}

		return code;
	}	

	this.onCreate = function(res, router) {
		var code = this.createLobbyCode();

		this.Connection.__insert("lobbys", "Code", ":code", {code: code}).then(function(data) {
			var id = data[0];
			this.Connection.__select("ID, Code", "lobbys", "ID = :id", {id: id}).then(function(data) {
				var row = data[0];
				this.Lobbys[row.ID] = row;
				this.LobbyCodes[row.Code] = true;

				this.Lobbys[row.ID].Room = JSON.parse(JSON.stringify(this.DefaultRoom)); //this.DefaultRoom;

				router.get("/lobby/" + row.Code, this.onRequest.bind({lobby: row.Code, id: row.ID}));
				res.redirect("/lobby/" + row.Code);

				res.locals.lobby = row.Code;
				res.locals.lobbyID = row.ID;
			});
		});
	}

	this.getLobbyFromSocket = function(socket) {
		var Lobby = this.Lobbys[socket.Lobby];

		if (Lobby != undefined) {
			return Lobby;
		}

		return false;
	}

	this.onSocket = function(socket) {
		var handler = this.handler;
		socket.on("onRequestLobby", function(lobby, code) {
			handler.onJoin(this, lobby, code);
		});

		socket.on("onRequestVideoData", function() {
			handler.onRequestVideoData(this);
		});

		socket.on("onVideoData", function(data) {
			var request = this.requestSocket;
			if (request.connected) {
				//data.position++;
				request.emit("sendVideoData", data);
				request.ready = true;
			}
		});

		socket.on("onSwitchVideoState", function(state) {
			var Lobby = handler.getLobbyFromSocket(this);

			if (Lobby != false) {
				var Room = Lobby.Room; 
				Room.playing = state;

				for (i in Room.users) {
					var user = Room.users[i];
					user.socket.emit("switchVideoState", state);
				}
			}	
		});

		socket.on("disconnect", function() {
			var Lobby = handler.Lobbys[this.Lobby];

			if (Lobby != undefined) {
				var Room = Lobby.Room; 
				var activeUser = false;
				if (this.ready == true) {
					for (i in Room.users) {
						var user = Room.users[i];
						if (user.socket == this) {
							handler.Lobbys[this.Lobby].Room.users.splice(i, 1);
							io.emit("onUserLeave", {
								id: user.id,
								name: user.name
							});
						} else {
							if (user.socket.connected) {
								activeUser = true;
							}
						}

						/////////////////////////////////////////////////////////////////////////
						// DELETING LOBBY ON LAST USER LEFT 								   //
						//handler.Connection.__delete("lobbys", "ID = :id", {id: this.Lobby}); //
						/////////////////////////////////////////////////////////////////////////
					}
				}
			}
		});

		handler.bindEvent("onUserMessage", handler.onUserMessage, socket);
		handler.bindEvent("onRequestSeek", handler.onRequestSeek, socket);
		handler.bindEvent("onRequestPlaybackRate", handler.onRequestPlaybackRate, socket);
		handler.bindEvent("onSearch", handler.onSearch, socket);
		handler.bindEvent("onSearchMore", handler.onSearchMore, socket);
		handler.bindEvent("onRequestVideo", handler.onRequestVideo, socket);
		handler.bindEvent("onAddPlaylistVideo", handler.onAddPlaylistVideo, socket);
		handler.bindEvent("onRemovePlaylistVideo", handler.onRemovePlaylistVideo, socket);
		handler.bindEvent("onPlaylistSort", handler.onPlaylistSort, socket);
		handler.bindEvent("onVideoFinished", handler.onVideoFinished, socket);
		handler.bindEvent("switchShuffleState", handler.switchShuffleState, socket);
		handler.bindEvent("addPlaylist", handler.addPlaylist, socket);
		handler.bindEvent("changePlaylist", handler.changePlaylist, socket);
		handler.bindEvent("removePlaylist", handler.removePlaylist, socket);
	}

	this.bindEvent = function(event, handler, socket) {
		socket.on(event, handler.bind({handler: this, socket: socket}));
	}

	this.removePlaylist = function(id) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		var Room = Lobby.Room;

		if (Lobby != false) {
			for (i in Room.playlists) {
				if (Room.playlists[i].id == id) {
					Room.playlists.splice(i);

					if (Room.currentPlaylist == id) {
						Room.currentPlaylist = Room.playlists[0].id;
					}

					for (i in Room.users) {
						var user = Room.users[i];
						user.socket.emit("onPlaylistUpdate", Room.currentPlaylist, Room.playlists);
					}

					break;
				}
			}
		}
	}

	this.changePlaylist = function(id) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		var Room = Lobby.Room;

		if (Lobby != false) {
			for (i in Room.playlists) {
				if (Room.playlists[i].id == id) {
					Room.currentPlaylist = id;

					for (i in Room.users) {
						var user = Room.users[i];
						user.socket.emit("onPlaylistUpdate", Room.currentPlaylist, Room.playlists);
					}

					break;
				}
			}
		}
	}

	this.addPlaylist = function(name) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		var Room = Lobby.Room;

		if (Lobby != false) {
			if (name.length > 0) {
				var ID = 0;

				for (i in Room.playlists) {
					var v = Room.playlists[i].id;

					if (v >= ID) {
						ID = v + 1;
					}
				}

				Room.playlists.push({
					name: name,
					videos: [],
					id: ID,
					position: -1
				});

				for (i in Room.users) {
					var user = Room.users[i];
					user.socket.emit("onPlaylistUpdate", Room.currentPlaylist, Room.playlists);
				}
			}
		}
	}

	this.switchShuffleState = function(state) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		var Room = Lobby.Room;

		if (Lobby != false) {
			Room.shuffle = state;

			for (i in Room.users) {
				var user = Room.users[i];
				user.socket.emit("onShuffleSwitch", state);
			}
		}

		if (Room.shuffle) {
			var playlist = this.handler.getCurrentPlaylist(Room);
			this.handler.shufflePlaylist(Room, playlist);
		}
	}

	this.shufflePlaylist = function(room, playlist) {
		playlist.position = 0;
		room.shuffleList = JSON.parse(JSON.stringify(playlist.videos));
		shuffleArray(room.shuffleList);
	}

	this.onVideoFinished = function(video) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		var Room = Lobby.Room;

		if (Lobby != false) {
			if (video == Room.video) {
				var lastVideo = Room.video;
				Room.video = false;
				
				var playlist = this.handler.getCurrentPlaylist(Room);
				playlist.position++;


				var video = false;

				if (Room.shuffle) {
					/*var len = playlist.videos.length;
					video = playlist.videos[Math.floor(Math.random() * len)];

					while (video.id == lastVideo && len > 1) {
						video = playlist.videos[Math.floor(Math.random() * len)];
					}*/

					for (i in Room.shuffleList) {
						if (i >= playlist.position) {
							video = Room.shuffleList[i];
							break;
						}
					}

					if (video == false) {
						playlist.position = 0;
						video = Room.shuffleList[0];
					}

				} else {
					for (i in playlist.videos) {
						var data = playlist.videos[i];
						if (data.sort >= playlist.position) {
							playlist.position = data.sort;
							video = data;
							break;
						}
					}
				}

				if (video === false) {
					if (playlist.videos.length > 0) {
						video = playlist.videos[0];
						playlist.position = video.sort;
					} else {
						return false;
					}
				}

				for (i in Room.users) {
					var user = Room.users[i];
					user.socket.emit("onPlaylistUpdate", Room.currentPlaylist, Room.playlists);
				}

				ytInfo([video.id], {key: core.YOUTUBE_API_KEY}, function(error, infos) {
					if (!error) {
						var info = infos[0];
						var videoData = {
							id: video.id,
							duration: info.contentDetails.duration,
							title: info.snippet.title,
							thumbnail: info.snippet.thumbnails.high.url,
							selector: this.handler.getSocketName(this.socket),
						};

						Room.history.push(videoData);

						Lobby.Room.video = video.id;
						Lobby.Room.playing = true;

						for (i in Room.users) {
							var user = Room.users[i];
							user.socket.emit("onVideoChange", video.id, videoData);
						}

						this.socket.emit("onSearchResponse", results, durations, tokens);
					}
				}.bind({socket: socket, handler: this.handler}));
			}
		}
	}

	this.onPlaylistSort = function(sorts) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		var Room = Lobby.Room;

		if (Lobby != false) {
			var playlist = this.handler.getCurrentPlaylist(Room);
			var videos = playlist.videos;
			var sorted = [];

			for (i in sorts) {
				for (j in videos) {
					if (videos[j].sort == sorts[i]) {
						sorted.push(videos[j]);
						videos.splice(j, 1);
						break;
					}
				}
			}

			for (i in videos) {
				sorted.push(videos[i]);
			}

			playlist.videos = [];

			for (i in sorted) {
				playlist.videos[i] = sorted[i];
				playlist.videos[i].sort = i;
			}

			for (i in Room.users) {
				var user = Room.users[i];
				user.socket.emit("onPlaylistUpdate", Room.currentPlaylist, Room.playlists);
			}
		}
	}

	this.onRemovePlaylistVideo = function(sort) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		var Room = Lobby.Room;

		if (Lobby != false) {
			var playlist = this.handler.getCurrentPlaylist(Room);
			for (i in playlist.videos) {
				if (playlist.videos[i].sort == sort) {
					playlist.videos.splice(i, 1);
				}
			}

			for (i in Room.users) {
				var user = Room.users[i];
				user.socket.emit("onPlaylistUpdate", Room.currentPlaylist, Room.playlists);
			}
		}
	}

	this.getCurrentPlaylist = function(room) {
		for (i in room.playlists) {
			var list = room.playlists[i];
			if (list.id == room.currentPlaylist) {
				return list;
			}
		}

		return false;
	}

	this.onAddPlaylistVideo = function(video) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		var Room = Lobby.Room;

		if (Lobby != false) {
			ytInfo([video], {key: core.YOUTUBE_API_KEY}, function(error, infos) {
				if (!error) {
					var playlist = this.handler.getCurrentPlaylist(Room);
					var position = playlist.videos.length;

					var info = infos[0];
					playlist.videos[position] = {
						selector: this.handler.getSocketName(this.socket),
						id: video,
						sort: position,
						duration: info.contentDetails.duration,
						title: info.snippet.title,
						thumbnail: info.snippet.thumbnails.high.url,
					};

					for (i in Room.users) {
						var user = Room.users[i];
						user.socket.emit("onPlaylistUpdate", Room.currentPlaylist, Room.playlists);
					}
				}
			}.bind({handler: this.handler, socket: socket}));
		}

		if (Room.shuffle) {
			var playlist = this.handler.getCurrentPlaylist(Room);
			this.handler.shufflePlaylist(Room, playlist);
		}
	}

	this.onRequestVideo = function(video, playlistSort) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		var Room = Lobby.Room;

		var Handler = this.handler;

		console.log("request")

		if (Lobby != false) {
			if (Room.video != video) {
				ytInfo([video], {key: core.YOUTUBE_API_KEY}, function(error, infos) {
					if (!error) {
						var info = infos[0];
						var videoData = {
							id: video,
							duration: info.contentDetails.duration,
							title: info.snippet.title,
							thumbnail: info.snippet.thumbnails.high.url,
							selector: this.handler.getSocketName(socket),
						};

						Room.history.push(videoData);

						Lobby.Room.video = video;
						Lobby.Room.playing = true;

						for (i in Room.users) {
							var user = Room.users[i];
							user.socket.emit("onVideoChange", video, videoData);
						}

						playlistSort = parseInt(playlistSort);

						if (!isNaN(playlistSort)) {
							var playlist = Handler.getCurrentPlaylist(Room);

							if (!Room.shuffle) {
								playlist.position = playlistSort;
							}
						}

						this.socket.emit("onSearchResponse", results, durations, tokens);
					}
				}.bind({socket: socket, handler: this.handler}));
			}
		}
	}
	
	this.onSearch = function(search) {
		var opts = {
		  maxResults: 6,
		  type: "video",
		  key: core.YOUTUBE_API_KEY
		};

		if (search.indexOf("&list") != -1) {
			search = search.substr(0, search.indexOf("&list"));
		}

		ytSearch(search, opts, function(error, results, tokens) {
			if (!error) {
				var ids = [];

				for (i in results) {
					ids.push(results[i].id);
				}

				var durations = {};

				ytInfo(ids, {key: core.YOUTUBE_API_KEY}, function(error, infos) {
					if (!error) {
						for (i in infos) {
							durations[infos[i].id] = infos[i].contentDetails.duration;
						}

						this.socket.emit("onSearchResponse", results, durations, tokens);
					}
				}.bind({socket: this.socket}));
			}
		}.bind({socket: this.socket}));
	}

	this.onSearchMore = function(search, token) {
		var opts = {
		  maxResults: 6,
		  type: "video",
		  key: core.YOUTUBE_API_KEY,
		  pageToken: token
		};

		if (search.indexOf("&list") != -1) {
			search = search.substr(0, search.indexOf("&list"));
		}

		ytSearch(search, opts, function(error, results, tokens) {
			if (!error) {
				var ids = [];

				for (i in results) {
					ids.push(results[i].id);
				}

				var durations = {};

				ytInfo(ids, {key: core.YOUTUBE_API_KEY}, function(error, infos) {
					if (!error) {
						for (i in infos) {
							durations[infos[i].id] = infos[i].contentDetails.duration;
						}

						this.socket.emit("onSearchMoreResponse", results, durations, tokens);
					}
				}.bind({socket: this.socket}));
			}
		}.bind({socket: this.socket}));
	}

	this.getSocketName = function(socket) {
		var Lobby = this.getLobbyFromSocket(socket);
		for (i in Lobby.Room.users) {
			var user = Lobby.Room.users[i];
			if (user.socket == socket) {
				return user.name;
			}
		}

		return "Unknown";
	}

	this.onRequestPlaybackRate = function(rate) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		if (Lobby != false) {
			var Room = Lobby.Room;
			for (i in Room.users) {
				var user = Room.users[i];
				user.socket.emit("setPlaybackRate", rate);
			}
		}
	}

	this.onRequestSeek = function(seconds) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		if (Lobby != false) {
			var Room = Lobby.Room;
			for (i in Room.users) {
				var user = Room.users[i];
				user.socket.emit("setVideoPosition", seconds);
			}
		}
	}


	this.onUserMessage = function(data) {
		var socket = this.socket;
		var Lobby = this.handler.getLobbyFromSocket(socket);
		if (Lobby != false) {
			var Room = Lobby.Room;
			if (data.message.length <= settings.CHAT_MESSAGE_MAX_LENGTH + 10000) {
				for (i in Room.users) {
					var user = Room.users[i];
					user.socket.emit("onRecieveMessage", {
						message: data.message,
						userid: socket.UserID,
						time: Date.now(),
						name: this.handler.getSocketName(socket)
					});
				}
			}
		}
	}

	this.onRequestVideoData = function(socket) {
		var Lobby = this.Lobbys[socket.Lobby];
		var Room = Lobby.Room;

		var ActiveSocket = false;

		for (i in Room.users) {
			var user = Room.users[i];

			if (user.socket != socket && user.socket.ready == true) {
				ActiveSocket = user.socket;
				break;
			}
		}

		if (ActiveSocket === false) {
			socket.ready = true;
			socket.emit("sendVideoData", {
				position: 0,
				playing: false,
			});
		} else {
			ActiveSocket.requestSocket = socket;
			ActiveSocket.emit("requestVideoData");
		}

	}

	this.IO.on("connection", this.onSocket.bind({handler: this}));

	return this;
}

module.exports = LobbyHandler;