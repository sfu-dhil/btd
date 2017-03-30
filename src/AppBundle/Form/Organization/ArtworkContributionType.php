<?php

/*
 * To change this license header, choose License Headers in Artwork Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Organization;

use AppBundle\Entity\Organization;
use AppBundle\Entity\ArtworkContribution;
use AppBundle\Transformer\HiddenEntityTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ArtworkContribution
 *
 * @author michael
 */
class ArtworkContributionType extends AbstractType {

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $organization = $options['organization'];
        $builder->add('organization', HiddenType::class, array(
            'data' => $organization,
            'data_class' => null,
        ));
        $builder->add('artworkRole');
        $builder->add('artwork');
        
        $builder->get('organization')->addModelTransformer(new HiddenEntityTransformer($this->em, Organization::class));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => ArtworkContribution::class,
            'organization' => null,
        ));
    }

}
