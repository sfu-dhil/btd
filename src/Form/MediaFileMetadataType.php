<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use Nines\DublinCoreBundle\Entity\AbstractField;
use Nines\DublinCoreBundle\Entity\Element;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaFileMetadataType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $em = $options['entityManager'];
        $mediaFile = $options['mediaFile'];

        foreach ($em->getRepository(Element::class)->findAll() as $element) {
            $fields = $mediaFile->getMetadataFields($element->getName());
            $values = $fields->map(function (AbstractField $field) {
                return $field->getValue();
            })->toArray();

            $builder->add($element->getName(), CollectionType::class, [
                'label' => $element->getLabel(),
                'entry_type' => TextType::class,
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'entry_options' => [
                    'label' => false,
                ],
                'by_reference' => false,
                'attr' => [
                    'help_block' => $element->getDescription(),
                    'class' => 'collection-simple',
                ],
                'data' => array_merge(array_values($values), [' ']),
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setRequired(['mediaFile', 'entityManager']);
    }
}
