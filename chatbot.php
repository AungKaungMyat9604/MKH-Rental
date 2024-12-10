<?php if (isset($_SESSION['user_id'])){
    ?><script src="https://sf-cdn.coze.com/obj/unpkg-va/flow-platform/chat-app-sdk/0.1.0-beta.5/libs/oversea/index.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new CozeWebSDK.WebChatClient({
        config: {
            bot_id: '7406784541920526343',
        },
        componentProps: {
            title: 'Chat Bot'
        },
    });
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var zoomLevels = [0.8, 1.0, 1.2];
    var currentZoomIndex = 1; // Start at 1.0 (default zoom level)

    var zoomControls = document.getElementById("zoom-controls");
    var accessibilityIcon = document.getElementById("accessibility-icon");

    accessibilityIcon.addEventListener("click", function () {
        if (zoomControls.style.display === "block") {
            zoomControls.style.display = "none";
        } else {
            zoomControls.style.display = "block";
        }
    });

    document.getElementById("zoom-in").addEventListener("click", function () {
        if (currentZoomIndex < zoomLevels.length - 1) {
            currentZoomIndex++;
            document.body.style.zoom = zoomLevels[currentZoomIndex];
        }
    });

    document.getElementById("zoom-out").addEventListener("click", function () {
        if (currentZoomIndex > 0) {
            currentZoomIndex--;
            document.body.style.zoom = zoomLevels[currentZoomIndex];
        }
    });
});


    </script>
    <?php
    }
    ?>