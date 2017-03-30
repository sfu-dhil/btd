<?php

namespace AppBundle\Form\Project;

use AppBundle\Entity\Project;
use AppBundle\Transformer\HiddenEntityTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContributionType extends AbstractType {

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $project = $options['project'];
        $builder->add('project', HiddenType::class, array(
            'data' => $project,
            'data_class' => null,
        ));
        $builder->add('person');
        $builder->add('organization');
        $builder->add('projectRole');
        
        $builder->get('project')->addModelTransformer(new HiddenEntityTransformer($this->em, Project::class));        
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProjectContribution',
            'project' => null,
        ));
    }

}
