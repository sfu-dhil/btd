<?php

namespace AppBundle\Form\Project;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title');
        $builder->add('startDate', DateType::class, array(
            'widget' => 'single_text',
        ));
        $builder->add('endDate', DateType::class, array(
            'widget' => 'single_text',
        ));
        $builder->add('venues');
        $builder->add('excerpt', CKEditorType::class, array(
            'attr' => array(
                'help_block' => 'Excerpts will be shown on the home page and in '
                . 'lists of pages. Leave this field blank and one will be '
                . 'generated automatically.'
            ),
        ));
        $builder->add('description', CKEditorType::class);
        $builder->add('url');
        $builder->add('projectCategory');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project'
        ));
    }

}
