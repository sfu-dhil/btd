<?php

namespace AppBundle\Form;

use AppBundle\Entity\ArtisticStatement;
use AppBundle\Entity\Artwork;
use AppBundle\Entity\Person;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

        $builder->add('artwork', EntityType::class, array(
            'required' => true,
            'label' => 'Artwork',
            'class' => Artwork::class,
        ));

        $builder->add('people', CollectionType::class, array(
            'required' => true,
            'label' => 'People',
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
