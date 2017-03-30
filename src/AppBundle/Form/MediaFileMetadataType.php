<?php

namespace AppBundle\Form;

use Nines\DublinCoreBundle\Entity\AbstractField;
use Nines\DublinCoreBundle\Entity\Element;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaFileMetadataType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $em = $options['entityManager'];
        $mediaFile = $options['mediaFile'];
        
        foreach($em->getRepository(Element::class)->findAll() as $element) {
            $fields = $mediaFile->getMetadataFields($element->getName());
            $values = $fields->map(function(AbstractField $field){
                        return $field->getValue();
            })->toArray();
            
            $builder->add($element->getName(), CollectionType::class, array(
                'label' => $element->getLabel(),
                'entry_type' => TextType::class,
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'attr' => array(
                    'help_block' => $element->getDescription(),
                    'group_class' => 'collection',
                ),
                'data' => array_merge(array_values($values), array(' ')),
            ));
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setRequired(array('mediaFile', 'entityManager'));
    }

}
