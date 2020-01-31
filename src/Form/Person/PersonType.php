<?php

namespace App\Form\Person;

use App\Entity\Person;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('fullname');
        $builder->add('sortableName');
        $builder->add('biography', CKEditorType::class);
        $builder->add('urls', CollectionType::class, array(
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'delete_empty' => true,
            'entry_type' => UrlType::class,
            'entry_options' => array(
                'label' => false,
            ),
            'label' => 'Links',
            'required' => false,
            'attr' => array(
                'class' => 'collection-simple',
                'help_block' => 'List of URLs associated with the person',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Person::class,
        ));
    }
}
