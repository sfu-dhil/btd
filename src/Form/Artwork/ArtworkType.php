<?php

declare(strict_types=1);

namespace App\Form\Artwork;

use App\Entity\Artwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtworkType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('title');
        $builder->add('artworkCategory');
        $builder->add('excerpt', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('content', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('materials', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('copyright', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Artwork::class,
        ]);
    }
}
