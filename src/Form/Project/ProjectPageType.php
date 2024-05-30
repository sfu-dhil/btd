<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Entity\ProjectPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectPageType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('title');
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
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => ProjectPage::class,
        ]);
    }
}
