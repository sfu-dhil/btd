<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\MediaFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaFileType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('file', FileType::class, [
            'label' => 'Media file',
            'attr' => [
                'help_block' => "Maximum file upload size is {$options['max_file_upload']}.",
            ],
        ]);
        $builder->add('mediaFileCategory');
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => MediaFile::class,
        ]);
        $resolver->setRequired(['max_file_upload']);
    }
}
