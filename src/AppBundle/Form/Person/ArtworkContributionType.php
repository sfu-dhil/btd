<?php

/*
 * To change this license header, choose License Headers in Artwork Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Person;

use AppBundle\Entity\ArtworkContribution;
use AppBundle\Entity\Person;
use AppBundle\Transformer\HiddenEntityTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ArtworkContribution
 *
 * @author michael
 */
class ArtworkContributionType extends AbstractType {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $person = $options['person'];
        $builder->add('person', HiddenType::class, array(
            'data' => $person,
            'data_class' => null,
        ));
        $builder->add('artworkRole');
        $builder->add('artwork');
        
        $builder->get('person')->addModelTransformer(new HiddenEntityTransformer($this->em, Person::class));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => ArtworkContribution::class,
            'person' => null,
        ));
    }

}
