<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Entity\Artwork;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtworksType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('artworks', EntityType::class, [
            'expanded' => true,
            'multiple' => true,
            'class' => Artwork::class,
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'project' => null,
        ]);
    }
}
