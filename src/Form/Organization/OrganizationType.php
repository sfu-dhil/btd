<?php

namespace App\Form\Organization;

use App\Entity\Organization;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizationType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name');
        $builder->add('address');
        $builder->add('description', CKEditorType::class);
        $builder->add('urls', CollectionType::class, array(
            'label' => 'Websites',
            'entry_type' => UrlType::class,
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_options' => array(
                'label' => false,
            ),
            'attr' => array(
                'class' => 'collection-simple',
            ),
        ));
        $builder->add('contact');
        $builder->add('location');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Organization::class,
        ));
    }
}
