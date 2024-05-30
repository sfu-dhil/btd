<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Venue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenueType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name');
        $builder->add('address');
        $builder->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('url');
        $builder->add('location');
        $builder->add('venueCategory');
        $builder->add('projects');
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Venue::class,
        ]);
    }
}
