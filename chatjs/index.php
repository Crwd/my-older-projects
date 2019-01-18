<!doctype html>
<html>
  <head>
    <?php
      session_start();
      $_SESSION['name'] = "Niko";
    ?>

    <title>Livechat</title>
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font: 13px Helvetica, Arial; }
      form { background: #000; padding: 3px; position: fixed; bottom: 0; width: 100%; }
      form input { border: 0; padding: 10px; width: 90%; margin-right: .5%; }
      form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
      #messages { list-style-type: none; margin: 0; padding: 0; }
      #messages li { padding: 5px 10px; }
      #messages li:nth-child(odd) { background: #eee; }
    </style>
  </head>
  <body>
    <ul id="messages"></ul>
    <form action="">
      <input id="m" autocomplete="off" /><button>Send</button><button id="exit">Exit</button>
    </form>
  </body>
  <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
  <script src="http://127.0.0.1:8080/socket.io/socket.io.js"></script>
  <script>

    try {
      var socket = io.connect('http://localhost:8080');

      $('#exit').click(function() {
        console.log("test");
        socket.disconnect();
      });

      $('form').submit(function(){
        var data = {"name" : <?php echo json_encode($_SESSION['name']); ?>, "msg" : $('#m').val()}
        socket.emit('chat message', data);
        $('#m').val('');
        return false;
      });

      socket.on('listContent', function(data) {
        for(m in data) {
          $('#messages').append($('<li>').text(data[m].name + ": " + data[m].msg));
        }
      });

      socket.on('chat message', function(data){
        $('#messages').append($('<li>').text(data.name + ": " + data.msg));
      });
    } catch(e) {

    }
  </script>
</html>
