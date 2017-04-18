<?php

namespace Anacona16\Bundle\SonataMediaWebcamProviderBundle\SonataMedia\Provider;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\ImageProvider;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class WebcamProvider extends ImageProvider
{
    /**
     * @var Container
     */
    private $container;

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

        $fs = new Filesystem();
        $fs->dumpFile($fileFullPath, $content);

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
