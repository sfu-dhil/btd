<?php

namespace AppBundle\Form;

use AppBundle\Entity\ArtisticStatement;
use AppBundle\Entity\Artwork;
use AppBundle\Entity\Person;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ArtisticStatementType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', null, array(
            'label' => 'Title',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));

        $builder->add('excerpt', CKEditorType::class, array(
            'label' => 'Excerpt',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));

        $builder->add('content', CKEditorType::class, array(
            'label' => 'Content',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));

        $builder->add('artwork', Select2EntityType::class, array(
            'required' => true,
            'label' => 'Artwork',
            'multiple' => false,
            'remote_route' => 'artwork_typeahead',
            'class' => Artwork::class,
            'primary_key' => 'id',
            'text_property' => 'title',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
        ));

        $builder->add('people', Select2EntityType::class, array(
            'required' => true,
            'label' => 'People',
            'multiple' => true,
            'remote_route' => 'person_typeahead',
            'class' => Person::class,
            'primary_key' => 'id',
            'text_property' => 'fullname',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => ArtisticStatement::class,
        ));
    }

}
