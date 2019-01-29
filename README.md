SonataMediaWebcamProvider
=========================

SonataMediaWebcamProvider add a new provider to SonataMediaBundle, it lets you capture a image from your webcam,
this bundle use [JpegCamera](https://github.com/amw/jpeg_camera) library.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5f212567-9f44-41b2-9e1f-115544c5f0e7/mini.png)](https://insight.sensiolabs.com/projects/5f212567-9f44-41b2-9e1f-115544c5f0e7)

**Features**

  * Integration with the default ImageProvider.

**Requirements**

  * Symfony 2.8+ applications.
  * SonataMediaBundle.
  
Please install each bundle following their instructions.
  
**How it works?**

This bundle replaces the binaryContent field.
Video HTML5 element uses canvas element to get an image using base64 encoding, then a temporary file is created, after, ImageProvider uses and processes it.

Installation
------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest version of this bundle:

```bash
$ composer require anacona16/sonata-media-webcam-provider-bundle
```

This command requires you to have Composer installed globally, as explained
in the [Composer documentation](https://getcomposer.org/doc/00-intro.md).

### Step 2: Enable the Bundle

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your Symfony application:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Anacona16\Bundle\SonataMediaWebcamProviderBundle\SonataMediaWebcamProviderBundle(),
        );
    }

    // ...
}
```

### Step 3: Prepare the Web Assets of the Bundle

This bundle includes the ScriptCam JavaScript library. Execute the following
command to make those assets available in your Symfony application:

```cli
php app/console assets:install --symlink
```

### Step 4: Add assets to your layout

You need add the assets in your layout e.g. SonataAdminBundle::standard_layout.html.twig

**JS**

```html
<script src="{{ asset('bundles/sonatamediawebcamprovider/jpeg_camera/swfobject.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('bundles/sonatamediawebcamprovider/jpeg_camera/canvas-to-blob.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('bundles/sonatamediawebcamprovider/jpeg_camera/jpeg_camera.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('bundles/sonatamediawebcamprovider/sonata-media-webcam-provider.min.js') }}" type="text/javascript"></script>
```

SWFObject and Canvas-to-Blob are stored in separate files so that you don't have to load 
them again if you already use them in your project. If you want to cut down on HTTP requests 
then there is a concatenated version you can use.

```html
<script src="{{ asset('bundles/sonatamediawebcamprovider/jpeg_camera/jpeg_camera_with_dependencies.min.js') }}" type="text/javascript"></script>
```

If you want to use HTML5-only version you can load `jpeg_camera_no_flash.min.js`. There is no "with dependencies" version of this file, so you have to remember to also load Canvas-to-Blob. You don't need SWFObject for HTML5.

**CSS**

```html
<link rel="stylesheet" href="{{ asset('bundles/sonatamediawebcamprovider/sonata-media-webcam-provider.min.css') }}">
```

That's it! Now everything is ready to use the Webcam provider.

Using SonataMediaWebcamProviderBundle
-------------------------------------

After you configure the SonataMediaBundle correctly you must add this lines to your SonataMediaBundle configuration file:

```yaml
sonata_media:
    contexts:
        default:
            providers:
                # ...
                - sonata.media.provider.webcam
```

That's all, now you can capture an image from your webcam using SonataMediaBundle.

-----

License
-------

This bundle is published under the [MIT License](LICENSE)
