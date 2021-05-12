<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Person;

use App\Entity\Person;
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
        $person = $options['person'];
        $builder->add('person', HiddenType::class, [
            'data' => $person,
            'data_class' => null,
        ]);
        $builder->add('projectRole');
        $builder->add('project');

        $builder->get('person')->addModelTransformer(new HiddenEntityTransformer($this->em, Person::class));
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => ProjectContribution::class,
            'person' => null,
        ]);
    }
}
