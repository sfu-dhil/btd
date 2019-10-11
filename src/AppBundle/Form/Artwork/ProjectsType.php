<?php

/*
 * To change this license header, choose License Headers in Artwork Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Artwork;

use AppBundle\Entity\Artwork;
use AppBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectsType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('projects', EntityType::class, array(
            'expanded' => true,
            'multiple' => true,
            'class' => Project::class,
            'required' => false,
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Artwork::class,
        ));
    }
}
