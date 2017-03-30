<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Artwork;

use AppBundle\Entity\Artwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ArtworkContributions
 *
 * @author michael
 */
class ArtworkContributionsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $artwork = $options['artwork'];        
        $builder->add('contributions', CollectionType::class, array(
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => ArtworkContributionType::class,
            'entry_options' => array(
                'artwork' => $artwork,
            ),
            'label' => 'Contribution',
            'attr' => array(
                'group_class' => 'collection',
            ),
        ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Artwork::class,
            'artwork' => null,
        ));
    }
    
}    