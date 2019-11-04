<?php

namespace AppBundle\Form;

use AppBundle\Entity\MediaFile;
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
        $builder->add('file', FileType::class, array(
            'label' => 'Media file',
            'attr' => array(
                'help_block' => "Maximum file upload size is {$options['max_file_upload']}.",
            ),
        ));
        $builder->add('mediaFileCategory');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => MediaFile::class,
        ));
        $resolver->setRequired(array('max_file_upload'));
    }
}
