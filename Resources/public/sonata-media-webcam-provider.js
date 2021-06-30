(function() {
    // The width and height of the captured photo. We will set the
    // width to the value defined here, but the height will be
    // calculated based on the aspect ratio of the input stream.

    var anacona16WebCamProviderWidth = 720;    // We will scale the photo width to this
    var anacona16WebCamProviderHeight = 0;     // This will be computed based on the input stream

    // |streaming| indicates whether or not we're currently streaming
    // video from the camera. Obviously, we start at false.
    var anacona16WebCamProviderStreaming = false;

    // The various HTML elements we need to configure or control. These
    // will be set by the anacona16SonataWebcamProviderStartUp() function.
    var anacona16WebCamProviderVideo = null;
    var anacona16WebCamProviderCanvas = null;
    var anacona16WebCamProviderPhoto = null;
    var anacona16WebCamProviderStartButton = null;
    var anacona16WebCamProviderBinaryContentField = null

    function anacona16SonataWebcamProviderStartUp() {
        anacona16WebCamProviderVideo = document.getElementById('anacona16-sonata-media-webcam-camera');
        anacona16WebCamProviderCanvas = document.getElementById('anacona16-sonata-media-webcam-camera-canvas');
        anacona16WebCamProviderPhoto = document.getElementById('anacona16-sonata-media-webcam-snapshots-photo');
        anacona16WebCamProviderStartButton = document.getElementById('anacona16-sonata-media-webcam-take-snapshots');
        anacona16WebCamProviderBinaryContentField = document.querySelector('.anacona16-sonata-media-webcam-provider-binary-content');

        if (null === anacona16WebCamProviderVideo) {
            return;
        }

        navigator.mediaDevices.getUserMedia({video: true, audio: false})
            .then(function(stream) {
                anacona16WebCamProviderVideo.srcObject = stream;
                anacona16WebCamProviderVideo.play();
            })
            .catch(function(err) {
                console.log("An error occurred: " + err);
            });

        anacona16WebCamProviderVideo.addEventListener('canplay', function(ev){
            if (!anacona16WebCamProviderStreaming) {
                anacona16WebCamProviderHeight = anacona16WebCamProviderVideo.videoHeight / (anacona16WebCamProviderVideo.videoWidth/anacona16WebCamProviderWidth);

                // Firefox currently has a bug where the height can't be read from
                // the video, so we will make assumptions if this happens.

                if (isNaN(anacona16WebCamProviderHeight)) {
                    anacona16WebCamProviderHeight = anacona16WebCamProviderWidth / (4/3);
                }

                anacona16WebCamProviderVideo.setAttribute('width', anacona16WebCamProviderWidth);
                anacona16WebCamProviderVideo.setAttribute('height', anacona16WebCamProviderHeight);
                anacona16WebCamProviderCanvas.setAttribute('width', anacona16WebCamProviderWidth);
                anacona16WebCamProviderCanvas.setAttribute('height', anacona16WebCamProviderHeight);
                anacona16WebCamProviderStreaming = true;
            }
        }, false);

        anacona16WebCamProviderStartButton.addEventListener('click', function(ev){
            anacona16SonataWebcamProviderTakePicture();
            ev.preventDefault();
        }, false);

        anacona16SonataWebcamProviderClearPhoto();
    }

    // Fill the photo with an indication that none has been
    // captured.
    function anacona16SonataWebcamProviderClearPhoto() {
        var context = anacona16WebCamProviderCanvas.getContext('2d');
        context.fillStyle = "#AAA";
        context.fillRect(0, 0, anacona16WebCamProviderCanvas.width, anacona16WebCamProviderCanvas.height);

        var data = anacona16WebCamProviderCanvas.toDataURL('image/png');
        anacona16WebCamProviderPhoto.setAttribute('src', data);
    }

    // Capture a photo by fetching the current contents of the video
    // and drawing it into a canvas, then converting that to a PNG
    // format data URL. By drawing it on an offscreen canvas and then
    // drawing that to the screen, we can change its size and/or apply
    // other changes before drawing it.
    function anacona16SonataWebcamProviderTakePicture() {
        var context = anacona16WebCamProviderCanvas.getContext('2d');
        if (anacona16WebCamProviderWidth && anacona16WebCamProviderHeight) {
            anacona16WebCamProviderCanvas.width = anacona16WebCamProviderWidth;
            anacona16WebCamProviderCanvas.height = anacona16WebCamProviderHeight;
            context.drawImage(anacona16WebCamProviderVideo, 0, 0, anacona16WebCamProviderWidth, anacona16WebCamProviderHeight);

            var data = anacona16WebCamProviderCanvas.toDataURL('image/png');
            anacona16WebCamProviderPhoto.setAttribute('src', data);
            anacona16WebCamProviderBinaryContentField.setAttribute('value', data.split(',')[1]);
        } else {
            anacona16SonataWebcamProviderClearPhoto();
        }
    }

    // Set up our event listener to run the anacona16SonataWebcamProviderStartUp process
    // once loading is complete.
    window.addEventListener('load', anacona16SonataWebcamProviderStartUp, false);
})();
