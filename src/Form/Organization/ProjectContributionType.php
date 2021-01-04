<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Organization;

use App\Entity\Organization;
use App\Entity\ProjectContribution;
use App\Transformer\HiddenEntityTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ProjectContribution.
 *
 * @author michael
 */
class ProjectContributionType extends AbstractType {
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $organization = $options['organization'];
        $builder->add('organization', HiddenType::class, [
            'data' => $organization,
            'data_class' => null,
        ]);
        $builder->add('projectRole');
        $builder->add('project');

        $builder->get('organization')->addModelTransformer(new HiddenEntityTransformer($this->em, Organization::class));
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => ProjectContribution::class,
            'organization' => null,
        ]);
    }
}
