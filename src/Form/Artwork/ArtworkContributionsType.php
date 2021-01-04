<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Artwork;

use App\Entity\Artwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ArtworkContributions.
 *
 * @author michael
 */
class ArtworkContributionsType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $artwork = $options['artwork'];
        $builder->add('contributions', CollectionType::class, [
            'entry_type' => ArtworkContributionType::class,
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_options' => [
                'label' => false,
                'artwork' => $artwork,
            ],
            'by_reference' => false,
            'label' => 'Contribution',
            'attr' => [
                'class' => 'collection-simple',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Artwork::class,
            'artwork' => null,
        ]);
    }
}
