<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Person;

use AppBundle\Entity\Person;
use AppBundle\Entity\ProjectContribution;
use AppBundle\Transformer\HiddenEntityTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ProjectContribution
 *
 * @author michael
 */
class ProjectContributionType extends AbstractType {

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $person = $options['person'];
        $builder->add('person', HiddenType::class, array(
            'data' => $person,
            'data_class' => null,
        ));
        $builder->add('projectRole');
        $builder->add('project');
        
        $builder->get('person')->addModelTransformer(new HiddenEntityTransformer($this->em, Person::class));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => ProjectContribution::class,
            'person' => null,
        ));
    }

}
