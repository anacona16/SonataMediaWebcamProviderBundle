// Put event listeners into place
window.addEventListener("DOMContentLoaded", function () {
    // Grab elements, create settings, etc.
    var canvas = document.getElementById('anacona16-sonata-media-webcam-canvas');
    var context = canvas.getContext('2d');
    var video = document.getElementById('anacona16-sonata-media-webcam-video');
    var mediaConfig = {video: true};

    var errBack = function (e) {
        console.log('An error has occurred!', e)
    };

    // Put video listeners into place
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia(mediaConfig).then(function (stream) {
            video.src = window.URL.createObjectURL(stream);
            video.play();
        });
    }

    /* Legacy code below! */
    else if (navigator.getUserMedia) { // Standard
        navigator.getUserMedia(mediaConfig, function (stream) {
            video.src = stream;
            video.play();
        }, errBack);
    } else if (navigator.webkitGetUserMedia) { // WebKit-prefixed
        navigator.webkitGetUserMedia(mediaConfig, function (stream) {
            video.src = window.webkitURL.createObjectURL(stream);
            video.play();
        }, errBack);
    } else if (navigator.mozGetUserMedia) { // Mozilla-prefixed
        navigator.mozGetUserMedia(mediaConfig, function (stream) {
            video.src = window.URL.createObjectURL(stream);
            video.play();
        }, errBack);
    }

    // Trigger photo take
    document.getElementById('anacona16-sonata-media-webcam-snap').addEventListener('click', function () {
        context.drawImage(video, 0, 0, 480, 360);
        video.style.display = "none";
        canvas.style.display = "block";

        document.getElementsByClassName('anacona16-sonata-media-webcam-provider')[0].value = canvas.toDataURL("image/jpeg");
    });

    // Trigger photo take
    document.getElementById('anacona16-sonata-media-webcam-reset').addEventListener('click', function () {
        video.style.display = "block";
        canvas.style.display = "none";
    });
}, false);
