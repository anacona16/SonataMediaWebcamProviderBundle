function anacona16SonataMediaWebcamProvider() {
    if (window.JpegCamera) {
        var camera; // Initialized at the end
        var $camera = $('#anacona16-sonata-media-webcam-camera');

        var take_snapshots = function (count) {
            var snapshot = camera.capture();

            if (JpegCamera.canvas_supported()) {
                snapshot.get_canvas(add_snapshot);
            }
            else {
                // <canvas> is not supported in this browser. We'll use anonymous
                // graphic instead.
                var image = document.createElement('img');
                image.src = $camera.data('no-canvas-photo');

                setTimeout(function () {
                    add_snapshot.call(snapshot, image)
                }, 1);
            }

            if (count > 1) {
                setTimeout(function () {
                    take_snapshots(count - 1);
                }, 500);
            }
        };

        var add_snapshot = function (element) {
            $(element).data('snapshot', this).addClass('anacona16-sonata-media-webcam-item');

            var $container = $('#anacona16-sonata-media-webcam-snapshots').append(element);
            var camera_ratio = $camera.innerWidth() / $camera.innerHeight();

            var height = $container.height();
            element.style.height = '' + height + 'px';
            element.style.width = '' + Math.round(camera_ratio * height) + 'px';

            var scroll = $container[0].scrollWidth - $container.innerWidth();

            $container.animate({
                scrollLeft: scroll
            }, 200);
        };

        var select_snapshot = function () {
            $('.anacona16-sonata-media-webcam-item').removeClass('anacona16-sonata-media-webcam-selected');
            var snapshot = $(this).addClass('anacona16-sonata-media-webcam-selected').data('snapshot');
            snapshot.show();
            $('#anacona16-sonata-media-webcam-show-stream').show();

            snapshot.get_canvas(function (canvas) {
                $('.anacona16-sonata-media-webcam-provider').val(canvas.toDataURL('image/jpeg').split(',')[1]);
            });
        };

        var show_stream = function () {
            $(this).hide();
            $('.anacona16-sonata-media-webcam-item').removeClass('anacona16-sonata-media-webcam-selected');
            hide_snapshot_controls();
            camera.show_stream();
        };

        var hide_snapshot_controls = function () {
            $('#anacona16-sonata-media-webcam-show-stream').hide();
        };

        $('#anacona16-sonata-media-webcam-take-snapshots').click(function () {
            take_snapshots(1);
        });

        $('#anacona16-sonata-media-webcam-snapshots').on('click', '.anacona16-sonata-media-webcam-item', select_snapshot);
        $('#anacona16-sonata-media-webcam-show-stream').click(show_stream);

        var options = {
            shutter_ogg_url: $camera.data('shutter-ogg-url'),
            shutter_mp3_url: $camera.data('shutter-mp3-url'),
            swf_url: $camera.data('shutter-jpeg-camera')
        };

        camera = new JpegCamera('#anacona16-sonata-media-webcam-camera', options).ready(function (info) {
            $('#anacona16-sonata-media-webcam-take-snapshots').show();
        });
    }
}
