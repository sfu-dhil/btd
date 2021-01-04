<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Project;

use App\Entity\Project;
use App\Entity\Venue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ProjectType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('title');

        $builder->add('parent', Select2EntityType::class, [
            'remote_route' => 'project_typeahead',
            'class' => Project::class,
            'multiple' => false,
            'primary_key' => 'id',
            'text_property' => 'title',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
        ]);

        $builder->add('startDate', DateType::class, [
            'widget' => 'single_text',
        ]);
        $builder->add('endDate', DateType::class, [
            'widget' => 'single_text',
        ]);
        $builder->add('venues', Select2EntityType::class, [
            'remote_route' => 'venue_typeahead',
            'class' => Venue::class,
            'multiple' => true,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
        ]);
        $builder->add('excerpt', TextareaType::class, [
            'attr' => [
                'class' => 'ckeditor',
                'help_block' => 'Excerpts will be shown on the home page and in '
                . 'lists of pages. Leave this field blank and one will be '
                . 'generated automatically.',
            ],
        ]);
        $builder->add('content', TextareaType::class, [
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('url');
        $builder->add('projectCategory');
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
