<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaFileType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('path', FileType::class, array(
            'label' => 'Media file',
            'attr' => array(
                'help_block' => "Max file upload size is {$options['max_file_upload']} bytes",
            )
        ));
        $builder->add('mediaFileType');
        $builder->add('creator');
        $builder->add('title');
        $builder->add('description');
        $builder->add('copyright');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MediaFile'
        ));
        $resolver->setRequired(array('max_file_upload'));
    }

}
