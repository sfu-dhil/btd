<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

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
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

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
