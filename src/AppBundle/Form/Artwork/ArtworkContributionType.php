<?php

namespace AppBundle\Form\Artwork;

use AppBundle\Entity\Artwork;
use AppBundle\Entity\ArtworkContribution;
use AppBundle\Transformer\HiddenEntityTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtworkContributionType extends AbstractType {

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $artwork = $options['artwork'];
        $builder->add('artwork', HiddenType::class, array(
            'data' => $artwork,
            'data_class' => null,
        ));
        $builder->add('person');
        $builder->add('organization');
        $builder->add('artworkRole');

        $builder->get('artwork')->addModelTransformer(new HiddenEntityTransformer($this->em, Artwork::class));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => ArtworkContribution::class,
            'artwork' => null,
        ));
    }

}
