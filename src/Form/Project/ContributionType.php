<?php

declare(strict_types=1);

namespace App\Form\Project;

use App\Entity\Project;
use App\Entity\ProjectContribution;
use App\Transformer\HiddenEntityTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContributionType extends AbstractType {
    public function __construct(private EntityManagerInterface $em) {}

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $project = $options['project'];
        $builder->add('project', HiddenType::class, [
            'data' => $project,
            'data_class' => null,
        ]);
        $builder->add('person');
        $builder->add('organization');
        $builder->add('projectRole');

        $builder->get('project')->addModelTransformer(new HiddenEntityTransformer($this->em, Project::class));
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => ProjectContribution::class,
            'project' => null,
        ]);
    }
}
