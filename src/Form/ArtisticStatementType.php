<?php

namespace App\Form;

use App\Entity\ArtisticStatement;
use App\Entity\Artwork;
use App\Entity\Person;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ArtisticStatementType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('title', null, array(
            'label' => 'Title',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));

        $builder->add('excerpt', TextareaType::class, array(
            'label' => 'Excerpt',
            'required' => false,
            'attr' => array(
                'help_block' => '',
                'class' => 'tinymce'
            ),
        ));

        $builder->add('content', TextareaType::class, array(
            'label' => 'Content',
            'required' => true,
            'attr' => array(
                'help_block' => '',
                'class' => 'tinymce'
            ),
        ));

        $builder->add('artwork', EntityType::class, array(
            'required' => true,
            'label' => 'Artwork',
            'class' => Artwork::class,
        ));

        $builder->add('people', Select2EntityType::class, array(
            'remote_route' => 'person_typeahead',
            'class' => Person::class,
            'multiple' => true,
            'primary_key' => 'id',
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
