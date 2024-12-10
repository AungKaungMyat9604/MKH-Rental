<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coze Chatbot Integration</title>
    <style>
        .close-chat-btn {
            background-color: #ff6666;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1001; /* Ensure it is above the chat window */
            display: none; /* Hide the close button initially */
        }

        .close-chat-btn:hover {
            background-color: #ff3333;
        }

        .open-chat-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1001;
        }

        .open-chat-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<!-- Start of Async Drift Code -->
<script>
"use strict";

!function() {
  var t = window.driftt = window.drift = window.driftt || [];
  if (!t.init) {
    if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));
    t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ], 
    t.factory = function(e) {
      return function() {
        var n = Array.prototype.slice.call(arguments);
        return n.unshift(e), t.push(n), t;
      };
    }, t.methods.forEach(function(e) {
      t[e] = t.factory(e);
    }), t.load = function(t) {
      var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");
      o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
      var i = document.getElementsByTagName("script")[0];
      i.parentNode.insertBefore(o, i);
    };
  }
}();
drift.SNIPPET_VERSION = '0.3.1';
drift.load('b2d2dc9xywc2');
</script>
<!-- Start of Async Drift Code -->
<script>
"use strict";

!function() {
  var t = window.driftt = window.drift = window.driftt || [];
  if (!t.init) {
    if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));
    t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ], 
    t.factory = function(e) {
      return function() {
        var n = Array.prototype.slice.call(arguments);
        return n.unshift(e), t.push(n), t;
      };
    }, t.methods.forEach(function(e) {
      t[e] = t.factory(e);
    }), t.load = function(t) {
      var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");
      o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
      var i = document.getElementsByTagName("script")[0];
      i.parentNode.insertBefore(o, i);
    };
  }
}();
drift.SNIPPET_VERSION = '0.3.1';
drift.load('dmsk7dnwwns5');
</script>
<!-- End of Async Drift Code -->



<!-- Start of Async Drift Code -->
<script>
"use strict";

!function() {
  var t = window.driftt = window.drift = window.driftt || [];
  if (!t.init) {
    if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));
    t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ], 
    t.factory = function(e) {
      return function() {
        var n = Array.prototype.slice.call(arguments);
        return n.unshift(e), t.push(n), t;
      };
    }, t.methods.forEach(function(e) {
      t[e] = t.factory(e);
    }), t.load = function(t) {
      var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");
      o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
      var i = document.getElementsByTagName("script")[0];
      i.parentNode.insertBefore(o, i);
    };
  }
}();
drift.SNIPPET_VERSION = '0.3.1';
drift.load('dmsk7dnwwns5');
</script>
<!-- End of Async Drift Code -->




    <!-- Button to open the chat -->
    <button id="toggleChatBtn" class="open-chat-btn">Open Chat</button>

    <!-- Button to close the chat (initially hidden) -->
    <button id="closeChatBtn" class="close-chat-btn">Close Chat</button>

    <script src="https://sf-cdn.coze.com/obj/unpkg-va/flow-platform/chat-app-sdk/0.1.0-beta.5/libs/oversea/index.js"></script>
    <script>
        let cozeChat;

        function initializeCozeChat() {
            cozeChat = new CozeWebSDK.WebChatClient({
                config: {
                    bot_id: '7406784541920526343', // Replace with your bot ID
                },
                componentProps: {
                    title: 'Coze',
                },
            });

            document.getElementById('toggleChatBtn').style.display = 'none';
            document.getElementById('closeChatBtn').style.display = 'block';
        }

        function closeCozeChat() {
            if (cozeChat && cozeChat.hide) {
                cozeChat.hide(); // Hide the chat window
                document.getElementById('toggleChatBtn').style.display = 'block';
                document.getElementById('closeChatBtn').style.display = 'none';
            }
        }

        document.getElementById('toggleChatBtn').addEventListener('click', () => {
            initializeCozeChat();
        });

        document.getElementById('closeChatBtn').addEventListener('click', () => {
            closeCozeChat();
        });
    </script>

</body>
</html>
