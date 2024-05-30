<?php

declare(strict_types=1);

namespace App\Form\Person;

use App\Entity\Person;
use App\Entity\ProjectContribution;
use App\Transformer\HiddenEntityTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectContributionType extends AbstractType {
    public function __construct(private EntityManagerInterface $em) {}

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
