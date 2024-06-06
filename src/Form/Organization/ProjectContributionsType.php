<?php

declare(strict_types=1);

namespace App\Form\Organization;

use App\Entity\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectContributionsType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $organization = $options['organization'];
        $builder->add('projectContributions', CollectionType::class, [
            'entry_type' => ProjectContributionType::class,
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_options' => [
                'organization' => $organization,
                'label' => false,
            ],
            'label' => 'Contribution',
            'attr' => [
                'class' => 'collection-simple',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Organization::class,
            'organization' => null,
        ]);
    }
}
