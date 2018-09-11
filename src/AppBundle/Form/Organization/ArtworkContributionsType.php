<?php

/*
 * To change this license header, choose License Headers in Artwork Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Organization;

use AppBundle\Entity\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtworkContributionsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $organization = $options['organization'];
        $builder->add('artworkContributions', CollectionType::class, array(
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => ArtworkContributionType::class,
            'entry_options' => array(
                'organization' => $organization,
            ),
            'label' => 'Contribution',
            'attr' => array(
                'class' => 'collection-simple',
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Organization::class,
            'organization' => null,
        ));
    }

}
