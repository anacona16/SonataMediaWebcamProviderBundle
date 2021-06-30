<?php

namespace Anacona16\Bundle\SonataMediaWebcamProviderBundle\SonataMedia\Provider;

use Gaufrette\Filesystem;
use Imagine\Image\ImagineInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\ImageProvider;
use Sonata\MediaBundle\Provider\Metadata;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Twig\Environment;

class WebcamProvider extends ImageProvider
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions, array $allowedMimeTypes, ImagineInterface $adapter, MetadataBuilderInterface $metadata = null)
    {
        $name = 'sonata.media.provider.webcam';

        $allowedMimeTypes[] = 'text/plain';

        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $allowedExtensions, $allowedMimeTypes, $adapter, $metadata);
    }

    /**
     * @param Environment $twig
     */
    public function setTwig(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderMetadata()
    {
        return new Metadata($this->getName(), $this->getName().'.description', false, 'SonataMediaWebcamProviderBundle', array('class' => 'fa fa-camera'));
    }

    /**
     * {@inheritdoc}
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $templatePath = 'SonataMediaWebcamProviderBundle::webcam.html.twig';

        if (Kernel::VERSION >= 3.4) {
            $templatePath = '@SonataMediaWebcamProvider/webcam.html.twig';
        }

        $formMapper->add('binaryContent', TextType::class, array(
            'constraints' => array(
                new NotBlank(),
                new NotNull(),
            ),
            'label' => false,
            'help' => $this->twig->render($templatePath),
            'help_html' => true,
            'attr' => array(
                'class' => 'anacona16-sonata-media-webcam-provider-binary-content',
                'style' => 'display: none; visibility: hidden;',
            ),
        ));
    }

    /**
     * Convert base64 content into an image file.
     *
     * @param MediaInterface $media
     */
    protected function doTransform(MediaInterface $media)
    {
        $content = $media->getBinaryContent();
        
        // We use the standard image provider to avoid errors or lost some process.
        $media->setProviderName('sonata.media.provider.image');

        if (!$content instanceof UploadedFile) {
            $fileName = $this->generateMediaUniqId($media).'.png';
            $filePath = sys_get_temp_dir();
            $fileFullPath = $filePath.'/'.$fileName;

            try {
                $fs = new SymfonyFilesystem();
                $fs->dumpFile($fileFullPath, base64_decode($content));

                $media->setBinaryContent($fileFullPath);
            } catch (\RuntimeException $e) {
                $media->setProviderStatus(MediaInterface::STATUS_ERROR);

                return;
            }
        }

        parent::doTransform($media);
    }
}
