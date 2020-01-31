<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form\Artwork;

use App\Entity\Artwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ArtworkContributions.
 *
 * @author michael
 */
class ArtworkContributionsType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $artwork = $options['artwork'];
        $builder->add('contributions', CollectionType::class, array(
            'entry_type' => ArtworkContributionType::class,
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_options' => array(
                'label' => false,
                'artwork' => $artwork,
            ),
            'by_reference' => false,
            'label' => 'Contribution',
            'attr' => array(
                'class' => 'collection-simple',
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
