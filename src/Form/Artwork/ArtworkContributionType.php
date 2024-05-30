<?php

declare(strict_types=1);

namespace App\Form\Artwork;

use App\Entity\Artwork;
use App\Entity\ArtworkContribution;
use App\Transformer\HiddenEntityTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtworkContributionType extends AbstractType {
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $artwork = $options['artwork'];
        $builder->add('artwork', HiddenType::class, [
            'data' => $artwork,
            'data_class' => null,
        ]);
        $builder->add('person');
        $builder->add('organization');
        $builder->add('artworkRole');

        $builder->get('artwork')->addModelTransformer(new HiddenEntityTransformer($this->em, Artwork::class));
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => ArtworkContribution::class,
            'artwork' => null,
        ]);
    }
}
