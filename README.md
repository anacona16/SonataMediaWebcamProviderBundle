SonataMediaWebcamProvider
=========================

SonataMediaWebcamProvider add a new provider to SonataMediaBundle, it lets you capture an image from your webcam.

**Requirements**

  * Symfony 3.4+ applications.
  * SonataMediaBundle.
  
Please install each bundle following their instructions.
  
**How it works?**

This bundle replaces the binaryContent field.
Video HTML5 element uses canvas element to get an image using base64 encoding, then a temporary file is created,
the ImageProvider uses and processes it.

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

If you are using Symfony Flex the bundle will be enabled automatically, if not enable the bundle by adding the 
following line in the `app/AppKernel.php` file of your Symfony application:

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

Execute the following command to make assets available in your Symfony application:

```cli
php app/console assets:install --symlink
```

### Step 4: Add assets to your layout

You need add the assets in your layout e.g. SonataAdminBundle::standard_layout.html.twig

**JS**

```html
<script src="{{ asset('bundles/sonatamediawebcamprovider/sonata-media-webcam-provider.min.js') }}" type="text/javascript"></script>
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
