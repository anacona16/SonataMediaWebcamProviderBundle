<?php

namespace Anacona16\Bundle\SonataMediaWebcamProviderBundle\SonataMedia\Provider;

use Gaufrette\Filesystem;
use Imagine\Image\ImagineInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\MediaBundle\CDN\CDNInterface;
use Sonata\MediaBundle\Generator\GeneratorInterface;
use Sonata\MediaBundle\Metadata\MetadataBuilderInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\ImageProvider;
use Sonata\MediaBundle\Thumbnail\ThumbnailInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class WebcamProvider extends ImageProvider
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions, array $allowedMimeTypes, ImagineInterface $adapter, MetadataBuilderInterface $metadata = null)
    {
        $name = 'sonata.media.provider.webcam';
        $allowedMimeTypes[] = 'text/plain';
        $allowedMimeTypes[] = 'application/octet-stream';

        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $allowedExtensions, $allowedMimeTypes, $adapter, $metadata);
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
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
        $formMapper->add('binaryContent', TextareaType::class, array(
            'constraints' => array(
                new NotBlank(),
                new NotNull(),
            ),
            'label' => false,
            'help' => $this->container->get('twig')->render('SonataMediaWebcamProviderBundle::webcam.html.twig'),
            'attr' => array(
                'class' => 'anacona16-sonata-media-webcam-provider',
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

        if (!$content instanceof UploadedFile) {
            $fileName = $this->generateMediaUniqId($media).'.jpg';
            $filePath = sys_get_temp_dir();
            $fileFullPath = $filePath.'/'.$fileName;

            try {
                $fs = new SymfonyFilesystem();
                $fs->dumpFile($fileFullPath, base64_decode(substr($content, 23)));

                $media->setBinaryContent($fileFullPath);
            } catch (\RuntimeException $e) {
                $media->setProviderStatus(MediaInterface::STATUS_ERROR);

                return;
            }
        }

        return parent::doTransform($media);
    }
}
