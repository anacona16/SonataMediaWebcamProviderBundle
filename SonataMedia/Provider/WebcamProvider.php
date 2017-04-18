<?php

namespace Anacona16\Bundle\SonataMediaWebcamProviderBundle\SonataMedia\Provider;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\ImageProvider;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class WebcamProvider extends ImageProvider
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    private $bundleConfiguration;

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @param array $bundleConfiguration
     */
    public function setBundleConfiguration($bundleConfiguration)
    {
        $this->bundleConfiguration = $bundleConfiguration;
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

        $path = tempnam(sys_get_temp_dir(), $this->generateMediaUniqId($media)).'.jpg';
        $fileObject = new \SplFileObject($path, 'w');
        $fileObject->fwrite(base64_decode($content));

        $media->setBinaryContent($path);

        parent::fixBinaryContent($media);
    }

    /**
     * @param FormMapper $formMapper
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $formMapper
            ->add('binaryContent', 'textarea', array(
                'constraints' => array(
                    new NotBlank(),
                    new NotNull(),
                ),
                'label' => false,
                'help' => $this->container->get('twig')->render('SonataMediaWebcamProviderBundle::webcam.html.twig', array('config' => $this->bundleConfiguration)),
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
