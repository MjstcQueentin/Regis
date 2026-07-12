var videoPlayer;

function showVideoPlayer() {
    if (typeof videoPlayer == 'undefined' || videoPlayer?.closed) {
        videoPlayer = window.open("videoplayer.php", "_blank", "height=500,width=500");
    } else {
        videoPlayer.focus();
    }
}

function setVideo(newSrc, onLoop = false) {
    var videoElement = videoPlayer.document.getElementById("videoElement");
    if (newSrc) {
        videoElement.setAttribute("src", newSrc);
        videoElement.classList.remove("d-none");
        videoElement.play();

        if (typeof videoElement.loop == 'boolean') {
            videoElement.loop = onLoop ? true : false;
        }
    } else {
        videoElement.pause();
        videoElement.classList.add("d-none");
    }
}

function stopVideo() {
    var videoElement = videoPlayer.document.getElementById("videoElement");
    videoElement.pause();
    videoElement.classList.add("d-none");
}

var showVideoPlayerBtns = document.querySelectorAll('button[data-trigger*="video-show-player"]');
for (let i = 0; i < showVideoPlayerBtns.length; i++) {
    showVideoPlayerBtns.item(i).addEventListener("click", e => showVideoPlayer());
}

var playVideoBtns = document.querySelectorAll('button[data-trigger*="video-play"]');
for (let i = 0; i < playVideoBtns.length; i++) {
    playVideoBtns.item(i).addEventListener("click", e => setVideo(playVideoBtns.item(i).getAttribute("data-video"), playVideoBtns.item(i).getAttribute("data-loop")));
}

var stopVideoBtns = document.querySelectorAll('button[data-trigger*="video-stop"]');
for (let i = 0; i < stopVideoBtns.length; i++) {
    stopVideoBtns.item(i).addEventListener("click", e => stopVideo());
}

// Ouverture automatique du player quand le tag autoshow-video-player est présent sur le body
document.addEventListener("DOMContentLoaded", function (e) {
    if (document.body.hasAttribute("autoshow-video-player")) {
        showVideoPlayer();
    }
});

// Fermer le video player quand on quitte la page
window.addEventListener("beforeunload", function () {
    videoPlayer?.close();
});