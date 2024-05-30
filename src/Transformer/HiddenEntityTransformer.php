<?php

declare(strict_types=1);

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
 */
class HiddenEntityTransformer implements DataTransformerInterface {
    public function __construct(private ObjectManager $em, private string $class) {}

    public function transform(mixed $entity) : ?string {
        if (null === $entity) {
            return null;
        }

        return $entity->getId();
    }

    public function reverseTransform(mixed $value) : mixed {
        if ( ! $value) {
            return null;
        }

        return $this->em->find($this->class, $value);
    }
}
