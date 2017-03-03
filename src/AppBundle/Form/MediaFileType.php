<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaFileType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('path');
        $builder->add('size');
        $builder->add('mimetype');
        $builder->add('creator');
        $builder->add('title');
        $builder->add('description');
        $builder->add('copyright');
        $builder->add('mediaFileType');
        $builder->add('artworks');
        $builder->add('projects');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MediaFile'
        ));
    }

}
