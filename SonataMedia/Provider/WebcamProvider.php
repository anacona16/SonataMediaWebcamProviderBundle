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
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class WebcamProvider extends ImageProvider
{
    /**
     * @var Container
     */
    private $container;

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
     * @param Container $container
     */
    public function setContainer($container)
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
    protected function fixBinaryContent(MediaInterface $media)
    {
        $content = $media->getBinaryContent();

        $fileName = $this->generateMediaUniqId($media).'.jpg';
        $filePath = sys_get_temp_dir();
        $fileFullPath = $filePath.'/'.$fileName;

        $fs = new SymfonyFilesystem();
        $fs->dumpFile($fileFullPath, base64_decode($content));

        $media->setBinaryContent($fileFullPath);

        parent::fixBinaryContent($media);
    }

    /**
     * @param FormMapper $formMapper
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $bundleConfiguration = $this->container->getParameter('sonata_media_webcam_provider');

        $formMapper
            ->add('binaryContent', 'textarea', array(
                'constraints' => array(
                    new NotBlank(),
                    new NotNull(),
                ),
                'label' => false,
                'help' => $this->container->get('twig')->render('SonataMediaWebcamProviderBundle::webcam.html.twig', array(
                    'config' => $bundleConfiguration,
                )),
                'attr' => array(
                    'class' => 'anacona16-sonata-media-webcam-provider',
                    'style' => 'display: none; visibility: hidden;',
                ),
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    public function buildEditForm(FormMapper $formMapper)
    {
        parent::buildEditForm($formMapper);

        $formMapper->remove('binaryContent');
    }
}
