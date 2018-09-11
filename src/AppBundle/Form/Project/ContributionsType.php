<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Project;

use AppBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContributionsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $project = $options['project'];
        $builder->add('contributions', CollectionType::class, array(
            'entry_type' => ContributionType::class,
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_options' => array(
                'project' => $project,
                'label' => false,
            ),
            'by_reference' => false,
            'label' => 'Contribution',
            'attr' => array(
                'class' => 'collection-simple',
            ),
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Project::class,
            'project' => null,
        ));
    }

}
