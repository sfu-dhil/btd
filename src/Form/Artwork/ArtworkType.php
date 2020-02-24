<?php

namespace App\Form\Artwork;

use App\Entity\Artwork;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtworkType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('title');
        $builder->add('artworkCategory');
        $builder->add('excerpt', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce'
            ]
        ]);
        $builder->add('content', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce'
            ]
        ]);
        $builder->add('materials', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce'
            ]
        ]);
        $builder->add('copyright', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce'
            ]
        ]);
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
