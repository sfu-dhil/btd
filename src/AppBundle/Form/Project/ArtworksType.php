<?php

/*
 * To change this license header, choose License Headers in Artwork Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Project;

use AppBundle\Entity\Artwork;
use AppBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtworksType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('artworks', EntityType::class, array(
            'expanded' => true,
            'multiple' => true,
            'class' => Artwork::class,
            'required' => false,
        ));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Project::class,
            'project' => null,
        ));
    }
    
}
