<!DOCTYPE html>
<html>
  <head>
    <title>Talking with Pusher</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container">
      <div class="content">
        <h1>Laravel 5 and Pusher is fun!</h1>
        <ul id="messages" class="list-group">
        </ul>
      </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
    <script>
      //instantiate a Pusher object with our Credential's key
      var pusher = new Pusher('bff3b61398ba1f1dc893', {
          cluster: 'ap2',
          encrypted: true
      });

      //Subscribe to the channel we specified in our Laravel Event
      var channel = pusher.subscribe('my-channel');

      //Bind a function to a Event (the full Laravel class)
      channel.bind('App\\Events\\HelloPusherEvent', addMessage);

      function addMessage(data) {
        console.log('sdgsdgv');
        var listItem = $("<li class='list-group-item'></li>");
        listItem.html(data.message);
        alert(data.message);
        $('#messages').prepend(listItem);
      }
    </script>
  </body>
</html>