SonataMediaWebcamProvider
=========================

SonataMediaWebcamProvider add a new provider to SonataMediaBundle, it lets you capture a image from your webcam,
this bundle use [ScriptCam](http://www.scriptcam.com/)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5f212567-9f44-41b2-9e1f-115544c5f0e7/mini.png)](https://insight.sensiolabs.com/projects/5f212567-9f44-41b2-9e1f-115544c5f0e7)

**Features**

  * Integration with the default ImageProvider.

**Requirements**

  * Symfony 2.3+ applications.
  * SonataMediaBundle.
  
Please install each bundle following their instructions.
  
**How it works?**

This bundle replaces the binaryContent field, adds the ScriptCam script and fill the new binaryContent field.
ScriptCam image using base64 encoding, then a temporary file is created, after ImageProvider uses and processes.

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

```html
<script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
<script type="text/javascript" src="{{ asset('bundles/sonatamediawebcamprovider/scriptcam/scriptcam.min.js') }}"></script>
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

Configure SonataMediaWebcamProviderBundle (optional)
----------------------------------------------------

You can change the width and height camera:

```yaml
# app/config/config.yml
sonata_media_webcam_provider:
    width: 640 # default: 320
    height: 480 # default: 240
```

That's all, now you can capture a image from your webcam using SonataMediaBundle.

-----

License
-------

This bundle is published under the [MIT License](LICENSE)
