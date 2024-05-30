<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\VenueCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenueCategoryType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name');
        $builder->add('label');
        $builder->add('description');
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => VenueCategory::class,
        ]);
    }
}
