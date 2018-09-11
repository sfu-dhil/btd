<?php

namespace AppBundle\Form\Artwork;

use AppBundle\Entity\Artwork;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtworkType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title');
        $builder->add('artworkCategory');
        $builder->add('description', CKEditorType::class);
        $builder->add('materials', CKEditorType::class);
        $builder->add('copyright', CKEditorType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Artwork::class,
        ));
    }

}
