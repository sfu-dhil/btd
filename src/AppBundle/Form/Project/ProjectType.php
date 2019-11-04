<?php

namespace AppBundle\Form\Project;

use AppBundle\Entity\Project;
use AppBundle\Entity\Venue;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ProjectType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title');

        $builder->add('parent', Select2EntityType::class, array(
            'remote_route' => 'project_typeahead',
            'class' => Project::class,
            'multiple' => false,
            'primary_key' => 'id',
            'text_property' => 'title',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
        ));

        $builder->add('startDate', DateType::class, array(
            'widget' => 'single_text',
        ));
        $builder->add('endDate', DateType::class, array(
            'widget' => 'single_text',
        ));
        $builder->add('venues', Select2EntityType::class, array(
            'remote_route' => 'venue_typeahead',
            'class' => Venue::class,
            'multiple' => true,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
        ));
        $builder->add('excerpt', CKEditorType::class, array(
            'attr' => array(
                'help_block' => 'Excerpts will be shown on the home page and in '
                . 'lists of pages. Leave this field blank and one will be '
                . 'generated automatically.',
            ),
        ));
        $builder->add('content', CKEditorType::class);
        $builder->add('url');
        $builder->add('projectCategory');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Project::class,
        ));
    }
}
