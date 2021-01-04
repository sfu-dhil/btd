<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Transformer;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Description of HiddenEntityTransformer.
 *
 * Configure the form as a service:
 * services:
 *     app.form.date_year_type:
 *         class: App\Form\DateYearType
 *         arguments: [ "@doctrine.orm.entity_manager" ]
 *         tags:
 *             - { name: form.type }
 *
 * add the hidden field and transformer:
 *         $builder->add('work', HiddenType::class, array(
 *             'data' => $work,
 *             'data_class' => null,
 *         ));
 *         $builder->add('dateCategory');
 *         $builder->add('value');
 *
 *         $builder->get('work')->addModelTransformer(new HiddenEntityTransformer($this->em, Work::class));
 *
 * @author michael
 */
class HiddenEntityTransformer implements DataTransformerInterface {
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var string
     */
    private $class;

    /**
     * Build and configure the transformer.
     *
     * @param type $class
     */
    public function __construct(ObjectManager $em, $class) {
        $this->em = $em;
        $this->class = $class;
    }

    /**
     * Transform an entity to a string.
     *
     * @param null|object $entity
     *
     * @return string
     */
    public function transform($entity) {
        if (null === $entity) {
            return;
        }

        return $entity->getId();
    }

    /**
     * Transforms  string into an entity.
     *
     * @param string $value
     *
     * @return null|object
     */
    public function reverseTransform($value) {
        if ( ! $value) {
            return;
        }

        return $this->em->find($this->class, $value);
    }
}
